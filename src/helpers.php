<?php

use HyroPlugins\SeoOptimizer\Services\MetaService;
use HyroPlugins\SeoOptimizer\Services\StructuredDataService;

if (!function_exists('seo')) {
    /**
     * Get SEO meta service instance
     */
    function seo(?object $model = null): MetaService
    {
        $service = app('seo.meta');
        
        if ($model) {
            $service->forModel($model);
        }
        
        return $service;
    }
}

if (!function_exists('schema')) {
    /**
     * Get structured data service instance
     */
    function schema(?object $model = null): StructuredDataService
    {
        $service = app('seo.schema');
        
        if ($model) {
            $service->forModel($model);
        }
        
        return $service;
    }
}
