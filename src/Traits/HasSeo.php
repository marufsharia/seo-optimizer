<?php

namespace HyroPlugins\SeoOptimizer\Traits;

use HyroPlugins\SeoOptimizer\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    /**
     * Get the SEO meta relationship
     */
    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'model');
    }
    
    /**
     * Get SEO meta or create if not exists
     */
    public function getSeoMeta(): SeoMeta
    {
        return $this->seo ?? $this->seo()->create([]);
    }
    
    /**
     * Update SEO meta
     */
    public function updateSeo(array $data): SeoMeta
    {
        if ($this->seo) {
            $this->seo->update($data);
            return $this->seo;
        }
        
        return $this->seo()->create($data);
    }
    
    /**
     * Get SEO title with fallback
     */
    public function getSeoTitle(): string
    {
        if ($this->seo && $this->seo->title) {
            return $this->seo->title;
        }
        
        // Fallback to model title/name
        return $this->title ?? $this->name ?? '';
    }
    
    /**
     * Get SEO description with fallback
     */
    public function getSeoDescription(): string
    {
        if ($this->seo && $this->seo->description) {
            return $this->seo->description;
        }
        
        // Fallback to model description/excerpt
        if (isset($this->description)) {
            return str_limit(strip_tags($this->description), 160);
        }
        
        if (isset($this->excerpt)) {
            return str_limit(strip_tags($this->excerpt), 160);
        }
        
        return '';
    }
    
    /**
     * Get SEO keywords
     */
    public function getSeoKeywords(): array
    {
        if ($this->seo && $this->seo->keywords) {
            return $this->seo->getKeywordsArray();
        }
        
        return [];
    }
    
    /**
     * Get OG image with fallback
     */
    public function getSeoImage(): ?string
    {
        if ($this->seo && $this->seo->og_image) {
            return $this->seo->og_image;
        }
        
        // Fallback to model image/featured_image
        return $this->image ?? $this->featured_image ?? null;
    }
    
    /**
     * Get canonical URL
     */
    public function getCanonicalUrl(): ?string
    {
        if ($this->seo && $this->seo->canonical_url) {
            return $this->seo->canonical_url;
        }
        
        // Try to generate from route
        if (method_exists($this, 'getUrl')) {
            return $this->getUrl();
        }
        
        return null;
    }
    
    /**
     * Scope to eager load SEO
     */
    public function scopeWithSeo($query)
    {
        return $query->with('seo');
    }
}
