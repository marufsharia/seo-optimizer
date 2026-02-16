<?php

namespace HyroPlugins\SeoOptimizer;

use Marufsharia\Hyro\Support\Plugins\HyroPlugin;

class Plugin extends HyroPlugin
{
    public function getId(): string
    {
        return 'seo-optimizer';
    }

    public function getName(): string
    {
        return 'SeoOptimizer';
    }

    public function getDescription(): string
    {
        return 'Production-ready SEO optimization plugin with meta tags, sitemaps, structured data, Open Graph, Twitter Cards, and redirect management';
    }

    public function getVersion(): string
    {
        return '1.0.0';
    }

    public function getAuthor(): string
    {
        return 'Hyro Team';
    }

    public function getDependencies(): array
    {
        return [];
    }

    public function register(): void
    {
        // Manually load models for traits and relationships
        $modelsPath = __DIR__ . '/Models';
        if (file_exists($modelsPath)) {
            foreach (glob($modelsPath . '/*.php') as $modelFile) {
                require_once $modelFile;
            }
        }
        
        // Manually load services
        $servicesPath = __DIR__ . '/Services';
        if (file_exists($servicesPath)) {
            foreach (glob($servicesPath . '/*.php') as $serviceFile) {
                require_once $serviceFile;
            }
        }
        
        // Manually load traits
        if (file_exists(__DIR__ . '/Traits/HasSeo.php')) {
            require_once __DIR__ . '/Traits/HasSeo.php';
        }
        
        // Register services
        $this->app->singleton('seo.meta', function ($app) {
            return new \HyroPlugins\SeoOptimizer\Services\MetaService();
        });
        
        $this->app->singleton('seo.sitemap', function ($app) {
            return new \HyroPlugins\SeoOptimizer\Services\SitemapService();
        });
        
        $this->app->singleton('seo.schema', function ($app) {
            return new \HyroPlugins\SeoOptimizer\Services\StructuredDataService();
        });
        
        // Load helpers
        if (file_exists(__DIR__ . '/helpers.php')) {
            require_once __DIR__ . '/helpers.php';
        }
    }

    public function boot(): void
    {
        // Boot plugin here
        $this->info('Plugin SeoOptimizer booted!');
        
        // Manually load Livewire component classes
        if (file_exists(__DIR__ . '/Livewire/SeoSettings.php')) {
            require_once __DIR__ . '/Livewire/SeoSettings.php';
        }
        if (file_exists(__DIR__ . '/Livewire/RedirectManager.php')) {
            require_once __DIR__ . '/Livewire/RedirectManager.php';
        }
        
        // Register Livewire components
        if (class_exists(\Livewire\Livewire::class)) {
            try {
                if (class_exists(\HyroPlugins\SeoOptimizer\Livewire\SeoSettings::class)) {
                    \Livewire\Livewire::component('seo-optimizer::seo-settings', \HyroPlugins\SeoOptimizer\Livewire\SeoSettings::class);
                    $this->info('Registered Livewire component: seo-optimizer::seo-settings');
                }
                if (class_exists(\HyroPlugins\SeoOptimizer\Livewire\RedirectManager::class)) {
                    \Livewire\Livewire::component('seo-optimizer::redirect-manager', \HyroPlugins\SeoOptimizer\Livewire\RedirectManager::class);
                    $this->info('Registered Livewire component: seo-optimizer::redirect-manager');
                }
            } catch (\Exception $e) {
                $this->info('Livewire component registration failed: ' . $e->getMessage());
            }
        }
        
        // Manually load middleware
        if (file_exists(__DIR__ . '/Middleware/SeoRedirectMiddleware.php')) {
            require_once __DIR__ . '/Middleware/SeoRedirectMiddleware.php';
        }
        
        // Register middleware only if the class exists
        if (class_exists(\HyroPlugins\SeoOptimizer\Middleware\SeoRedirectMiddleware::class)) {
            try {
                $this->app['router']->pushMiddlewareToGroup('web', \HyroPlugins\SeoOptimizer\Middleware\SeoRedirectMiddleware::class);
            } catch (\Exception $e) {
                // Silently fail if middleware registration fails
            }
        }
    }

    public function routes(): ?string
    {
        return __DIR__ . '/../routes/web.php';
    }

    public function migrations(): ?string
    {
        return __DIR__ . '/../database/migrations';
    }

    public function views(): ?string
    {
        return __DIR__ . '/../resources/views';
    }

    public function install(): void
    {
        parent::install();
        
        $this->info('Installing SeoOptimizer...');
        
        // Run migrations automatically
        if ($this->migrations()) {
            $this->runMigrations();
        }
        
        // Publish assets if any
        $this->publishAssets();
        
        $this->info('SeoOptimizer installed successfully!');
    }

    public function uninstall(): void
    {
        parent::uninstall();
        
        $this->info('Uninstalling SeoOptimizer...');
        
        // Rollback migrations
        if ($this->migrations()) {
            $this->rollbackMigrations();
        }
        
        // Remove published assets
        $this->removeAssets();
        
        $this->info('SeoOptimizer uninstalled successfully!');
    }

