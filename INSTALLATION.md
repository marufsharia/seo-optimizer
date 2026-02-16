# SEO Optimizer - Installation & Setup Guide

## Quick Installation

### Step 1: Install the Plugin

```bash
php artisan hyro:plugin:install seo-optimizer
```

This command will:
- Run database migrations
- Publish assets
- Register the plugin
- Activate the plugin automatically

### Step 2: Verify Installation

Visit your admin panel:
```
http://your-domain.com/hyro/plugins/seo-optimizer
```

You should see the SEO Optimizer dashboard.

## Manual Installation

If you prefer manual installation:

### 1. Place Plugin Files

Ensure the plugin is in:
```
hyro-plugins/SeoOptimizer/
```

### 2. Run Migrations

```bash
php artisan migrate
```

### 3. Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### 4. Dump Autoload

```bash
composer dump-autoload
```

### 5. Activate Plugin

```bash
php artisan hyro:plugin:activate seo-optimizer
```

## Post-Installation Setup

### 1. Configure General Settings

1. Navigate to: Admin → SEO Optimizer → Settings
2. Configure:
   - Site Name
   - Title Template (e.g., `{title} | {site}`)
   - Default Description
   - Default OG Image

### 2. Configure Social Media

1. Go to Settings → Social Media tab
2. Add:
   - Twitter Handle (e.g., `@yourhandle`)
   - Facebook App ID (optional)

### 3. Configure Sitemap

1. Go to Settings → Sitemap tab
2. Enable sitemap
3. Click "Generate Sitemap Now"
4. Optionally ping search engines

### 4. Configure Robots.txt

1. Go to Settings → Robots.txt tab
2. Edit the robots.txt content
3. Save changes

## Adding SEO to Your Models

### Step 1: Add the Trait

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HyroPlugins\SeoOptimizer\Traits\HasSeo;

class Post extends Model
{
    use HasSeo;
}
```

### Step 2: Update Your Layout

In your `resources/views/layouts/app.blade.php` (or similar):

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- SEO Meta Tags --}}
    @if(isset($model))
        {!! seo($model)->render() !!}
        {!! schema($model)->render() !!}
    @else
        {!! seo()->render() !!}
    @endif
    
    {{-- Your other head content --}}
</head>
<body>
    @yield('content')
</body>
</html>
```

### Step 3: Use in Controllers

```php
public function show($slug)
{
    $post = Post::where('slug', $slug)->withSeo()->firstOrFail();
    
    return view('posts.show', ['model' => $post]);
}
```

## Verifying Installation

### Check Routes

```bash
php artisan route:list --name=seo-optimizer
```

You should see:
- `hyro.plugin.seo-optimizer.index`
- `hyro.plugin.seo-optimizer.settings`
- `hyro.plugin.seo-optimizer.redirects`
- `sitemap`
- `robots`

### Check Public URLs

Visit these URLs to verify:
- `http://your-domain.com/sitemap.xml`
- `http://your-domain.com/robots.txt`

### Check Database Tables

```bash
php artisan tinker
```

```php
DB::table('seo_meta')->count();
DB::table('seo_redirects')->count();
DB::table('seo_settings')->count();
```

## Troubleshooting

### Plugin Not Showing in Admin

1. Clear cache:
```bash
php artisan cache:clear
php artisan config:clear
```

2. Refresh plugin cache:
```bash
php artisan hyro:plugin:refresh
```

### Routes Not Working

1. Clear route cache:
```bash
php artisan route:clear
```

2. Dump autoload:
```bash
composer dump-autoload
```

### Migrations Failed

1. Check if tables already exist:
```bash
php artisan tinker
Schema::hasTable('seo_meta');
```

2. If tables exist, skip migrations or drop them first

### Livewire Components Not Loading

1. Clear Livewire cache:
```bash
php artisan livewire:discover
```

2. Publish Livewire assets:
```bash
php artisan livewire:publish --assets
```

## Uninstallation

To completely remove the plugin:

```bash
# Deactivate
php artisan hyro:plugin:deactivate seo-optimizer

# Uninstall (removes database tables)
php artisan hyro:plugin:uninstall seo-optimizer
```

## Updating

When a new version is available:

```bash
# Deactivate current version
php artisan hyro:plugin:deactivate seo-optimizer

# Replace plugin files with new version

# Run migrations
php artisan migrate

# Reactivate
php artisan hyro:plugin:activate seo-optimizer

# Clear cache
php artisan cache:clear
```

## Support

For issues or questions:
- Email: support@hyro.dev
- Documentation: See README.md
- Examples: See EXAMPLES.md

## Next Steps

1. Read the [README.md](README.md) for full documentation
2. Check [EXAMPLES.md](EXAMPLES.md) for usage examples
3. Review [CHANGELOG.md](CHANGELOG.md) for version history
4. Start adding SEO to your models!
