<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">
        Post Bewerken: {{ $title }}
    </h1>

    @if($post->author)
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            Geschreven door: <span class="font-medium text-gray-800 dark:text-white">{{ $post->author->name }}</span>
        </p>
    @endif

    <!-- TOM HEEFT DIT GEMAAKT -->
    @if (session()->has('message'))
        <x-ui.flash-message
            :message="session('message')"
            :type="session('message_type', 'success')"
        />
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <div class="grid grid-cols-6 gap-6">

                <!-- Titel -->
                <div class="col-span-6 sm:col-span-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Titel</label>
                    <input type="text" wire:model.debounce.300ms="title" id="title" class="mt-1 block w-full dark:bg-gray-900 border-gray-300 dark:border-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <x-ui.forms.error error="title" />
                </div>

                <!-- Slug -->
                <div class="col-span-6 sm:col-span-4">
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                    <input type="text" wire:model="slug" id="slug" class="mt-1 block w-full dark:bg-gray-900 border-gray-300 dark:border-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <x-ui.forms.error error="slug" />
                </div>

                <!-- Inhoud -->
                <div class="col-span-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Inhoud</label>
                    <textarea wire:model="content" id="content" rows="5" class="mt-1 block w-full dark:bg-gray-900 border-gray-300 dark:border-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    <x-ui.forms.error error="content" />
                </div>

                <!-- Categorieën -->
                <div class="col-span-6 sm:col-span-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categorieën</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($allCategories as $category)
                            <button
                                type="button"
                                wire:click="toggleCategory({{ $category->id }})"
                                class="px-3 py-1 rounded-full text-sm font-medium border transition
                    {{ in_array($category->id, $selectedCategories)
                        ? 'bg-indigo-600 text-white border-indigo-600'
                        : 'bg-gray-100 text-gray-700 border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600' }}"
                            >
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                    <x-ui.forms.error error="selectedCategories" />
                </div>


                <!-- Gepubliceerd -->
                <div class="col-span-6 sm:col-span-4">
                    <label for="is_published" class="flex items-center space-x-2">
                        <input type="checkbox" wire:model="is_published" id="is_published" class="rounded dark:bg-gray-900 dark:border-gray-600">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Gepubliceerd</span>
                    </label>
                    <x-ui.forms.error error="is_published" />
                </div>
            </div>
        </div>

        <!-- Opslaan -->
        <div class="flex justify-end">
            <x-ui.button type="submit">
                Opslaan
            </x-ui.button>
        </div>
    </form>
</div>
