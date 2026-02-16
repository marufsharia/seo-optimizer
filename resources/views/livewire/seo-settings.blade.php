<div class="space-y-6">
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">SEO Settings</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure your site's SEO settings</p>
        </div>
        <a href="{{ route('hyro.plugin.seo-optimizer.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
            Back to Dashboard
        </a>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="flex space-x-4">
            <button wire:click="$set('activeTab', 'general')" 
                    class="px-4 py-2 border-b-2 font-medium text-sm {{ $activeTab === 'general' ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                General
            </button>
            <button wire:click="$set('activeTab', 'social')" 
                    class="px-4 py-2 border-b-2 font-medium text-sm {{ $activeTab === 'social' ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Social Media
            </button>
            <button wire:click="$set('activeTab', 'sitemap')" 
                    class="px-4 py-2 border-b-2 font-medium text-sm {{ $activeTab === 'sitemap' ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Sitemap
            </button>
            <button wire:click="$set('activeTab', 'robots')" 
                    class="px-4 py-2 border-b-2 font-medium text-sm {{ $activeTab === 'robots' ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Robots.txt
            </button>
        </nav>
    </div>

    {{-- General Settings --}}
    @if($activeTab === 'general')
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">General SEO Settings</h3>
        
        <form wire:submit.prevent="saveGeneral" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Site Name</label>
                <input type="text" wire:model="site_name" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                @error('site_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title Template</label>
                <input type="text" wire:model="title_template" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Use {title} for page title and {site} for site name</p>
                @error('title_template') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Default Description</label>
                <textarea wire:model="default_description" rows="3" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white"></textarea>
                @error('default_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Default OG Image URL</label>
                <input type="text" wire:model="default_og_image" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                @error('default_og_image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 font-medium shadow-lg shadow-purple-500/50">
                    Save General Settings
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Social Media Settings --}}
    @if($activeTab === 'social')
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Social Media Integration</h3>
        
        <form wire:submit.prevent="saveSocial" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Twitter Handle</label>
                <input type="text" wire:model="twitter_handle" placeholder="@yourhandle" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                @error('twitter_handle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Facebook App ID</label>
                <input type="text" wire:model="facebook_app_id" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                @error('facebook_app_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 font-medium shadow-lg shadow-purple-500/50">
                    Save Social Settings
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Sitemap Settings --}}
    @if($activeTab === 'sitemap')
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Sitemap Configuration</h3>
        
        <form wire:submit.prevent="saveSitemap" class="space-y-6">
            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Enable Sitemap</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Generate and serve sitemap.xml</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="sitemap_enabled" class="sr-only peer" value="1">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                </label>
            </div>

            <div class="flex space-x-3">
                <button type="button" wire:click="generateSitemap" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    <span wire:loading.remove wire:target="generateSitemap">Generate Sitemap Now</span>
                    <span wire:loading wire:target="generateSitemap">Generating...</span>
                </button>
                <button type="button" wire:click="pingSitemapToSearchEngines" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                    <span wire:loading.remove wire:target="pingSitemapToSearchEngines">Ping Search Engines</span>
                    <span wire:loading wire:target="pingSitemapToSearchEngines">Pinging...</span>
                </button>
            </div>

            <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                <p class="text-sm text-yellow-800 dark:text-yellow-300">
                    <strong>Note:</strong> The "Ping Search Engines" feature only works on production sites with public domain names. It will not work on localhost or local development environments because search engines cannot access local URLs.
                </p>
            </div>

            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <p class="text-sm text-blue-800 dark:text-blue-300">
                    <strong>Sitemap URL:</strong> <a href="{{ url('/sitemap.xml') }}" target="_blank" class="underline">{{ url('/sitemap.xml') }}</a>
                </p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 font-medium shadow-lg shadow-purple-500/50">
                    Save Sitemap Settings
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Robots.txt Settings --}}
    @if($activeTab === 'robots')
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Robots.txt Configuration</h3>
        
        <form wire:submit.prevent="saveRobots" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Robots.txt Content</label>
                <textarea wire:model="robots_content" rows="10" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white font-mono text-sm"></textarea>
                @error('robots_content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <p class="text-sm text-blue-800 dark:text-blue-300">
                    <strong>Robots.txt URL:</strong> <a href="{{ url('/robots.txt') }}" target="_blank" class="underline">{{ url('/robots.txt') }}</a>
                </p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 font-medium shadow-lg shadow-purple-500/50">
                    Save Robots.txt
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
