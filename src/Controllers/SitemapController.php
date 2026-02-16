<?php

namespace HyroPlugins\SeoOptimizer\Controllers;

use HyroPlugins\SeoOptimizer\Services\SitemapService;
use Illuminate\Routing\Controller;

class SitemapController extends Controller
{
    public function index(SitemapService $sitemap)
    {
        // Add static pages
        $sitemap->addUrl(url('/'), [
            'changefreq' => 'daily',
            'priority' => '1.0',
        ]);
        
        // Add models (you can configure this in settings)
        // Example: $sitemap->addModel(\App\Models\Post::class);
        
        $xml = $sitemap->generate();
        
        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }
}
