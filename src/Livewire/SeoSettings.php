<?php

namespace HyroPlugins\SeoOptimizer\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class SeoSettings extends Component
{
    public $activeTab = 'general';
    
    // General Settings
    public $site_name;
    public $title_template;
    public $default_description;
    public $default_og_image;
    
    // Social Settings
    public $twitter_handle;
    public $facebook_app_id;
    
    // Sitemap Settings
    public $sitemap_enabled;
    
    // Robots Settings
    public $robots_content;
    
    public function mount()
    {
        $this->loadSettings();
    }
    
    protected function loadSettings()
    {
        $this->site_name = $this->getSetting('site_name', config('app.name'));
        $this->title_template = $this->getSetting('title_template', '{title} | {site}');
        $this->default_description = $this->getSetting('default_description', '');
        $this->default_og_image = $this->getSetting('default_og_image', '');
        $this->twitter_handle = $this->getSetting('twitter_handle', '');
        $this->facebook_app_id = $this->getSetting('facebook_app_id', '');
        $this->sitemap_enabled = $this->getSetting('sitemap_enabled', '1');
        $this->robots_content = $this->getSetting('robots_content', "User-agent: *\nDisallow:");
    }
    
    protected function getSetting($key, $default = null)
    {
        $setting = DB::table('seo_settings')->where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
    
    protected function setSetting($key, $value)
    {
        DB::table('seo_settings')->updateOrInsert(
            ['key' => $key],
            ['value' => $value, 'updated_at' => now()]
        );
    }
    
    public function saveGeneral()
    {
        $this->validate([
            'site_name' => 'required|string|max:255',
            'title_template' => 'required|string|max:255',
            'default_description' => 'nullable|string|max:500',
            'default_og_image' => 'nullable|string|max:500',
        ]);
        
        $this->setSetting('site_name', $this->site_name);
        $this->setSetting('title_template', $this->title_template);
        $this->setSetting('default_description', $this->default_description);
        $this->setSetting('default_og_image', $this->default_og_image);
        
        session()->flash('success', 'General settings saved successfully!');
    }
    
    public function saveSocial()
    {
        $this->validate([
            'twitter_handle' => 'nullable|string|max:255',
            'facebook_app_id' => 'nullable|string|max:255',
        ]);
        
        $this->setSetting('twitter_handle', $this->twitter_handle);
        $this->setSetting('facebook_app_id', $this->facebook_app_id);
        
        session()->flash('success', 'Social settings saved successfully!');
    }
    
    public function saveSitemap()
    {
        $this->setSetting('sitemap_enabled', $this->sitemap_enabled);
        
        session()->flash('success', 'Sitemap settings saved successfully!');
    }
    
    public function saveRobots()
    {
        $this->validate([
            'robots_content' => 'required|string',
        ]);
        
        $this->setSetting('robots_content', $this->robots_content);
        
        session()->flash('success', 'Robots.txt saved successfully!');
    }
    
    public function generateSitemap()
    {
        try {
            // Ensure service is loaded
            if (!class_exists('\HyroPlugins\SeoOptimizer\Services\SitemapService')) {
                require_once __DIR__ . '/../Services/SitemapService.php';
            }
            
            $sitemap = new \HyroPlugins\SeoOptimizer\Services\SitemapService();
            
            // Clear cache first
            \Illuminate\Support\Facades\Cache::forget('sitemap_xml');
            
            // Generate new sitemap
            $xml = $sitemap->generate();
            
            if (!empty($xml)) {
                session()->flash('success', 'Sitemap generated successfully! View it at: ' . url('/sitemap.xml'));
            } else {
                session()->flash('error', 'Sitemap generation returned empty result.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to generate sitemap: ' . $e->getMessage());
        }
    }
    
    public function pingSitemapToSearchEngines()
    {
        try {
            // Check if running on localhost
            $sitemapUrl = url('/sitemap.xml');
            $isLocalhost = str_contains($sitemapUrl, 'localhost') || 
                          str_contains($sitemapUrl, '127.0.0.1') || 
                          str_contains($sitemapUrl, '::1');
            
            if ($isLocalhost) {
                session()->flash('error', 'Cannot ping search engines from localhost. This feature only works when your site is deployed to a public server with a real domain name. Search engines cannot access localhost URLs.');
                return;
            }
            
            // Ensure service is loaded
            if (!class_exists('\HyroPlugins\SeoOptimizer\Services\SitemapService')) {
                require_once __DIR__ . '/../Services/SitemapService.php';
            }
            
            $sitemap = new \HyroPlugins\SeoOptimizer\Services\SitemapService();
            $results = $sitemap->pingSearchEngines();
            
            // Build detailed message
            $successCount = 0;
            $messages = [];
            
            foreach ($results as $engine => $success) {
                if ($success) {
                    $successCount++;
                    $messages[] = ucfirst($engine) . ': ✓ Success';
                } else {
                    $messages[] = ucfirst($engine) . ': ✗ Failed';
                }
            }
            
            if ($successCount > 0) {
                session()->flash('success', 'Sitemap ping results: ' . implode(', ', $messages));
            } else {
                session()->flash('error', 'Failed to ping search engines. ' . implode(', ', $messages) . '. This may be due to network restrictions or the search engines being temporarily unavailable.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to ping search engines: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('hyro-plugin-seo-optimizer::livewire.seo-settings');
    }
}
