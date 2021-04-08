<?php

namespace App\Observers;

use App\Models\Creator;
use Illuminate\Database\Eloquent\Collection;
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

        $creator->files()->chunk(200, fn (Collection $files) => $files->each->delete());
    }
}
