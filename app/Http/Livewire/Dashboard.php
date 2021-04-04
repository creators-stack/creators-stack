<?php

namespace App\Http\Livewire;

use App\Helpers\FileSystemHelper;
use App\Models\Creator;
use App\Models\File;
use Livewire\Component;

class Dashboard extends Component
{
    public string $creators_count;
    public string $images_count;
    public string $videos_count;
    public string $images_size;
    public string $videos_size;
    public string $total_size;

    public function mount()
    {
        $this->creators_count = number_format(Creator::count(), 0, '.', ' ');
        $this->images_count = number_format(File::images()->count(), 0, '.', ' ');
        $this->videos_count = number_format(File::videos()->count(), 0, '.', ' ');

        $images_size = File::images()->sum('size');
        $videos_size = File::videos()->sum('size');

        $this->images_size = FileSystemHelper::humanSize($images_size);
        $this->videos_size = FileSystemHelper::humanSize($videos_size);
        $this->total_size = FileSystemHelper::humanSize($images_size + $videos_size);
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
