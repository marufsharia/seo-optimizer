# SEO Optimizer Plugin for Hyro

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%5E8.2-blue)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-red)](https://laravel.com/)

A production-ready, comprehensive SEO optimization plugin for the Hyro framework. Manage meta tags, Open Graph, Twitter Cards, structured data, sitemaps, redirects, and more - all from an intuitive admin interface.

## 🚀 Features

### Core SEO Features
- **Meta Tags Management** - Title, description, keywords, canonical URLs
- **Open Graph Tags** - Full Facebook/LinkedIn social sharing optimization
- **Twitter Cards** - Rich Twitter sharing with image support
- **Structured Data (JSON-LD)** - Schema.org markup for better search visibility
- **Sitemap Generator** - Automatic XML sitemap generation with caching
- **Robots.txt Manager** - Control search engine crawling behavior
- **Redirect Manager** - 301/302 redirects with hit tracking
- **HasSeo Trait** - Easy integration with any Eloquent model

### Admin Interface
- **Beautiful Dashboard** - Overview of all SEO metrics
- **Settings Management** - Configure global SEO settings
- **Redirect Manager** - CRUD interface for URL redirects
- **Real-time Preview** - See how your content appears in search results
- **Dark Mode Support** - Fully compatible with light and dark themes

### Developer Features
- **Helper Functions** - `seo()` and `schema()` for easy integration
- **Service Classes** - Clean, testable architecture
- **Middleware** - Automatic redirect handling
- **Database Driven** - All settings stored in database
- **Cache Support** - Optimized performance with Laravel cache

## 📋 Requirements

- PHP 8.2 or higher
- Laravel 11.x or higher
- Hyro Framework 1.0 or higher
- MySQL 5.7+ / PostgreSQL 9.6+ / SQLite 3.8+

## 📦 Installation

### Step 1: Install via Composer

```bash
composer require hyro-plugins/seo-optimizer
```

### Step 2: Install the Plugin

Navigate to your Hyro admin panel:

1. Go to **Plugins** → **Available Plugins**
2. Find **SEO Optimizer**
3. Click **Install**
4. Click **Activate**

The plugin will automatically:
- Run database migrations
- Register routes
- Set up the admin menu
- Load all necessary assets

### Manual Installation (Alternative)

If you prefer manual installation:

```bash
# Clone the repository into your hyro-plugins directory
cd hyro-plugins
git clone https://github.com/marufsharia/seo-optimizer.git SeoOptimizer

# Run migrations
php artisan migrate --path=hyro-plugins/SeoOptimizer/database/migrations

# Clear cache
php artisan cache:clear
php artisan config:clear
```

## 🎯 Quick Start

### 1. Add SEO to Your Models

Add the `HasSeo` trait to any model you want to optimize:

```php
use HyroPlugins\SeoOptimizer\Traits\HasSeo;

class Article extends Model
{
    use HasSeo;
    
    // Your model code...
}
```

### 2. Set SEO Data

```php
$article = Article::find(1);

$article->updateSeo([
    'title' => 'Complete Guide to Laravel SEO',
    'description' => 'Learn how to optimize your Laravel application for search engines',
    'keywords' => 'laravel, seo, optimization, tutorial',
    'og_image' => '/images/articles/laravel-seo.jpg',
    'canonical_url' => 'https://example.com/articles/laravel-seo-guide',
]);
```

### 3. Render SEO Tags in Your Layout

In your Blade layout file (typically `resources/views/layouts/app.blade.php`):

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- SEO Meta Tags --}}
    {!! seo($model ?? null)->render() !!}
    
    {{-- Your other head tags --}}
</head>
<body>
    @yield('content')
</body>
</html>
```

### 4. Add Structured Data (Optional)

```blade
<head>
    {!! seo($article)->render() !!}
    
    {{-- Add structured data --}}
    {!! schema($article)->article()->render() !!}
</head>
```

## 📖 Documentation

### Configuration

Access the SEO settings from your admin panel:

**Admin Panel** → **SEO Optimizer** → **Settings**

#### General Settings
- **Site Name** - Your website name
- **Title Template** - Template for page titles (use `{title}` and `{site}`)
- **Default Description** - Fallback meta description
- **Default OG Image** - Default image for social sharing

#### Social Media Settings
- **Twitter Handle** - Your Twitter username (e.g., @yourhandle)
- **Facebook App ID** - Your Facebook App ID for analytics

#### Sitemap Settings
- **Enable/Disable Sitemap** - Toggle sitemap generation
- **Generate Sitemap** - Manually regenerate sitemap
- **Ping Search Engines** - Notify Google and Bing of updates

#### Robots.txt
- **Custom Robots.txt** - Define crawling rules for search engines

### Using the HasSeo Trait

The `HasSeo` trait provides several helpful methods:

```php
// Get SEO meta relationship
$article->seo; // Returns SeoMeta model

// Get or create SEO meta
$seoMeta = $article->getSeoMeta();

// Update SEO data
$article->updateSeo([
    'title' => 'My Article Title',
    'description' => 'Article description',
    'keywords' => 'keyword1, keyword2, keyword3',
    'og_image' => '/path/to/image.jpg',
    'canonical_url' => 'https://example.com/article',
    'robots' => 'index,follow',
]);

// Get SEO title with fallback
$title = $article->getSeoTitle();

// Get SEO description with fallback
$description = $article->getSeoDescription();

// Get SEO keywords as array
$keywords = $article->getSeoKeywords();

// Get OG image with fallback
$image = $article->getSeoImage();

// Get canonical URL
$canonical = $article->getCanonicalUrl();

// Eager load SEO data
$articles = Article::withSeo()->get();
```

### Helper Functions

#### seo() Helper

Generate and render SEO meta tags:

```php
// With a model
{!! seo($article)->render() !!}

