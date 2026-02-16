# SEO Optimizer - User Guide

Welcome to the SEO Optimizer plugin for Hyro! This guide will walk you through every feature and help you get the most out of your SEO efforts.

## Table of Contents

1. [Getting Started](#getting-started)
2. [Dashboard Overview](#dashboard-overview)
3. [General Settings](#general-settings)
4. [Social Media Settings](#social-media-settings)
5. [Sitemap Management](#sitemap-management)
6. [Robots.txt Configuration](#robotstxt-configuration)
7. [Redirect Management](#redirect-management)
8. [Using SEO with Your Content](#using-seo-with-your-content)
9. [Best Practices](#best-practices)
10. [FAQ](#faq)

---

## Getting Started

### Accessing the Plugin

After installation and activation, you can access the SEO Optimizer from your Hyro admin panel:

1. Log in to your admin panel
2. Look for **SEO Optimizer** in the sidebar menu
3. Click to open the dashboard

### First-Time Setup

When you first open the plugin, follow these steps:

1. **Configure General Settings**
   - Set your site name
   - Define your title template
   - Add a default description
   - Upload a default social sharing image

2. **Set Up Social Media**
   - Add your Twitter handle
   - Add your Facebook App ID (if you have one)

3. **Generate Your Sitemap**
   - Enable sitemap generation
   - Click "Generate Sitemap Now"

4. **Configure Robots.txt**
   - Review the default robots.txt content
   - Customize if needed

---

## Dashboard Overview

The dashboard provides a quick overview of your SEO status:

### Statistics Cards

- **Active Redirects** - Number of active URL redirects
- **SEO Meta Records** - Total pages with SEO data
- **Sitemap Status** - Whether sitemap is enabled/disabled
- **Plugin Version** - Current version installed

### Quick Actions

- **SEO Settings** - Jump to settings configuration
- **Redirect Manager** - Manage URL redirects

### Quick Start Guide

The dashboard includes a helpful quick start guide with:
- How to add the HasSeo trait to models
- How to render SEO tags in layouts
- Where to configure settings

---

## General Settings

### Site Name

Your website's name that appears in search results and social shares.

**Example**: "My Awesome Blog"

**Where it's used**:
- Browser tab titles
- Social media shares
- Search engine results

### Title Template

Define how page titles are formatted across your site.

**Available placeholders**:
- `{title}` - The page-specific title
- `{site}` - Your site name

**Examples**:
- `{title} | {site}` → "About Us | My Awesome Blog"
- `{title} - {site}` → "About Us - My Awesome Blog"
- `{site} - {title}` → "My Awesome Blog - About Us"

**Best Practice**: Keep titles under 60 characters for optimal display in search results.

### Default Description

A fallback description used when a page doesn't have a specific description.

**Best Practices**:
- Keep it between 150-160 characters
- Make it compelling and descriptive
- Include your main keywords naturally
- Avoid duplicate descriptions across pages

**Example**: "Discover expert tips, tutorials, and insights about web development, Laravel, and modern programming practices."

### Default OG Image

The default image shown when your pages are shared on social media.

**Requirements**:
- Minimum size: 1200x630 pixels (recommended)
- Format: JPG or PNG
- File size: Under 8MB
- Aspect ratio: 1.91:1

**Best Practices**:
- Use high-quality images
- Include your logo or branding
- Avoid text-heavy images
- Test on different platforms

---

## Social Media Settings

### Twitter Handle

Your Twitter username for Twitter Card attribution.

**Format**: `@yourhandle` or `yourhandle`

**Example**: `@myawesomeblog`

**Benefits**:
- Proper attribution when content is shared
- Enables Twitter analytics
- Shows your profile in Twitter Cards

### Facebook App ID

Your Facebook App ID for Facebook analytics and insights.

**How to get it**:
1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create an app (if you don't have one)
3. Copy your App ID

**Benefits**:
- Track social engagement
- Access Facebook Insights
- Enable advanced Facebook features

---

## Sitemap Management

### What is a Sitemap?

A sitemap is an XML file that lists all important pages on your website, helping search engines discover and index your content more efficiently.

### Enabling the Sitemap

1. Go to **SEO Optimizer** → **Settings**
2. Click the **Sitemap** tab
3. Toggle "Enable Sitemap" to ON
4. Click "Save Sitemap Settings"

### Generating the Sitemap

**Automatic Generation**:
- The sitemap is automatically generated when accessed
- Cached for 1 hour for performance
- Updates automatically when cache expires

**Manual Generation**:
1. Go to the Sitemap tab
2. Click "Generate Sitemap Now"
3. Wait for confirmation message

### Viewing Your Sitemap

Your sitemap is available at:
```
https://yourdomain.com/sitemap.xml
```

### Pinging Search Engines

After generating your sitemap, notify search engines:

1. Click "Ping Search Engines"
2. Wait for results (Google and Bing)

**Important Notes**:
- Only works on production sites (not localhost)
- Requires public domain name
- May take a few seconds to complete
- Don't ping too frequently (once per day is enough)

### Best Practices

- Generate sitemap after major content updates
- Ping search engines after significant changes
- Keep sitemap under 50,000 URLs
- Submit sitemap to Google Search Console
- Submit sitemap to Bing Webmaster Tools

---

## Robots.txt Configuration

### What is Robots.txt?

The robots.txt file tells search engines which pages they can and cannot crawl on your website.

### Default Configuration

The plugin provides a sensible default:

```
User-agent: *
Disallow:

Sitemap: https://yourdomain.com/sitemap.xml
```

This allows all search engines to crawl all pages.

### Common Configurations

**Block specific directories**:
```
User-agent: *
Disallow: /admin/
Disallow: /private/
Disallow: /temp/
```

**Block specific files**:
```
User-agent: *
Disallow: /secret-page.html
Disallow: /*.pdf$
```

**Block specific search engines**:
```
User-agent: BadBot
Disallow: /

User-agent: *
Disallow:
```

**Set crawl delay**:
```
User-agent: *
Crawl-delay: 10
Disallow:
```

### Testing Your Robots.txt

1. Visit: `https://yourdomain.com/robots.txt`
2. Use [Google's Robots Testing Tool](https://www.google.com/webmasters/tools/robots-testing-tool)
3. Verify blocked/allowed URLs

### Best Practices

- Don't block CSS or JavaScript files
- Don't use robots.txt for sensitive data (use authentication)
- Always include sitemap reference
- Test before deploying
- Keep it simple and clear

---

## Redirect Management

### Why Use Redirects?

Redirects are essential for:
- Moving pages to new URLs
- Fixing broken links
- Consolidating duplicate content
- Maintaining SEO value when restructuring

### Types of Redirects

**301 - Permanent Redirect**:
- Use when a page has permanently moved
- Passes ~90-99% of link equity
- Tells search engines to update their index

**302 - Temporary Redirect**:
- Use for temporary moves
- Doesn't pass full link equity
- Search engines keep the original URL

### Creating a Redirect

1. Go to **SEO Optimizer** → **Redirects**
2. Click "Add Redirect"
3. Fill in the form:
   - **Old URL**: The URL to redirect from (e.g., `/old-page`)
   - **New URL**: The destination URL (e.g., `/new-page`)
   - **Status Code**: Choose 301 or 302
   - **Active**: Toggle to enable/disable
4. Click "Save"

### Managing Redirects

**Edit a Redirect**:
1. Find the redirect in the list
2. Click "Edit"
3. Make your changes
4. Click "Save"

**Delete a Redirect**:
1. Find the redirect in the list
2. Click "Delete"
3. Confirm deletion

**Toggle Active Status**:
- Click the status badge to quickly enable/disable

**Search Redirects**:
- Use the search box to filter by old or new URL

### Redirect Statistics

Each redirect tracks:
- **Hits**: Number of times the redirect was used
- **Created Date**: When the redirect was created
- **Status**: Active or Inactive

### Best Practices

- Always use 301 for permanent moves
- Test redirects after creating them
- Avoid redirect chains (A→B→C)
- Monitor hit counts to identify popular redirects
- Clean up unused redirects periodically
- Document why redirects were created

### Common Redirect Patterns

**Single Page**:
```
Old: /about-us
New: /about
```

**Category Change**:
```
Old: /blog/category/news
New: /news
```

**URL Structure Change**:
```
Old: /products/item/123
New: /shop/products/123
```

**Domain Change** (use full URLs):
```
Old: https://old-domain.com/page
New: https://new-domain.com/page
```

---

## Using SEO with Your Content

### Adding SEO to Models

To enable SEO for your content (articles, products, pages, etc.):

1. Open your model file (e.g., `app/Models/Article.php`)
2. Add the HasSeo trait:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HyroPlugins\SeoOptimizer\Traits\HasSeo;

class Article extends Model
{
    use HasSeo;
    
    // Your model code...
}
```

### Setting SEO Data

**In your controller or form handler**:

```php
$article = Article::find(1);

$article->updateSeo([
    'title' => 'Complete Guide to Laravel SEO',
    'description' => 'Learn how to optimize your Laravel application for search engines with this comprehensive guide.',
    'keywords' => 'laravel, seo, optimization, tutorial, guide',
    'og_image' => '/images/articles/laravel-seo.jpg',
    'canonical_url' => 'https://example.com/articles/laravel-seo-guide',
    'robots' => 'index,follow',
]);
```

### Rendering SEO Tags

**In your layout file** (e.g., `resources/views/layouts/app.blade.php`):

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- SEO Meta Tags --}}
    {!! seo($model ?? null)->render() !!}
    
    {{-- Your other head tags --}}
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    @yield('content')
</body>
</html>
```

**In your view** (e.g., `resources/views/articles/show.blade.php`):

```blade
@extends('layouts.app')

@section('content')
    <article>
        <h1>{{ $article->title }}</h1>
        <div>{!! $article->content !!}</div>
    </article>
@endsection
```

The `seo($article)` in your layout will automatically use the article's SEO data.

### What Gets Generated

The plugin generates:

**Basic Meta Tags**:
```html
<title>Complete Guide to Laravel SEO | My Site</title>
<meta name="description" content="Learn how to optimize...">
<meta name="keywords" content="laravel, seo, optimization">
<link rel="canonical" href="https://example.com/articles/laravel-seo-guide">
<meta name="robots" content="index,follow">
```

**Open Graph Tags** (Facebook, LinkedIn):
```html
<meta property="og:title" content="Complete Guide to Laravel SEO">
<meta property="og:description" content="Learn how to optimize...">
<meta property="og:image" content="https://example.com/images/articles/laravel-seo.jpg">
<meta property="og:url" content="https://example.com/articles/laravel-seo-guide">
<meta property="og:type" content="website">
<meta property="og:site_name" content="My Site">
```

**Twitter Card Tags**:
```html
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Complete Guide to Laravel SEO">
<meta name="twitter:description" content="Learn how to optimize...">
<meta name="twitter:image" content="https://example.com/images/articles/laravel-seo.jpg">
<meta name="twitter:site" content="@mysite">
```

---

## Best Practices

### Title Optimization

✅ **Do**:
- Keep titles under 60 characters
- Include primary keyword near the beginning
- Make titles unique for each page
- Use compelling, clickable language
- Include your brand name

❌ **Don't**:
- Stuff keywords
- Use all caps
- Duplicate titles across pages
- Use generic titles like "Home" or "Page 1"

### Description Optimization

✅ **Do**:
- Keep descriptions 150-160 characters
- Include primary and secondary keywords
- Write compelling copy that encourages clicks
- Make each description unique
- Include a call-to-action

❌ **Don't**:
- Copy content directly from the page
- Stuff keywords
- Use duplicate descriptions
- Leave descriptions empty

### Image Optimization

✅ **Do**:
- Use high-quality images (1200x630px for OG)
- Optimize file size (under 1MB)
- Use descriptive file names
- Include your branding
- Test on different platforms

❌ **Don't**:
- Use low-resolution images
- Use images with too much text
- Forget to test mobile display
- Use copyrighted images

### URL Structure

✅ **Do**:
- Use descriptive, readable URLs
- Include keywords in URLs
- Use hyphens to separate words
- Keep URLs short and simple
- Use lowercase letters

❌ **Don't**:
- Use special characters or spaces
- Create deep URL hierarchies
- Use session IDs or parameters
- Change URLs frequently

### Content Strategy

✅ **Do**:
- Create unique, valuable content
- Update content regularly
- Use proper heading hierarchy (H1, H2, H3)
- Include internal links
- Optimize for user intent

❌ **Don't**:
- Duplicate content across pages
- Keyword stuff
- Create thin content
- Ignore mobile users
- Forget about page speed

---

## FAQ

### General Questions

**Q: Do I need technical knowledge to use this plugin?**
A: No! The plugin is designed to be user-friendly. Basic tasks like setting titles and descriptions require no technical knowledge.

**Q: Will this plugin improve my search rankings?**
A: The plugin provides the tools for proper SEO implementation. Rankings depend on many factors including content quality, backlinks, and competition.

**Q: Can I use this with other SEO plugins?**
A: It's not recommended to use multiple SEO plugins simultaneously as they may conflict.

### Technical Questions

**Q: Does the plugin slow down my site?**
A: No. The plugin uses caching and optimized queries to minimize performance impact.

**Q: Can I customize the generated meta tags?**
A: Yes! You can extend the MetaService class to customize tag generation.

**Q: Does it work with custom post types?**
A: Yes! Add the HasSeo trait to any Eloquent model.

**Q: Can I export/import redirects?**
A: Currently, redirects are managed through the database. You can export/import via SQL.

### Troubleshooting

**Q: My meta tags aren't showing up**
A: Check that:
1. You've added `{!! seo()->render() !!}` to your layout
2. Your model uses the HasSeo trait
3. SEO data is saved in the database

**Q: Redirects aren't working**
A: Verify that:
1. The redirect is marked as "Active"
2. The old URL matches exactly
3. Cache is cleared

**Q: Sitemap is empty**
A: The default sitemap only includes manually added URLs. You need to add your models to the sitemap generation.

**Q: Ping search engines fails**
A: This is normal on localhost. It only works on production sites with public domains.

### Support

**Need more help?**
- Check the [GitHub Issues](https://github.com/marufsharia/seo-optimizer/issues)
- Read the [full documentation](https://github.com/marufsharia/seo-optimizer)
- Contact support at support@hyro.dev

---

## Conclusion

Congratulations! You now know how to use all features of the SEO Optimizer plugin. Remember:

1. **Start with the basics** - Configure general settings first
2. **Be consistent** - Use the same format for titles and descriptions
3. **Monitor results** - Check your search rankings and adjust
4. **Stay updated** - Keep the plugin updated for new features
5. **Test everything** - Always test changes before going live

Happy optimizing! 🚀

---

**Last Updated**: February 2026
**Plugin Version**: 1.0.0
**Hyro Version**: 1.0+
