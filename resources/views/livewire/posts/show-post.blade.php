<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    @if (session()->has('message'))
        <x-ui.flash-message
            :message="session('message')"
            :type="session('message_type', 'success')"
        />
    @endif

    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
        @if($editing)
            <div class="w-full">
                <input
                    type="text"
                    wire:model="title"
                    class="p-2 border rounded-md w-full bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white @error('title') border-red-500 @enderror"
                >
                @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        @else
            <span>{{ $post['title_'.$currentLanguage] }}</span>
        @endif

        @if(Auth::user()->hasRole("admin"))
            <button wire:click="toggleEdit" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.314l-4.5 1.5 1.5-4.5L16.862 3.487z" />
                </svg>
            </button>
        @endif
    </h1>

    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 relative">
        @if($editing && Auth::user()->hasRole("admin"))
            <div class="flex justify-end mb-4">
                <button wire:click="save" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    {{ __('messages.Save') }}
                </button>
                <button wire:click="toggleEdit" class="ml-2 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    {{ __('messages.Cancel') }}
                </button>
            </div>
        @endif

        <div class="mb-4 text-sm">
            <span class="text-gray-500 dark:text-gray-400">{{ __('messages.Author') }}:</span>
            <span class="font-semibold text-gray-900 dark:text-white">{{ $post->author->name }}</span>
        </div>

        <div class="mb-4 text-sm">
            <span class="text-gray-500 dark:text-gray-400">{{ __('messages.Last updated on') }}:</span>
            <span class="text-gray-900 dark:text-white">{{ $post->updated_at->format('d-m-Y H:i') }}</span>
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            @if($editing)
                <div class="mb-4">
                    <textarea
                        wire:model="content"
                        rows="15"
                        class="p-2 border rounded-md w-full bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white @error('content') border-red-500 @enderror"
                    ></textarea>
                    @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @else
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $post['content_'.$currentLanguage] }}</p>
            @endif
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('posts.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
            {{ __('messages.Back to overview') }}
        </a>
    </div>
</div>
