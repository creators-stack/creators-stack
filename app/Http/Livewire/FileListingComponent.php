<?php

namespace App\Http\Livewire;

use App\Enums\ContentType;
use App\Models\Creator;
use App\Models\File;
use App\Models\View;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

abstract class FileListingComponent extends Component
{
    use WithPagination;

    public ?Creator $creator = null;
    public ?string $search = null;
    public ?string $creator_username = null;
    public string $sort_by = 'updated_at';
    public string $sort_order = 'desc';

    protected ?ContentType $content_type = null;
    protected int $per_page = 20;

    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->queryString = array_merge($this->queryString, [
            'page' => ['except' => 1],
            'search' => ['except' => ''],
            'creator_username' => ['except' => ''],
            'sort_by' => ['except' => ''],
            'sort_order' => ['except' => ''],
        ]);

        $this->listeners = array_merge($this->listeners, [
            'creatorSelected',
            'creatorReseted',
            'sortSelected',
        ]);
    }

    public function updatingPaginators()
    {
        $this->dispatchBrowserEvent('scrollTop');
    }

    public function sortSelected($sort_by, $sort_order)
    {
        $this->sort_by = $sort_by;
        $this->sort_order = $sort_order;
        $this->resetPage();
    }

    public function creatorSelected(string $creator_username)
    {
        $this->creator_username = $creator_username;
        $this->resetPage();
    }

    public function creatorReseted()
    {
        $this->creator_username = null;
        $this->creator = null;
        $this->resetPage();
    }

    protected function setCreator()
    {
        if (! empty($this->creator_username)) {
            $this->creator = Creator::where('username', $this->creator_username)->firstOrFail();
        }
    }

    protected function getContent(): LengthAwarePaginator
    {
        $rules = [
            'search' => 'nullable|string',
            'creator_username' => 'nullable|string',
            'sort_by' => 'nullable|string|in:created_at,updated_at,size,views_count,latest_view',
            'sort_order' => 'nullable|string|in:asc,desc',
        ];

        $validator = Validator::make($this->getDataForValidation($rules), $rules);

        if ($validator->fails()) {
            abort(400);
        }

        $this->setCreator();

        return File::withCount('views')
            ->whereNotNull('thumbnail')
            ->when($this->content_type, fn (Builder $query) => $query->where('content_type', $this->content_type))
            ->when($this->creator, fn (Builder $query) => $query->where('creator_id', $this->creator->id))
            ->when($this->search, fn (Builder $query) => $query->where('path', 'like', sprintf('%%%s%%', $this->search)))
            ->when($this->sort_by === 'latest_view', function (Builder $query) {
                return $query->orderBy(
                    View::select('created_at')
                        ->whereColumn('file_id', 'files.id')
                        ->orderByDesc('created_at')
                        ->limit(1),
                    $this->sort_order
                );
            }, fn (Builder $query) => $query->orderBy($this->sort_by, $this->sort_order))
            ->paginate($this->per_page);
    }

    protected function updatingSearch()
    {
        $this->resetPage();
    }
}
