<?php

namespace App\Http\Livewire;

use App\Jobs\ImportContentFromUrl;
use App\Models\Creator;
use App\Traits\ContentPathAutocompletion;
use Illuminate\Support\Str;
use Livewire\Component;

class ImportCreatorFromUrl extends Component
{
    use ContentPathAutocompletion;

    public bool $modalOpened = false;
    public ?string $creatorName = null;
    public ?string $creatorUrl = null;

    protected $listeners = ['toggleModal'];

    public function render()
    {
        return view('livewire.import-creator-from-url');
    }

    public function toggleModal()
    {
        $this->modalOpened = !$this->modalOpened;
    }

    public function import()
    {
        $this->validate([
            'creatorName' => 'required|string|unique:creators,name',
            'creatorUrl' => 'required|string|importable_url',
            'path' => 'required|string|content_path',
        ]);

        $creator = new Creator();

        $creator->name = $this->creatorName;
        $creator->username = Str::slug($this->creatorName);
        $creator->url = $this->creatorUrl;
        $creator->root_folder = Str::trimSlashes(implode('/', [$this->path, Str::slug($this->creatorName)]));

        $creator->save();

        dispatch(new ImportContentFromUrl($creator->id));

        // Clear fields
        $this->creatorName = null;
        $this->creatorUrl = null;
        $this->path = null;
        $this->clearSuggestions();

        // Close modal
        $this->modalOpened = false;

        $this->clearSuggestions();
        $this->redirectRoute('creators.view', $creator);
    }
}
