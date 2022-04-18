<?php

namespace App\Http\Livewire;

use App\Enums\ContentType;
use App\Models\Settings;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;

class ListVideos extends FileListingComponent
{
    public string $has = 'videos';
    public ?Settings $settings;

    protected ?ContentType $content_type = ContentType::VIDEO;
    protected int $per_page = 28;
    protected LengthAwarePaginator $videos;

    public function mount()
    {
        $this->settings = App::make(Settings::class);
    }

    public function render()
    {
        $this->videos = $this->getContent();

        return view('livewire.list-videos', [
            'videos' => $this->videos,
        ]);
    }
}
