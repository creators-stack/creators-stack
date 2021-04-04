<?php

namespace App\Http\Livewire;

use App\Models\Creator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;

class CreatorsDropdownSelect extends Component
{
    public bool $opened = false;
    public ?string $creator_query = null;
    public string $has;
    public Collection $creators;
    public ?Creator $creator;

    public function render()
    {
        $this->handleCreators();

        return view('livewire.creators-dropdown-select');
    }

    public function resetCreator()
    {
        $this->opened = false;
        $this->creator = null;
        $this->creator_query = null;
        $this->emitUp('creatorReseted');
    }

    public function selectCreator(Creator $creator)
    {
        $this->opened = false;
        $this->creator = $creator;
        $this->emitUp('creatorSelected', $creator->username);
    }

    protected function handleCreators()
    {
        $this->creators = Creator::whereHas($this->has, function (Builder $query) {
            $query->whereNotNull('thumbnail');
        })
            ->when($this->creator_query, function (Builder $query) {
                $query->where('name', 'like', sprintf('%%%s%%', $this->creator_query));
            })
            ->limit(5)
            ->get()
            ->map(function (Creator $creator) {
                $creator->profile_picture = $creator->profilePictureUrl(true);

                return $creator;
            });
    }

    protected function updatingCreatorQuery()
    {
        $this->opened = true;
    }
}
