<?php

namespace HyroPlugins\SeoOptimizer\Controllers;

use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        return view('hyro-plugin-seo-optimizer::settings');
    }
}
