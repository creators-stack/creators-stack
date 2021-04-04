<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StreamController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @var resource
     */
    private $stream;
    private array $headers;
    private string $path;
    private int $buffer = 102400;
    private int $start = 0;
    private int $end;
    private int $size;

    protected function stream(File $file)
    {
        $this->path = Storage::disk('content')->path($file->path);
        $this->size = $file->size;
        $this->end = $this->size - 1;

        if (! file_exists($this->path)) {
            abort(404);
        }

        if (! ($this->stream = fopen($this->path, 'rb'))) {
            Log::error(sprintf('Could not open stream at path %s', $this->path));
            abort(500);
        }

        $status = $this->setStreamHeaders();

        if ($status !== 206) {
            return response()->noContent($status, $this->headers);
        }

        return response()->stream(fn () => $this->partialStream(), $status, $this->headers);
    }

    private function setStreamHeaders(): int
    {
        $this->headers['Content-Type'] = sprintf('video/%s', Str::afterLast($this->path, '.') ?? 'mp4');
        $this->headers['Accept-Ranges'] = sprintf('0-%d', $this->end);

        $request = request();

        if ($request->headers->has('range') === true) {
            $range_string = Str::after($request->headers->get('range'), 'bytes=');
            $ranges = array_filter(explode('-', $range_string), fn (string $part) => $part !== '');

            if (empty($ranges) || in_array(false, array_map('is_numeric', $ranges), true)) {
                return 416;
            }

            $start = (int) $ranges[0];

            if ($start > $this->end || $start > $this->size - 1) {
                return 416;
            }

            $this->start = $start;

            if (count($ranges) === 2) {
                $end = (int) $ranges[1];

                if ($end < $start || $end > $this->size - 1) {
                    return 416;
                }
            }

            /*
             * Moving pointer
             */
            fseek($this->stream, $this->start);

            $this->headers['Content-Length'] = $this->end - $this->start + 1;
            $this->headers['Content-Range'] = sprintf('bytes %d-%d/%d', $this->start, $this->end, $this->size);

            return 206;
        }

        return 200;
    }

    private function partialStream()
    {
        $offset = $this->start;

        while ($offset <= $this->end) {
            $bytesToRead = $this->buffer;
            if ($offset + $bytesToRead > $this->end) {
                $bytesToRead = $this->end - $offset + 1;
            }

            echo fread($this->stream, $bytesToRead);
            flush();

            $offset += $bytesToRead;
        }

        fclose($this->stream);
    }
}
