<?php

namespace HyroPlugins\SeoOptimizer\Services;

use HyroPlugins\SeoOptimizer\Models\SeoSetting;
use Illuminate\Database\Eloquent\Model;

class StructuredDataService
{
    protected array $schemas = [];
    
    /**
     * Add schema for model
     */
    public function forModel(Model $model): self
    {
        $schemaType = $this->detectSchemaType($model);
        
        if ($schemaType) {
            $method = 'generate' . $schemaType . 'Schema';
            if (method_exists($this, $method)) {
                $this->schemas[] = $this->$method($model);
            }
        }
        
        return $this;
    }
    
    /**
     * Detect schema type from model
     */
    protected function detectSchemaType(Model $model): ?string
    {
        // Check if model has SEO meta with schema type
        if ($model->seo ?? null) {
            $schemaType = $model->seo->schema_type;
            if ($schemaType) {
                return $schemaType;
            }
        }
        
        // Auto-detect from model class name
        $className = class_basename($model);
        
        $typeMap = [
            'Post' => 'Article',
            'Article' => 'Article',
            'Product' => 'Product',
            'Page' => 'WebPage',
        ];
        
        return $typeMap[$className] ?? null;
    }
    
    /**
     * Generate Article schema
     */
    protected function generateArticleSchema(Model $model): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $model->title ?? $model->name ?? '',
            'datePublished' => $model->created_at?->toIso8601String(),
            'dateModified' => $model->updated_at?->toIso8601String(),
        ];
        
        if (method_exists($model, 'getSeoDescription')) {
            $schema['description'] = $model->getSeoDescription();
        }
        
        if (method_exists($model, 'getSeoImage')) {
            $image = $model->getSeoImage();
            if ($image) {
                $schema['image'] = url($image);
            }
        }
        
        // Author
        if (isset($model->author)) {
            $schema['author'] = [
                '@type' => 'Person',
                'name' => $model->author->name ?? 'Unknown',
            ];
        }
        
        // Publisher
        $schema['publisher'] = [
            '@type' => 'Organization',
            'name' => SeoSetting::get('site_name', config('app.name')),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => url(SeoSetting::get('default_og_image', '')),
            ],
        ];
        
        return $schema;
    }
    
    /**
     * Generate Product schema
     */
    protected function generateProductSchema(Model $model): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $model->name ?? $model->title ?? '',
        ];
        
        if (method_exists($model, 'getSeoDescription')) {
            $schema['description'] = $model->getSeoDescription();
        }
        
        if (method_exists($model, 'getSeoImage')) {
            $image = $model->getSeoImage();
            if ($image) {
                $schema['image'] = url($image);
            }
        }
        
        // Price
        if (isset($model->price)) {
            $schema['offers'] = [
                '@type' => 'Offer',
                'price' => $model->price,
                'priceCurrency' => 'USD',
                'availability' => 'https://schema.org/InStock',
            ];
        }
        
        // Rating
        if (isset($model->rating)) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $model->rating,
                'reviewCount' => $model->reviews_count ?? 0,
            ];
        }
        
        return $schema;
    }
    
    /**
     * Generate WebPage schema
     */
    protected function generateWebPageSchema(Model $model): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $model->title ?? $model->name ?? '',
            'description' => method_exists($model, 'getSeoDescription') ? $model->getSeoDescription() : '',
            'url' => method_exists($model, 'getCanonicalUrl') ? $model->getCanonicalUrl() : url()->current(),
        ];
    }
    
    /**
     * Generate Organization schema
     */
    public function generateOrganizationSchema(): self
    {
        $this->schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => SeoSetting::get('site_name', config('app.name')),
            'url' => url('/'),
            'logo' => url(SeoSetting::get('default_og_image', '')),
        ];
        
        return $this;
    }
    
    /**
     * Generate Breadcrumb schema
     */
    public function generateBreadcrumbSchema(array $items): self
    {
        $listItems = [];
        
        foreach ($items as $position => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $position + 1,
                'name' => $item['name'],
                'item' => $item['url'] ?? null,
            ];
        }
        
        $this->schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ];
        
        return $this;
    }
    
    /**
     * Render schemas as JSON-LD
     */
    public function render(): string
    {
        if (empty($this->schemas)) {
            return '';
        }
        
        $html = [];
        
        foreach ($this->schemas as $schema) {
            $html[] = '<script type="application/ld+json">' . "\n";
            $html[] = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $html[] = "\n" . '</script>';
        }
        
        return implode("\n", $html);
    }
    
    /**
     * Get schemas as array
     */
    public function getSchemas(): array
    {
        return $this->schemas;
    }
}
