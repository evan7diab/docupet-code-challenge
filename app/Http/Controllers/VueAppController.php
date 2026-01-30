<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class VueAppController extends Controller
{
    /**
     * Show the Vue app container view (resources/views/vue/vue-app-container.blade.php).
     */
    public function __invoke(): View
    {
        return view('vue.vue-app-container');
    }
}
