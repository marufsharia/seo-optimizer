<?php

namespace HyroPlugins\SeoOptimizer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';
    
    protected $fillable = [
        'model_type',
        'model_id',
        'title',
        'description',
        'keywords',
        'og_image',
        'canonical_url',
        'robots',
        'schema_type',
        'schema_data',
    ];
    
    protected $casts = [
        'schema_data' => 'array',
    ];
    
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
    
    /**
     * Get formatted keywords as array
     */
    public function getKeywordsArray(): array
    {
        if (empty($this->keywords)) {
            return [];
        }
        
        return array_map('trim', explode(',', $this->keywords));
    }
    
    /**
     * Set keywords from array
     */
    public function setKeywordsFromArray(array $keywords): void
    {
        $this->keywords = implode(', ', $keywords);
    }
}
