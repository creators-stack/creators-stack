<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SortDropdownSelect extends Component
{
    public ?int $option = null;
    public array $options = [
        [
            'name' => 'Created At',
            'field' => 'created_at',
            'order' => 'desc',
        ],
        [
            'name' => 'Created At',
            'field' => 'created_at',
            'order' => 'asc',
        ],
        [
            'name' => 'Updated At',
            'field' => 'updated_at',
            'order' => 'desc',
        ],
        [
            'name' => 'Updated At',
            'field' => 'updated_at',
            'order' => 'asc',
        ],
        [
            'name' => 'Size',
            'field' => 'size',
            'order' => 'desc',
        ],
        [
            'name' => 'Size',
            'field' => 'size',
            'order' => 'asc',
        ],
    ];

    public function mount(string $field, string $order)
    {
        $this->option = collect($this->options)
            ->search(fn (array $option) => $option['field'] === $field && $option['order'] === $order) ?? null;
    }

    public function render()
    {
        return view('livewire.sort-dropdown-select');
    }

    public function sortBy(int $id)
    {
        if (empty($this->options[$id])) {
            abort(404);
        }

        $this->option = $id;
        $this->emitUp('sortSelected', $this->options[$id]['field'], $this->options[$id]['order']);
    }
}
