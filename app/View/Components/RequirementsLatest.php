<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Request;
use App\Models\Requirement;


class RequirementsLatest extends Component
{
    /**
     * Create a new component instance.
     */

     public $requirements;

    public function __construct()
    {
        $this->requirements = Requirement::with('user')
            ->latest()
            // ->paginate();
            // ->take(5)
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.requirements-latest');
    }
}
