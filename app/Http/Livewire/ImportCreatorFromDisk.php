<?php

namespace App\Http\Livewire;

use App\Traits\ContentPathAutocompletion;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Livewire\Component;

class ImportCreatorFromDisk extends Component
{
    use ContentPathAutocompletion;

    public bool $modalOpened = false;

    protected $listeners = ['toggleModal'];

    public function render()
    {
        return view('livewire.import-creator-from-disk');
    }

    public function toggleModal()
    {
        $this->modalOpened = !$this->modalOpened;
    }

    public function import()
    {
        $this->validate([
            'path' => 'required|string|content_path',
        ]);

        $this->modalOpened = false;

        Artisan::call('crawl:creators', [
            'path' => Str::trimSlashes($this->path),
        ]);

        $this->path = null;
        $this->clearSuggestions();
    }
}
