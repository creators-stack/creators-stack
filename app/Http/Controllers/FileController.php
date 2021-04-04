<?php

namespace App\Http\Controllers;

use App\Models\ContentType;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileController extends StreamController
{
    /**
     * @param File $file
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function serveFile(File $file)
    {
        return response()->file(Storage::disk('content')->path($file->path));
    }

    public function streamVideo(File $file)
    {
        if ($file->content_type_id !== ContentType::VIDEO) {
            abort(404);
        }

        return $this->stream($file);
    }
}
