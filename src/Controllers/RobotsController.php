<?php

namespace HyroPlugins\SeoOptimizer\Controllers;

use HyroPlugins\SeoOptimizer\Models\SeoSetting;
use Illuminate\Routing\Controller;

class RobotsController extends Controller
{
    public function index()
    {
        $content = SeoSetting::get('robots_content', "User-agent: *\nDisallow:");
        
        // Add sitemap reference
        $content .= "\n\nSitemap: " . url('/sitemap.xml');
        
        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