// Without a model (uses default settings)
{!! seo()->render() !!}

// Generate meta array
$meta = seo($article)->generate();
```

#### schema() Helper

Generate structured data (JSON-LD):

```php
// Article schema
{!! schema($article)->article()->render() !!}

// Product schema
{!! schema($product)->product()->render() !!}

// Organization schema
{!! schema()->organization([
    'name' => 'My Company',
    'url' => 'https://example.com',
    'logo' => 'https://example.com/logo.png',
])->render() !!}

// Breadcrumb schema
{!! schema()->breadcrumb([
    ['name' => 'Home', 'url' => '/'],
    ['name' => 'Blog', 'url' => '/blog'],
    ['name' => 'Article', 'url' => '/blog/article'],
])->render() !!}
```

### Redirect Management

#### Via Admin Interface

1. Go to **SEO Optimizer** → **Redirects**
2. Click **Add Redirect**
3. Enter old URL and new URL
4. Choose redirect type (301 or 302)
5. Set active status
6. Save

#### Programmatically

```php
use Illuminate\Support\Facades\DB;

// Create a redirect
DB::table('seo_redirects')->insert([
    'old_url' => '/old-page',
    'new_url' => '/new-page',
    'status_code' => 301,
    'is_active' => true,
    'created_at' => now(),
    'updated_at' => now(),
]);

// Get active redirects
$redirects = DB::table('seo_redirects')
    ->where('is_active', true)
    ->get();
```

### Sitemap Generation

#### Automatic Generation

The sitemap is automatically generated and cached. Access it at:

```
https://yourdomain.com/sitemap.xml
```

#### Manual Generation

```php
use HyroPlugins\SeoOptimizer\Services\SitemapService;

$sitemap = app(SitemapService::class);

// Add URLs
$sitemap->addUrl('https://example.com/', [
    'changefreq' => 'daily',
    'priority' => '1.0',
]);

// Add models
$sitemap->addModel(Article::class, function($article) {
    return route('articles.show', $article);
});

// Generate XML
$xml = $sitemap->generate();

// Clear cache
$sitemap->clearCache();

// Ping search engines
$results = $sitemap->pingSearchEngines();
```

## 🎨 Customization

### Custom Meta Service

Extend the MetaService to customize SEO generation:

```php
namespace App\Services;

use HyroPlugins\SeoOptimizer\Services\MetaService as BaseMetaService;

class CustomMetaService extends BaseMetaService
{
    protected function generateTitle(): string
    {
        // Your custom title logic
        return parent::generateTitle();
    }
}
```

Register your custom service in a service provider:

```php
$this->app->singleton('seo.meta', function ($app) {
    return new CustomMetaService();
});
```

### Custom Structured Data

Create custom schema types:

```php
use HyroPlugins\SeoOptimizer\Services\StructuredDataService;

$schema = app(StructuredDataService::class);

$schema->addSchema('Event', [
    '@context' => 'https://schema.org',
    '@type' => 'Event',
    'name' => 'My Event',
    'startDate' => '2024-03-01T19:00',
    'location' => [
        '@type' => 'Place',
        'name' => 'Event Venue',
        'address' => '123 Main St',
    ],
]);

echo $schema->render();
```

## 🔧 Advanced Usage

### Middleware

The plugin automatically registers a middleware that handles redirects. To customize:

```php
// In your route middleware
Route::middleware(['web', 'seo.redirect'])->group(function () {
    // Your routes
});
```

### Database Queries

Direct database access for advanced use cases:

```php
use Illuminate\Support\Facades\DB;

// Get all SEO meta records
$seoRecords = DB::table('seo_meta')->get();

// Get SEO settings
$siteName = DB::table('seo_settings')
    ->where('key', 'site_name')
    ->value('value');

// Update settings
DB::table('seo_settings')->updateOrInsert(
    ['key' => 'site_name'],
    ['value' => 'My Site', 'updated_at' => now()]
);
```

## 🐛 Troubleshooting

### Sitemap Not Generating

1. Clear cache: `php artisan cache:clear`
2. Check permissions on storage directory
3. Verify database tables exist: `php artisan migrate:status`

### Redirects Not Working

1. Ensure middleware is registered
2. Check redirect is active in database
3. Clear route cache: `php artisan route:clear`

### Meta Tags Not Showing

1. Verify `{!! seo()->render() !!}` is in your layout
2. Check model has `HasSeo` trait
3. Ensure SEO data is saved in database

### Ping Search Engines Fails

This is normal on localhost. The ping feature only works on production sites with public domains.

## 🤝 Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Setup

```bash
# Clone the repository
git clone https://github.com/marufsharia/seo-optimizer.git

# Install dependencies
composer install

# Run tests (when available)
composer test
```

## 📝 License

This plugin is open-sourced software licensed under the [MIT license](LICENSE).

## 🙏 Credits

- **Author**: Maruf Sharia
- **Framework**: [Hyro](https://github.com/marufsharia/hyro)
- **Inspired by**: Laravel best practices and modern SEO standards

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/marufsharia/seo-optimizer/issues)
- **Documentation**: [Full Documentation](https://github.com/marufsharia/seo-optimizer/wiki)
- **Email**: support@hyro.dev

## 🗺️ Roadmap

- [ ] Google Analytics integration
- [ ] Schema.org markup builder UI
- [ ] SEO audit tool
- [ ] Keyword tracking
- [ ] Competitor analysis
- [ ] Automated SEO recommendations
- [ ] Multi-language support
- [ ] REST API endpoints

## ⭐ Show Your Support

If you find this plugin helpful, please give it a star on GitHub!

---

Made with ❤️ for the Hyro community
