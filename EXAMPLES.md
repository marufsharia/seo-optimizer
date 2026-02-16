# SEO Optimizer - Usage Examples

## Basic Setup

### 1. Add HasSeo Trait to Your Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HyroPlugins\SeoOptimizer\Traits\HasSeo;

class Post extends Model
{
    use HasSeo;
    
    protected $fillable = ['title', 'content', 'slug', 'featured_image'];
}
```

### 2. Use in Your Blade Layout

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- SEO Meta Tags --}}
    {!! seo($post)->render() !!}
    
    {{-- Structured Data --}}
    {!! schema($post)->render() !!}
</head>
<body>
    @yield('content')
</body>
</html>
```

## Advanced Examples

### Custom SEO for a Blog Post

```php
$post = Post::create([
    'title' => 'How to Optimize Your Laravel Application',
    'content' => '...',
    'slug' => 'optimize-laravel-application',
    'featured_image' => '/images/laravel-optimization.jpg',
]);

// Add custom SEO
$post->updateSeo([
    'title' => 'Laravel Optimization Guide 2024 - Complete Tutorial',
    'description' => 'Learn how to optimize your Laravel application for maximum performance. Includes caching, database optimization, and more.',
    'keywords' => 'laravel, optimization, performance, caching, database',
    'og_image' => '/images/og/laravel-optimization.jpg',
    'canonical_url' => 'https://example.com/blog/optimize-laravel-application',
    'robots' => 'index,follow',
    'schema_type' => 'Article',
]);
```

### E-commerce Product SEO

```php
use App\Models\Product;

$product = Product::create([
    'name' => 'Premium Wireless Headphones',
    'description' => 'High-quality wireless headphones with noise cancellation',
    'price' => 199.99,
    'image' => '/products/headphones.jpg',
]);

$product->updateSeo([
    'title' => 'Premium Wireless Headphones - Noise Cancelling | YourStore',
    'description' => 'Shop Premium Wireless Headphones with active noise cancellation, 30-hour battery life, and premium sound quality. Free shipping available.',
    'keywords' => 'wireless headphones, noise cancelling, premium audio, bluetooth headphones',
    'og_image' => '/products/headphones-og.jpg',
    'schema_type' => 'Product',
    'schema_data' => [
        'price' => 199.99,
        'currency' => 'USD',
        'availability' => 'InStock',
        'brand' => 'YourBrand',
    ],
]);
```

### Dynamic Sitemap with Multiple Models

```php
use HyroPlugins\SeoOptimizer\Services\SitemapService;
use App\Models\Post;
use App\Models\Product;
use App\Models\Page;

$sitemap = app(SitemapService::class);

// Add homepage
$sitemap->addUrl(url('/'), [
    'changefreq' => 'daily',
    'priority' => '1.0',
]);

// Add blog posts
$sitemap->addModel(Post::class, function ($post) {
    return route('blog.show', $post->slug);
});

// Add products
$sitemap->addModel(Product::class, function ($product) {
    return route('products.show', $product->slug);
});

// Add static pages
$sitemap->addModel(Page::class, function ($page) {
    return route('pages.show', $page->slug);
});

// Generate and cache
$xml = $sitemap->generate();
```

### Custom Structured Data

```php
use HyroPlugins\SeoOptimizer\Services\StructuredDataService;

$schema = app(StructuredDataService::class);

// Organization schema
$schema->generateOrganizationSchema();

// Breadcrumb schema
$schema->generateBreadcrumbSchema([
    ['name' => 'Home', 'url' => url('/')],
    ['name' => 'Blog', 'url' => url('/blog')],
    ['name' => $post->title, 'url' => route('blog.show', $post->slug)],
]);

// Render all schemas
echo $schema->render();
```

### Managing Redirects Programmatically

```php
use HyroPlugins\SeoOptimizer\Models\SeoRedirect;

// Create a 301 redirect
SeoRedirect::create([
    'old_url' => '/old-blog-post',
    'new_url' => '/blog/new-post-url',
    'status_code' => 301,
    'is_active' => true,
]);

// Create a temporary 302 redirect
SeoRedirect::create([
    'old_url' => '/temporary-page',
    'new_url' => '/new-location',
    'status_code' => 302,
    'is_active' => true,
]);

// Get all active redirects
$redirects = SeoRedirect::active()->get();

// Get redirect statistics
$topRedirects = SeoRedirect::orderBy('hits', 'desc')->take(10)->get();
```

