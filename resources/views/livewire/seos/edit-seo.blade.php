<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <x-ui.flash-message
                :message="session('message')"
                :type="session('message_type', 'success')"
            />
        @endif

        <div class="space-y-6">
            <div class="px-4 sm:px-0">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500 dark:text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9m3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ __('Edit SEO') }} - {{ $seo->id == 1 ? 'Description' : ($seo->id == 2 ? 'Keywords' : 'Unknown') }}
                    </h3>
                </div>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Update the SEO content for different languages.') }}
                </p>
            </div>

            <form wire:submit.prevent="save" class="space-y-6">
                <!-- English -->
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-md overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <label for="en" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('English') }}
                        </label>
                        <textarea
                            wire:model="translations.en"
                            id="en"
                            rows="4"
                            class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        ></textarea>
                        @error('translations.en') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Dutch -->
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-md overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <label for="nl" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Dutch') }}
                        </label>
                        <textarea
                            wire:model="translations.nl"
                            id="nl"
                            rows="4"
                            class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        ></textarea>
                        @error('translations.nl') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- French -->
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-md overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <label for="fr" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('French') }}
                        </label>
                        <textarea
                            wire:model="translations.fr"
                            id="fr"
                            rows="4"
                            class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        ></textarea>
                        @error('translations.fr') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Spanish -->
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-md overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <label for="es" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('español') }}
                        </label>
                        <textarea
                            wire:model="translations.es"
                            id="es"
                            rows="4"
                            class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        ></textarea>
                        @error('translations.es') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 dark:bg-gray-800 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                    <a href="{{ route('seos.index') }}" class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Cancel') }}
                    </a>
                    <x-ui.button type="submit">
                        {{ __('Save') }}
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</div>
