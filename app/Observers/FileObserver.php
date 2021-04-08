<?php

namespace App\Observers;

use App\Models\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class FileObserver
{
    /**
     * Handle the File "deleted" event.
     *
     * @param  \App\Models\File  $file
     * @return void
     */
    public function deleted(File $file)
    {
        if ($file->thumbnail) {
            Storage::disk('public')->delete($file->thumbnail);
        }
        if ($file->preview) {
            Storage::disk('public')->delete($file->preview);
        }

        Cache::forget(File::MIME_TYPE_CACHE_KEY.$file->id);
    }
}
