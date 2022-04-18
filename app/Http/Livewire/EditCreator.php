<?php

namespace App\Http\Livewire;

use App\Console\Commands\CrawlFilesCommand;
use App\Jobs\GenerateProfileThumbnail;
use App\Models\Creator;
use App\Traits\ContentPathAutocompletion;
use Illuminate\Support\Facades\Artisan;
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
        'creator.url' => 'nullable|string|importable_url',
        'path' => 'required|string|max:255|content_path|unique:creators,root_folder',
        'profile_picture' => 'nullable|image',
    ];

    public function mount(Creator $creator)
    {
        $this->creator = $creator;
        $this->path = $this->creator->root_folder;
    }

    public function render()
    {
        return view('livewire.edit-creator');
    }

    public function save()
    {

        if ($this->creator->exists) {
            $this->rules['path'] .= sprintf(',%d', $this->creator->id);
        }

        $this->validate();

        $this->path = Str::trimSlashes($this->path);

        $this->creator->root_folder = $this->path;

        $this->creator->save();

        if ($this->profile_picture) {
            GenerateProfileThumbnail::dispatchSync($this->creator, $this->profile_picture->getRealPath());
        }

        $this->redirectRoute('creators.view', $this->creator);
    }

    public function goBack()
    {
        if ($this->creator->exists) {
            $this->redirectRoute('creators.view', $this->creator);
        } else {
            $this->redirectRoute('creators');
        }
    }

    protected function updatedProfilePicture()
    {
        $this->validateOnly('profile_picture');
    }
}
