<?php

namespace App\Http\Livewire;

use App\Models\Creator;
use App\Traits\ContentPathAutocompletion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class ListCreators extends Component
{
    use WithPagination;
    use ContentPathAutocompletion;

    public string $search = '';
    public bool $opened = false;

    /** @var LengthAwarePaginator */
    protected $creators;

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $rules = [
        'path' => 'required|string|content_path',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->creators = Creator::orderByDesc('updated_at')
            ->withCount('images')
            ->withCount('videos')
            ->when($this->search, fn (Builder $query) => $query->where('name', 'like', sprintf('%%%s%%', $this->search)))
            ->orderByDesc('updated_at')
            ->paginate(20);

        return view('livewire.list-creators', [
            'creators' => $this->creators,
        ]);
    }

    public function createFromDisk()
    {
        $this->validate();

        $this->opened = false;

        Artisan::call('crawl:creators', [
            'path' => Str::trimSlashes($this->path),
        ]);

        $this->path = null;
        $this->feedSuggestions();
    }
}
