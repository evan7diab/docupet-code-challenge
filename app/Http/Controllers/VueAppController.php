<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

class VueAppController extends Controller
{
    /**
     * Show the Vue app container view (resources/views/vue-app-container.blade.php).
     */
    public function __invoke(): View
    {
        return ViewFacade::file(resource_path('views/vue-app-container.blade.php'));
    }
}
