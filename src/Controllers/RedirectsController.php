<?php

namespace HyroPlugins\SeoOptimizer\Controllers;

use Illuminate\Routing\Controller;

class RedirectsController extends Controller
{
    public function index()
    {
        return view('hyro-plugin-seo-optimizer::redirects');
    }
}
