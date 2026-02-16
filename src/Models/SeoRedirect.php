<?php

namespace HyroPlugins\SeoOptimizer\Models;

use Illuminate\Database\Eloquent\Model;

class SeoRedirect extends Model
{
    protected $table = 'seo_redirects';
    
    protected $fillable = [
        'old_url',
        'new_url',
        'status_code',
        'is_active',
        'hits',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'hits' => 'integer',
        'status_code' => 'integer',
    ];
    
    /**
     * Increment hit counter
     */
    public function incrementHits(): void
    {
        $this->increment('hits');
    }
    
    /**
     * Scope for active redirects
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
