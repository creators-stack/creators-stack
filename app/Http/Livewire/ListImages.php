<?php

namespace App\Http\Livewire;

use App\Enums\ContentType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListImages extends FileListingComponent
{
    public string $has = 'images';

    protected ?ContentType $content_type = ContentType::IMAGE;
    protected int $per_page = 30;
    protected LengthAwarePaginator $images;

    public function updatedPaginators()
    {
        $this->dispatchBrowserEvent('initGallery');
    }

    public function render()
    {
        $this->images = $this->getContent();

        return view('livewire.list-images', [
            'images' => $this->images,
        ]);
    }
}
