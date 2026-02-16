# Contributing to SEO Optimizer

First off, thank you for considering contributing to SEO Optimizer! It's people like you that make this plugin better for everyone.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
- [Development Setup](#development-setup)
- [Coding Standards](#coding-standards)
- [Commit Guidelines](#commit-guidelines)
- [Pull Request Process](#pull-request-process)
- [Reporting Bugs](#reporting-bugs)
- [Suggesting Features](#suggesting-features)

## Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct. By participating, you are expected to uphold this code. Please report unacceptable behavior to support@hyro.dev.

### Our Standards

- Be respectful and inclusive
- Welcome newcomers and help them learn
- Focus on what is best for the community
- Show empathy towards other community members
- Accept constructive criticism gracefully

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the existing issues to avoid duplicates. When you create a bug report, include as many details as possible:

**Bug Report Template:**

```markdown
**Describe the bug**
A clear and concise description of what the bug is.

**To Reproduce**
Steps to reproduce the behavior:
1. Go to '...'
2. Click on '....'
3. Scroll down to '....'
4. See error

**Expected behavior**
A clear description of what you expected to happen.

**Screenshots**
If applicable, add screenshots to help explain your problem.

**Environment:**
 - OS: [e.g. Windows, macOS, Linux]
 - PHP Version: [e.g. 8.2]
 - Laravel Version: [e.g. 11.0]
 - Hyro Version: [e.g. 1.0]
 - Plugin Version: [e.g. 1.0.0]

**Additional context**
Add any other context about the problem here.
```

### Suggesting Features

Feature suggestions are welcome! Please provide:

**Feature Request Template:**

```markdown
**Is your feature request related to a problem?**
A clear description of what the problem is.

**Describe the solution you'd like**
A clear description of what you want to happen.

**Describe alternatives you've considered**
Any alternative solutions or features you've considered.

**Additional context**
Add any other context or screenshots about the feature request.
```

### Code Contributions

1. **Fork the repository**
2. **Create a feature branch** (`git checkout -b feature/amazing-feature`)
3. **Make your changes**
4. **Test your changes**
5. **Commit your changes** (see commit guidelines below)
6. **Push to your fork** (`git push origin feature/amazing-feature`)
7. **Open a Pull Request**

## Development Setup

### Prerequisites

- PHP 8.2 or higher
- Composer
- Laravel 11.x
- Hyro Framework 1.0+
- MySQL/PostgreSQL/SQLite

### Installation

```bash
# Clone your fork
git clone https://github.com/YOUR-USERNAME/seo-optimizer.git
cd seo-optimizer

# Install dependencies
composer install

# Set up test environment (if applicable)
cp .env.example .env
php artisan key:generate
```

### Running Tests

```bash
# Run all tests
composer test

# Run specific test file
composer test tests/Unit/MetaServiceTest.php

# Run with coverage
composer test-coverage
```

### Local Development

To test the plugin in a Hyro installation:

```bash
# Link the plugin to your Hyro installation
ln -s /path/to/seo-optimizer /path/to/hyro/hyro-plugins/SeoOptimizer

# Run migrations
php artisan migrate --path=hyro-plugins/SeoOptimizer/database/migrations

# Clear cache
php artisan cache:clear
php artisan config:clear
```

## Coding Standards

### PHP Standards

We follow PSR-12 coding standards. Please ensure your code adheres to these standards.

```bash
# Check code style
composer phpcs

# Fix code style automatically
composer phpcbf
```

### Code Style Guidelines

**Classes:**
```php
<?php

namespace HyroPlugins\SeoOptimizer\Services;

use Illuminate\Support\Facades\Cache;

class ExampleService
{
    protected string $property;
    
    public function __construct()
    {
        // Constructor code
    }
    
    public function methodName(): string
    {
        // Method code
        return 'value';
    }
}
```

**Methods:**
- Use camelCase for method names
- Type hint parameters and return types
- Add docblocks for complex methods
- Keep methods focused and small

**Variables:**
- Use camelCase for variable names
- Use descriptive names
- Avoid single-letter variables (except in loops)

**Comments:**
```php
/**
 * Generate SEO meta tags for the given model
 *
 * @param  \Illuminate\Database\Eloquent\Model|null  $model
 * @return array
 */
public function generate(?Model $model = null): array
{
    // Implementation
}
```

### Database Conventions

**Migrations:**
- Use descriptive names: `2026_02_16_000001_create_seo_meta_table.php`
- Always include `up()` and `down()` methods
- Add indexes for foreign keys and frequently queried columns

**Table Names:**
- Use plural snake_case: `seo_redirects`, `seo_settings`
- Prefix plugin tables: `seo_*`

**Column Names:**
- Use snake_case: `created_at`, `is_active`
- Use descriptive names: `old_url` not `old`

### Blade Templates

```blade
{{-- Use comments for sections --}}
<div class="container">
    {{-- Always escape output --}}
    <h1>{{ $title }}</h1>
    
    {{-- Use @if for conditionals --}}
    @if($condition)
        <p>Content</p>
    @endif
    
    {{-- Use @foreach for loops --}}
    @foreach($items as $item)
        <div>{{ $item->name }}</div>
    @endforeach
</div>
```

### JavaScript/Livewire

```javascript
// Use wire:click for Livewire actions
<button wire:click="save">Save</button>

// Use wire:model for two-way binding
<input type="text" wire:model="title">

// Use wire:loading for loading states
<span wire:loading>Loading...</span>
```

## Commit Guidelines

We follow [Conventional Commits](https://www.conventionalcommits.org/) specification.

### Commit Message Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types

- `feat`: A new feature
- `fix`: A bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, missing semi-colons, etc)
- `refactor`: Code refactoring
- `perf`: Performance improvements
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

### Examples

```bash
feat(redirects): add bulk import functionality

Add ability to import redirects from CSV file.
Includes validation and error handling.

Closes #123

---

fix(sitemap): resolve caching issue

Sitemap was not clearing cache properly after generation.
Now uses Cache::forget() instead of Cache::flush().

Fixes #456

---

docs(readme): update installation instructions

Add more detailed steps for manual installation.
Include troubleshooting section.
```

### Scope

The scope should be the name of the affected component:

- `meta`: Meta tags functionality
- `sitemap`: Sitemap generation
- `redirects`: Redirect management
- `settings`: Settings management
- `ui`: User interface
- `docs`: Documentation
- `tests`: Test files

## Pull Request Process

### Before Submitting

1. **Update documentation** if you changed functionality
2. **Add tests** for new features
3. **Run tests** to ensure nothing breaks
4. **Check code style** with phpcs
5. **Update CHANGELOG.md** with your changes

### PR Template

```markdown
## Description
Brief description of what this PR does.

## Type of Change
- [ ] Bug fix (non-breaking change which fixes an issue)
- [ ] New feature (non-breaking change which adds functionality)
- [ ] Breaking change (fix or feature that would cause existing functionality to not work as expected)
- [ ] Documentation update

## How Has This Been Tested?
Describe the tests you ran and how to reproduce them.

## Checklist
- [ ] My code follows the code style of this project
- [ ] I have performed a self-review of my own code
- [ ] I have commented my code, particularly in hard-to-understand areas
- [ ] I have made corresponding changes to the documentation
- [ ] My changes generate no new warnings
- [ ] I have added tests that prove my fix is effective or that my feature works
- [ ] New and existing unit tests pass locally with my changes
- [ ] Any dependent changes have been merged and published

## Screenshots (if applicable)
Add screenshots to help explain your changes.

## Related Issues
Closes #(issue number)
```

### Review Process

1. At least one maintainer must review and approve
2. All CI checks must pass
3. No merge conflicts
4. Documentation must be updated
5. Tests must pass

### After Approval

- Maintainers will merge your PR
- Your contribution will be included in the next release
- You'll be added to the contributors list

## Development Workflow

### Branch Naming

- `feature/feature-name` - New features
- `fix/bug-description` - Bug fixes
- `docs/what-changed` - Documentation updates
- `refactor/what-changed` - Code refactoring
- `test/what-tested` - Test additions/updates

### Release Process

1. Update version in `composer.json`
2. Update `CHANGELOG.md`
3. Create release tag
4. Publish release notes

## Questions?

Feel free to ask questions by:
- Opening an issue
- Emailing support@hyro.dev
- Joining our community chat (if available)

## Recognition

Contributors will be:
- Listed in CHANGELOG.md
- Mentioned in release notes
- Added to the contributors section

Thank you for contributing! 🎉

---

**Happy Coding!** 💻
