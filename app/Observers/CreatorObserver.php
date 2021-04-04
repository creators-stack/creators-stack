<?php

namespace App\Observers;

use App\Models\Creator;
use Illuminate\Support\Facades\Storage;

class CreatorObserver
{
    /**
     * Handle the Creator "deleting" event.
     *
     * @param  \App\Models\Creator  $creator
     * @return void
     */
    public function deleting(Creator $creator)
    {
        if ($creator->profile_picture) {
            Storage::disk('public')->delete($creator->profile_picture);
        }

        foreach ($creator->files as $file) {
            if ($file->thumbnail) {
                Storage::disk('public')->delete($file->thumbnail);
            }
            if ($file->preview) {
                Storage::disk('public')->delete($file->preview);
            }
        }
    }
}
