<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class ResolverController extends Controller
{
    /**
     * @return Factory|View
     */
    public function resolve()
    {
        return view('index');
    }
}