    public function activate(): void
    {
        parent::activate();
        
        $this->info('SeoOptimizer activated!');
        
        // Register sidebar menu
        $this->registerSidebarMenu();
        
        // Ensure assets are published
        $this->publishAssets();
    }

    public function deactivate(): void
    {
        parent::deactivate();
        
        $this->info('SeoOptimizer deactivated!');
        
        // Unregister sidebar menu
        $this->unregisterSidebarMenu();
    }
    
    /**
     * Register plugin in sidebar menu
     */
    protected function registerSidebarMenu(): void
    {
        $menuFile = storage_path('app/private/hyro/menu.json');
        
        // Ensure directory exists
        if (!file_exists(dirname($menuFile))) {
            mkdir(dirname($menuFile), 0755, true);
        }
        
        $menu = file_exists($menuFile) ? json_decode(file_get_contents($menuFile), true) : [];
        
        // Add plugin menu item with submenu
        $menuItem = [
            'id' => 'seo-optimizer',
            'label' => 'SEO Optimizer',
            'icon' => 'chart-line',
            'url' => '/hyro/plugins/seo-optimizer',
            'order' => 100,
            'plugin' => 'seo-optimizer',
            'group' => 'Marketing',
            'children' => [
                [
                    'id' => 'seo-optimizer-dashboard',
                    'label' => 'Dashboard',
                    'icon' => 'home',
                    'url' => '/hyro/plugins/seo-optimizer',
                ],
                [
                    'id' => 'seo-optimizer-settings',
                    'label' => 'Settings',
                    'icon' => 'cog',
                    'url' => '/hyro/plugins/seo-optimizer/settings',
                ],
                [
                    'id' => 'seo-optimizer-redirects',
                    'label' => 'Redirects',
                    'icon' => 'arrow-right',
                    'url' => '/hyro/plugins/seo-optimizer/redirects',
                ],
            ],
        ];
        
        // Check if already exists
        $exists = false;
        foreach ($menu as $key => $item) {
            if (isset($item['id']) && $item['id'] === 'seo-optimizer') {
                $menu[$key] = $menuItem; // Update existing
                $exists = true;
                break;
            }
        }
        
        if (!$exists) {
            $menu[] = $menuItem;
        }
        
        file_put_contents($menuFile, json_encode($menu, JSON_PRETTY_PRINT));
        $this->info('Registered in sidebar menu!');
    }
    
    /**
     * Unregister plugin from sidebar menu
     */
    protected function unregisterSidebarMenu(): void
    {
        $menuFile = storage_path('app/private/hyro/menu.json');
        
        if (!file_exists($menuFile)) {
            return;
        }
        
        $menu = json_decode(file_get_contents($menuFile), true);
        
        // Remove plugin menu item
        $menu = array_filter($menu, function ($item) {
            return !isset($item['id']) || $item['id'] !== 'seo-optimizer';
        });
        
        file_put_contents($menuFile, json_encode(array_values($menu), JSON_PRETTY_PRINT));
        $this->info('Removed from sidebar menu!');
    }
    
    /**
     * Run plugin migrations
     */
    protected function runMigrations(): void
    {
        $migrationsPath = $this->migrations();
        
        if ($migrationsPath && file_exists($migrationsPath)) {
            \Illuminate\Support\Facades\Artisan::call('migrate', [
                '--path' => str_replace(base_path() . '/', '', $migrationsPath),
                '--force' => true,
            ]);
            $this->info('Migrations executed!');
        }
    }
    
    /**
     * Rollback plugin migrations
     */
    protected function rollbackMigrations(): void
    {
        $migrationsPath = $this->migrations();
        
        if ($migrationsPath && file_exists($migrationsPath)) {
            \Illuminate\Support\Facades\Artisan::call('migrate:rollback', [
                '--path' => str_replace(base_path() . '/', '', $migrationsPath),
                '--force' => true,
            ]);
            $this->info('Migrations rolled back!');
        }
    }
    
    /**
     * Publish plugin assets
     */
    protected function publishAssets(): void
    {
        $assetsPath = __DIR__ . '/../resources/assets';
        $publicPath = public_path('vendor/hyro-plugins/seo-optimizer');
        
        if (file_exists($assetsPath)) {
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            // Copy assets recursively
            $this->copyDirectory($assetsPath, $publicPath);
            $this->info('Assets published!');
        }
    }
    
    /**
     * Remove published assets
     */
    protected function removeAssets(): void
    {
        $publicPath = public_path('vendor/hyro-plugins/seo-optimizer');
        
        if (file_exists($publicPath)) {
            $this->deleteDirectory($publicPath);
            $this->info('Assets removed!');
        }
    }
    
    /**
     * Copy directory recursively
     */
    protected function copyDirectory($source, $destination): void
    {
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $item) {
            $target = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            if ($item->isDir()) {
                if (!file_exists($target)) {
                    mkdir($target, 0755, true);
                }
            } else {
                copy($item, $target);
            }
        }
    }
    
    /**
     * Delete directory recursively
     */
    protected function deleteDirectory($directory): void
    {
        if (!file_exists($directory)) {
            return;
        }
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($iterator as $item) {
            if ($item->isDir()) {
                rmdir($item);
            } else {
                unlink($item);
            }
        }
        
        rmdir($directory);
    }
}