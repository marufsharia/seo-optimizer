<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Redirect Manager</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage 301 and 302 redirects</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('hyro.plugin.seo-optimizer.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                Back to Dashboard
            </a>
            <button wire:click="create" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 shadow-lg shadow-purple-500/50">
                Add Redirect
            </button>
        </div>
    </div>

    {{-- Search --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search redirects..." class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
    </div>

    {{-- Redirects Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Old URL</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">New URL</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hits</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Active</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($redirects as $redirect)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $redirect->old_url }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $redirect->new_url }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 bg-{{ $redirect->status_code == 301 ? 'blue' : 'green' }}-100 dark:bg-{{ $redirect->status_code == 301 ? 'blue' : 'green' }}-900/30 text-{{ $redirect->status_code == 301 ? 'blue' : 'green' }}-700 dark:text-{{ $redirect->status_code == 301 ? 'blue' : 'green' }}-400 rounded-full text-xs font-medium">
                            {{ $redirect->status_code }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $redirect->hits }}</td>
                    <td class="px-6 py-4 text-sm">
                        <button wire:click="toggleActive({{ $redirect->id }})" class="px-2 py-1 bg-{{ $redirect->is_active ? 'green' : 'gray' }}-100 dark:bg-{{ $redirect->is_active ? 'green' : 'gray' }}-900/30 text-{{ $redirect->is_active ? 'green' : 'gray' }}-700 dark:text-{{ $redirect->is_active ? 'green' : 'gray' }}-400 rounded-full text-xs font-medium">
                            {{ $redirect->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </td>
                    <td class="px-6 py-4 text-sm text-right space-x-2">
                        <button wire:click="edit({{ $redirect->id }})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">Edit</button>
                        <button wire:click="delete({{ $redirect->id }})" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-800 dark:text-red-400">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        No redirects found. Click "Add Redirect" to create one.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div>
        {{ $redirects->links() }}
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-2xl w-full">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ $editingId ? 'Edit' : 'Add' }} Redirect</h3>
            
            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Old URL</label>
                    <input type="text" wire:model="old_url" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                    @error('old_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New URL</label>
                    <input type="text" wire:model="new_url" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                    @error('new_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Code</label>
                    <select wire:model="status_code" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:text-white">
                        <option value="301">301 - Permanent</option>
                        <option value="302">302 - Temporary</option>
                    </select>
                    @error('status_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" wire:model="is_active" class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                    <label class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Active</label>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 shadow-lg shadow-purple-500/50">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
