<?php

namespace App\Http\Livewire;

use App\Models\ContentType;
use App\Models\File;
use App\Models\View;
use Livewire\Component;

class ViewVideo extends Component
{
    public File $video;

    public function mount(string $hash)
    {
        $this->video = File::with('creator')
            ->with('views')
            ->videos()
            ->where('hash', $hash)
            ->firstOrFail();

        $latest = $this->video->views->first();

        if (!$latest || ($latest->ip !== request()->ip() && $latest->created_at->diffInHours(now()) >= 1)) {
            $this->video->views->push($this->video->views()->create([
                'ip' => request()->ip(),
            ]));
        }
    }

    public function render()
    {
        return view('livewire.view-video');
    }
}
