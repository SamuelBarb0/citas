<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\SelectOption;

class DynamicSelect extends Component
{
    public $tipo;
    public $name;
    public $id;
    public $value;
    public $required;
    public $placeholder;
    public $class;
    public $useSelect2;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $tipo,
        $name = null,
        $id = null,
        $value = null,
        $required = false,
        $placeholder = 'Selecciona...',
        $class = '',
        $useSelect2 = false
    ) {
        $this->tipo = $tipo;
        $this->name = $name ?? $tipo;
        $this->id = $id ?? $name ?? $tipo;
        $this->value = $value;
        $this->required = $required;
        $this->placeholder = $placeholder;
        $this->class = $class;
        $this->useSelect2 = $useSelect2;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $options = SelectOption::getGroupedByType($this->tipo);

        return view('components.dynamic-select', [
            'options' => $options
        ]);
    }
}
