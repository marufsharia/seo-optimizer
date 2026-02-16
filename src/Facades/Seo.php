<?php

namespace HyroPlugins\SeoOptimizer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \HyroPlugins\SeoOptimizer\Services\MetaService forModel(\Illuminate\Database\Eloquent\Model $model)
 * @method static array generate()
 * @method static string render()
 * 
 * @see \HyroPlugins\SeoOptimizer\Services\MetaService
 */
class Seo extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'seo.meta';
    }
}
