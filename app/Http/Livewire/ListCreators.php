<?php

namespace App\Http\Livewire;

use App\Models\Creator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class ListCreators extends Component
{
    use WithPagination;

    public string $search = '';

    /** @var LengthAwarePaginator */
    protected $creators;

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPaginators()
    {
        $this->dispatchBrowserEvent('scrollTop');
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
}
