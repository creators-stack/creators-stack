<?php

namespace App\Http\Livewire;

use App\Models\Creator;
use App\Traits\ContentPathAutocompletion;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class EditCreator extends Component
{
    use WithFileUploads;
    use ContentPathAutocompletion;

    public Creator $creator;

    /**
     * @var TemporaryUploadedFile
     */
    public $profile_picture;

    protected array $rules = [
        'creator.name' => 'required|string|max:255',
        'creator.username' => 'required|string|max:255',
        'profile_picture' => 'nullable|image',
        'path' => 'required|string|max:255|content_path|unique:creators,root_folder',
    ];

    public function mount(Creator $creator)
    {
        $this->creator = $creator;
        $this->path = $this->creator->root_folder;

        if ($this->creator->exists) {
            $this->rules['path'] .= sprintf(',%d', $this->creator->id);
        }
    }

    public function render()
    {
        return view('livewire.edit-creator');
    }

    public function save()
    {
        $path = $this->path;
        $this->path = Str::trimSlashes($this->path);

        try {
            $this->validate();
        } catch (ValidationException $exception) {
            $this->path = $path;

            throw $exception;
        }

        $this->path = $path;
        $this->creator->root_folder = $this->path;

        if ($this->profile_picture) {
            $this->creator->profile_picture = $this->profile_picture->store('thumbnails/profile_pictures/', $disk = 'public');
        }

        $this->creator->save();

        $this->redirectRoute('creators.view', $this->creator);
    }
}
