<?php

namespace HyroPlugins\SeoOptimizer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SeoSetting extends Model
{
    protected $table = 'seo_settings';
    
    protected $fillable = ['key', 'value'];
    
    /**
     * Get setting value by key
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("seo_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }
    
    /**
     * Set setting value
     */
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        
        Cache::forget("seo_setting_{$key}");
    }
    
    /**
     * Get all settings as array
     */
    public static function getAll(): array
    {
        return Cache::remember('seo_settings_all', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }
    
    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }
}
