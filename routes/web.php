<?php

use Illuminate\Support\Facades\Route;

// Public routes - using closures to avoid autoloading issues
Route::get('/sitemap.xml', function () {
    // Manually require dependencies
    require_once __DIR__ . '/../src/Services/SitemapService.php';
    require_once __DIR__ . '/../src/Services/MetaService.php';
    
    try {
        $sitemap = new \HyroPlugins\SeoOptimizer\Services\SitemapService();
        
        // Add static pages
        $sitemap->addUrl(url('/'), [
            'changefreq' => 'daily',
            'priority' => '1.0',
        ]);
        
        $xml = $sitemap->generate();
        
        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    } catch (\Exception $e) {
        return response('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>', 200)
            ->header('Content-Type', 'application/xml');
    }
})->name('sitemap');

Route::get('/robots.txt', function () {
    $content = \Illuminate\Support\Facades\DB::table('seo_settings')->where('key', 'robots_content')->value('value');
    
    if (!$content) {
        $content = "User-agent: *\nDisallow:";
    }
    
    // Add sitemap reference
    $content .= "\n\nSitemap: " . url('/sitemap.xml');
    
    return response($content, 200)
        ->header('Content-Type', 'text/plain');
})->name('robots');

// Admin routes
Route::prefix('hyro/plugins/seo-optimizer')
    ->middleware(['web'])
    ->name('hyro.plugin.seo-optimizer.')
    ->group(function () {
        Route::get('/', function () {
            return view('hyro-plugin-seo-optimizer::index');
        })->name('index');
        
        Route::get('/settings', function () {
            return view('hyro-plugin-seo-optimizer::settings');
        })->name('settings');
        
        Route::get('/redirects', function () {
            return view('hyro-plugin-seo-optimizer::redirects');
        })->name('redirects');
    });

