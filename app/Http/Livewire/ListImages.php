<?php

namespace App\Http\Livewire;

use App\Models\ContentType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListImages extends FileListingComponent
{
    public string $has = 'images';

    protected ?int $content_type = ContentType::IMAGE;
    protected int $per_page = 30;
    protected LengthAwarePaginator $images;

    public function setPage($page)
    {
        $this->page = $page;
        $this->dispatchBrowserEvent('scrollTop');
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
