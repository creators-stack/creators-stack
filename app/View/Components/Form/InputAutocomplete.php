<?php

namespace App\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputAutocomplete extends Component
{
    /**
     * Create a new component instance.
     *
     * @param string $label
     * @param ?string $path
     * @param ?string $help
     *
     * @return void
     */
    public function __construct(public string $label, public ?string $help = null)
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render()
    {
        return view('components.form.input-autocomplete');
    }
}
