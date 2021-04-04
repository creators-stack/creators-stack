<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\Settings;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GenerateVideoPreview implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected array $filter;
    protected array $sub_parts = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected File $file, protected Settings $settings)
    {
        $this->filter = [
            sprintf('scale=%d:%d:force_original_aspect_ratio=decrease', File::PREVIEW_WIDTH, File::PREVIEW_HEIGHT),
            sprintf('pad=%d:%d:-1:-1:color=black', File::PREVIEW_WIDTH, File::PREVIEW_HEIGHT),
        ];
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId(): string
    {
        return md5($this->file->path);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parts_count = $this->settings->videos_preview_parts_count;
        $parts_length = $this->settings->videos_preview_parts_length / 1000;

        $dir = 'thumbnails/videos/';
        $this->file->preview = sprintf('%s%s.mp4', $dir, md5(uniqid('hash', true)));

        Storage::disk('public')->makeDirectory($dir);

        $tmp = 'thumbnails/tmp/';
        Storage::disk('public')->makeDirectory($tmp);

        $path = Storage::disk('content')->path($this->file->path);

        /**
         * Duration of the video in seconds.
         */
        $duration = FFProbe::create()
            ->format($path)
            ->get('duration');

        if ($parts_count * $parts_length > $duration) {
            Log::error(sprintf('Could not generate video preview thumbnail for: %s because the preview duration is greater than the actual video', $this->file->path));

            return;
        }

        $gaps_count = $parts_count + 1;
        $gaps_total_count = $duration - ($parts_count * $parts_length);
        $gaps_length = $gaps_total_count / $gaps_count;

        for ($part = 0; $part < $parts_count; $part++) {
            $this->sub_parts[$part] = sprintf('%s%s_%d.mp4', $tmp, md5($this->file->path), $part);
            $start = $gaps_length + ($gaps_length * $part) + ($parts_length * $part);

            FFMpeg::create()
                ->open($path)
                ->clip(TimeCode::fromSeconds($start), TimeCode::fromSeconds($parts_length))
                ->addFilter(new CustomFilter(implode(',', $this->filter)))
                ->addFilter(new CustomFilter(implode(',', $this->filter)))
                ->save((new X264('aac', 'libx264'))->setPasses(1), Storage::disk('public')->path($this->sub_parts[$part]));
        }

        FFMpeg::create()
            ->open(Storage::disk('public')->path($this->sub_parts[0]))
            ->concat(array_map([Storage::disk('public'), 'path'], $this->sub_parts))
            ->saveFromSameCodecs(Storage::disk('public')->path($this->file->preview), true);

        $this->cleanTmpFiles();

        $this->file->save();
    }

    /**
     * @param Throwable $exception
     */
    public function failed(Throwable $exception)
    {
        $this->cleanTmpFiles();
    }

    /**
     * Clean temporary parts.
     */
    protected function cleanTmpFiles()
    {
        while (! empty($this->sub_parts)) {
            Storage::disk('public')->delete(array_pop($this->sub_parts));
        }
    }
}
