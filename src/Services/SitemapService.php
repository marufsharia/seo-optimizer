<?php

namespace HyroPlugins\SeoOptimizer\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class SitemapService
{
    protected array $urls = [];
    protected array $models = [];
    
    /**
     * Add URL to sitemap
     */
    public function addUrl(string $url, array $options = []): self
    {
        $this->urls[] = array_merge([
            'loc' => $url,
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.5',
        ], $options);
        
        return $this;
    }
    
    /**
     * Add model to sitemap
     */
    public function addModel(string $modelClass, callable $urlGenerator = null): self
    {
        $this->models[] = [
            'class' => $modelClass,
            'url_generator' => $urlGenerator,
        ];
        
        return $this;
    }
    
    /**
     * Generate sitemap XML
     */
    public function generate(): string
    {
        return Cache::remember('sitemap_xml', 3600, function () {
            $this->collectUrls();
            return $this->buildXml();
        });
    }
    
    /**
     * Collect URLs from models
     */
    protected function collectUrls(): void
    {
        foreach ($this->models as $modelConfig) {
            $modelClass = $modelConfig['class'];
            
            if (!class_exists($modelClass)) {
                continue;
            }
            
            $query = $modelClass::query();
            
            // Add published scope if exists
            if (method_exists($modelClass, 'scopePublished')) {
                $query->published();
            }
            
            $models = $query->get();
            
            foreach ($models as $model) {
                $url = null;
                
                // Use custom URL generator if provided
                if ($modelConfig['url_generator']) {
                    $url = call_user_func($modelConfig['url_generator'], $model);
                }
                // Try getUrl method
                elseif (method_exists($model, 'getUrl')) {
                    $url = $model->getUrl();
                }
                // Try url attribute
                elseif (isset($model->url)) {
                    $url = $model->url;
                }
                
                if ($url) {
                    $this->addUrl($url, [
                        'lastmod' => $model->updated_at?->toAtomString() ?? now()->toAtomString(),
                        'changefreq' => $this->getChangeFreq($model),
                        'priority' => $this->getPriority($model),
                    ]);
                }
            }
        }
    }
    
    /**
     * Get change frequency for model
     */
    protected function getChangeFreq($model): string
    {
        // You can customize this based on model type
        return 'weekly';
    }
    
    /**
     * Get priority for model
     */
    protected function getPriority($model): string
    {
        // You can customize this based on model type
        return '0.7';
    }
    
    /**
     * Build XML sitemap
     */
    protected function buildXml(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($this->urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($url['loc']) . "</loc>\n";
            $xml .= "    <lastmod>" . $url['lastmod'] . "</lastmod>\n";
            $xml .= "    <changefreq>" . $url['changefreq'] . "</changefreq>\n";
            $xml .= "    <priority>" . $url['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }
        
        $xml .= '</urlset>';
        
        return $xml;
    }
    
    /**
     * Clear sitemap cache
     */
    public function clearCache(): void
    {
        Cache::forget('sitemap_xml');
    }
    
    /**
     * Ping search engines
     */
    public function pingSearchEngines(): array
    {
        $sitemapUrl = url('/sitemap.xml');
        $results = [];
        
        $searchEngines = [
            'google' => "https://www.google.com/ping?sitemap=" . urlencode($sitemapUrl),
            'bing' => "https://www.bing.com/ping?sitemap=" . urlencode($sitemapUrl),
        ];
        
        foreach ($searchEngines as $engine => $pingUrl) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(10)->get($pingUrl);
                $results[$engine] = $response->successful();
            } catch (\Exception $e) {
                $results[$engine] = false;
            }
        }
        
        return $results;
    }
}
