<?php

namespace HyroPlugins\SeoOptimizer\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MetaService
{
    protected ?Model $model = null;
    protected array $meta = [];
    
    /**
     * Set the model for SEO generation
     */
    public function forModel(Model $model): self
    {
        $this->model = $model;
        return $this;
    }
    
    /**
     * Generate meta tags
     */
    public function generate(): array
    {
        if (!$this->model) {
            return $this->getDefaultMeta();
        }
        
        $this->meta = [
            'title' => $this->generateTitle(),
            'description' => $this->generateDescription(),
            'keywords' => $this->generateKeywords(),
            'canonical' => $this->generateCanonical(),
            'robots' => $this->generateRobots(),
            'og' => $this->generateOpenGraph(),
            'twitter' => $this->generateTwitterCard(),
        ];
        
        return $this->meta;
    }
    
    /**
     * Get a setting value
     */
    protected function getSetting($key, $default = null)
    {
        $setting = DB::table('seo_settings')->where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
    
    /**
     * Generate title
     */
    protected function generateTitle(): string
    {
        $title = '';
        
        // Priority 1: SEO meta title
        if (method_exists($this->model, 'getSeoTitle')) {
            $title = $this->model->getSeoTitle();
        }
        
        // Priority 2: Model title/name
        if (empty($title)) {
            $title = $this->model->title ?? $this->model->name ?? '';
        }
        
        // Apply template
        $template = $this->getSetting('title_template', '{title} | {site}');
        $siteName = $this->getSetting('site_name', config('app.name'));
        
        return str_replace(
            ['{title}', '{site}'],
            [$title, $siteName],
            $template
        );
    }
    
    /**
     * Generate description
     */
    protected function generateDescription(): string
    {
        if (method_exists($this->model, 'getSeoDescription')) {
            $description = $this->model->getSeoDescription();
            if (!empty($description)) {
                return $description;
            }
        }
        
        return $this->getSetting('default_description', '');
    }
    
    /**
     * Generate keywords
     */
    protected function generateKeywords(): array
    {
        if (method_exists($this->model, 'getSeoKeywords')) {
            return $this->model->getSeoKeywords();
        }
        
        return [];
    }
    
    /**
     * Generate canonical URL
     */
    protected function generateCanonical(): ?string
    {
        if (method_exists($this->model, 'getCanonicalUrl')) {
            return $this->model->getCanonicalUrl();
        }
        
        return url()->current();
    }
    
    /**
     * Generate robots directive
     */
    protected function generateRobots(): string
    {
        if ($this->model->seo ?? null) {
            return $this->model->seo->robots ?? 'index,follow';
        }
        
        return 'index,follow';
    }
    
    /**
     * Generate Open Graph tags
     */
    protected function generateOpenGraph(): array
    {
        $image = null;
        
        if (method_exists($this->model, 'getSeoImage')) {
            $image = $this->model->getSeoImage();
        }
        
        if (empty($image)) {
            $image = $this->getSetting('default_og_image');
        }
        
        return [
            'og:title' => $this->generateTitle(),
            'og:description' => $this->generateDescription(),
            'og:image' => $image ? url($image) : null,
            'og:url' => $this->generateCanonical(),
            'og:type' => 'website',
            'og:site_name' => $this->getSetting('site_name', config('app.name')),
        ];
    }
    
    /**
     * Generate Twitter Card tags
     */
    protected function generateTwitterCard(): array
    {
        $image = null;
        
        if (method_exists($this->model, 'getSeoImage')) {
            $image = $this->model->getSeoImage();
        }
        
        if (empty($image)) {
            $image = $this->getSetting('default_og_image');
        }
        
        $card = [
            'twitter:card' => $image ? 'summary_large_image' : 'summary',
            'twitter:title' => $this->generateTitle(),
            'twitter:description' => $this->generateDescription(),
        ];
        
        if ($image) {
            $card['twitter:image'] = url($image);
        }
        
        $twitterHandle = $this->getSetting('twitter_handle');
        if ($twitterHandle) {
            $card['twitter:site'] = '@' . ltrim($twitterHandle, '@');
        }
        
        return $card;
    }
    
    /**
     * Get default meta when no model is set
     */
    protected function getDefaultMeta(): array
    {
        $siteName = $this->getSetting('site_name', config('app.name'));
        $defaultDescription = $this->getSetting('default_description', '');
        $defaultImage = $this->getSetting('default_og_image');
        
        return [
            'title' => $siteName,
            'description' => $defaultDescription,
            'keywords' => [],
            'canonical' => url()->current(),
            'robots' => 'index,follow',
            'og' => [
                'og:title' => $siteName,
                'og:description' => $defaultDescription,
                'og:image' => $defaultImage ? url($defaultImage) : null,
                'og:url' => url()->current(),
                'og:type' => 'website',
                'og:site_name' => $siteName,
            ],
            'twitter' => [
                'twitter:card' => 'summary',
                'twitter:title' => $siteName,
                'twitter:description' => $defaultDescription,
            ],
        ];
    }
    
    /**
     * Render meta tags as HTML
     */
    public function render(): string
    {
        if (empty($this->meta)) {
            $this->generate();
        }
        
        $html = [];
        
        // Title
        $html[] = '<title>' . e($this->meta['title']) . '</title>';
        
        // Description
        if (!empty($this->meta['description'])) {
            $html[] = '<meta name="description" content="' . e($this->meta['description']) . '">';
        }
        
        // Keywords
        if (!empty($this->meta['keywords'])) {
            $html[] = '<meta name="keywords" content="' . e(implode(', ', $this->meta['keywords'])) . '">';
        }
        
        // Canonical
        if (!empty($this->meta['canonical'])) {
            $html[] = '<link rel="canonical" href="' . e($this->meta['canonical']) . '">';
        }
        
        // Robots
        $html[] = '<meta name="robots" content="' . e($this->meta['robots']) . '">';
        
        // Open Graph
        foreach ($this->meta['og'] as $property => $content) {
            if (!empty($content)) {
                $html[] = '<meta property="' . e($property) . '" content="' . e($content) . '">';
            }
        }
        
        // Twitter Card
        foreach ($this->meta['twitter'] as $name => $content) {
            if (!empty($content)) {
                $html[] = '<meta name="' . e($name) . '" content="' . e($content) . '">';
            }
        }
        
        // Facebook App ID
        $fbAppId = $this->getSetting('facebook_app_id');
        if ($fbAppId) {
            $html[] = '<meta property="fb:app_id" content="' . e($fbAppId) . '">';
        }
        
        return implode("\n    ", $html);
    }
}
