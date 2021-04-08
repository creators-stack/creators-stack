<?php

namespace App\Http\Livewire;

use App\Models\ContentType;
use App\Models\File;
use Livewire\Component;

class ViewVideo extends Component
{
    public File $video;

    public function mount(string $hash)
    {
        $this->video = File::with('creator')
            ->where('content_type_id', ContentType::VIDEO)
            ->where('hash', $hash)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.view-video');
    }
}