### Custom Meta Tags in Controller

```php
namespace App\Http\Controllers;

use App\Models\Post;
use HyroPlugins\SeoOptimizer\Services\MetaService;

class BlogController extends Controller
{
    public function show($slug, MetaService $seo)
    {
        $post = Post::where('slug', $slug)->withSeo()->firstOrFail();
        
        // Generate SEO meta
        $meta = $seo->forModel($post)->generate();
        
        return view('blog.show', compact('post', 'meta'));
    }
}
```

### Conditional SEO Based on User Role

```php
$post = Post::find(1);

if (auth()->user()->isAdmin()) {
    // Admins can see draft posts
    $post->updateSeo([
        'robots' => 'noindex,nofollow',
    ]);
} else {
    // Public posts
    $post->updateSeo([
        'robots' => 'index,follow',
    ]);
}
```

### Bulk SEO Updates

```php
use App\Models\Post;

// Update SEO for all posts without custom SEO
Post::whereDoesntHave('seo')->chunk(100, function ($posts) {
    foreach ($posts as $post) {
        $post->updateSeo([
            'title' => $post->title . ' | My Blog',
            'description' => str_limit(strip_tags($post->content), 160),
            'keywords' => implode(', ', $post->tags->pluck('name')->toArray()),
        ]);
    }
});
```

### API Response with SEO Data

```php
namespace App\Http\Controllers\Api;

use App\Models\Post;

class PostController extends Controller
{
    public function show($id)
    {
        $post = Post::with('seo')->findOrFail($id);
        
        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'seo' => [
                'title' => $post->getSeoTitle(),
                'description' => $post->getSeoDescription(),
                'keywords' => $post->getSeoKeywords(),
                'og_image' => $post->getSeoImage(),
                'canonical_url' => $post->getCanonicalUrl(),
            ],
        ]);
    }
}
```

### Event-Based SEO Updates

```php
namespace App\Observers;

use App\Models\Post;

class PostObserver
{
    public function created(Post $post)
    {
        // Auto-generate SEO when post is created
        $post->updateSeo([
            'title' => $post->title,
            'description' => str_limit(strip_tags($post->content), 160),
            'schema_type' => 'Article',
        ]);
    }
    
    public function updated(Post $post)
    {
        // Update SEO when post is updated
        if ($post->isDirty('title') || $post->isDirty('content')) {
            $post->updateSeo([
                'title' => $post->title,
                'description' => str_limit(strip_tags($post->content), 160),
            ]);
        }
    }
}
```

### Testing SEO

```php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use HyroPlugins\SeoOptimizer\Traits\HasSeo;

class SeoTest extends TestCase
{
    public function test_post_has_seo_meta()
    {
        $post = Post::factory()->create();
        
        $post->updateSeo([
            'title' => 'Test SEO Title',
            'description' => 'Test description',
        ]);
        
        $this->assertEquals('Test SEO Title', $post->getSeoTitle());
        $this->assertEquals('Test description', $post->getSeoDescription());
    }
    
    public function test_sitemap_is_accessible()
    {
        $response = $this->get('/sitemap.xml');
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
    }
    
    public function test_redirect_works()
    {
        SeoRedirect::create([
            'old_url' => '/old-page',
            'new_url' => '/new-page',
            'status_code' => 301,
            'is_active' => true,
        ]);
        
        $response = $this->get('/old-page');
        
        $response->assertRedirect('/new-page');
        $response->assertStatus(301);
    }
}
```

## Tips and Best Practices

1. **Always use descriptive titles** - Keep them under 60 characters
2. **Write compelling descriptions** - 150-160 characters is optimal
3. **Use relevant keywords** - But don't keyword stuff
4. **Optimize images** - Use descriptive filenames and alt text
5. **Set canonical URLs** - Prevent duplicate content issues
6. **Use structured data** - Helps search engines understand your content
7. **Monitor redirects** - Check hit counts and remove unused redirects
8. **Generate sitemaps regularly** - Keep search engines updated
9. **Test your SEO** - Use tools like Google Search Console
10. **Keep robots.txt updated** - Control what search engines can crawl
