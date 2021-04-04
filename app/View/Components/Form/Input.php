<?php

namespace App\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;

class Input extends Component
{
    /**
     * Create a new component instance.
     *
     * @param string $label
     * @param ViewErrorBag $errors
     * @param string|null $help
     *
     * @return void
     */
    public function __construct(public string $label, public ViewErrorBag $errors, public ?string $help = null)
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render()
    {
        return view('components.form.input');
    }
}
