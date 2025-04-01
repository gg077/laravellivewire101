<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">
        {{ $post['title_'.$currentLanguage] }}
    </h1>

    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        <div class="mb-4 text-sm">
            <span class="text-gray-500 dark:text-gray-400">Auteur:</span>
            <span class="font-semibold text-gray-900 dark:text-white">{{ $post->author->name }}</span>
        </div>

        <div class="mb-4 text-sm">
            <span class="text-gray-500 dark:text-gray-400">Laatst bijgewerkt op:</span>
            <span class="text-gray-900 dark:text-white">{{ $post->updated_at->format('d-m-Y H:i') }}</span>
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
                {{ $post['content_'.$currentLanguage] }}
            </p>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('posts.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
            Terug naar overzicht
        </a>
    </div>
</div>
