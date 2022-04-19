<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    /**
     * @param File $file
     *
     * @return BinaryFileResponse
     */
    public function serveFile(File $file): BinaryFileResponse
    {
        return response()->file(Storage::disk('content')->path($file->path));
    }
}
