<div class="mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500 dark:text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                </svg>
                <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">Alle Posts</h1>
            </div>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Beheer alle blogposts van het systeem.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('posts.create') }}" class="inline-flex items-center gap-x-2 rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 -ml-0.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nieuw Post
            </a>
        </div>
    </div>

    <div class="mt-8 overflow-hidden shadow-sm sm:rounded-lg bg-white dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Titel</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Content</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Categories</th>
                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">Acties</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($posts as $post)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $post['title_'.$currentLanguage] }}</td>

                    {{-- Inhoud in plaats van slug --}}
                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-300">
                        {{ Str::limit($post['content_'.$currentLanguage], 100) }}
                    </td>

                    <td class="px-4 py-3 text-sm">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $post->is_published ? 'bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                {{ $post->is_published ? 'Gepubliceerd' : 'Concept' }}
            </span>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex flex-wrap gap-1">
                            @foreach ($post->categories as $category)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300">
                        {{ $category->name }}
                    </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-4 py-3 text-right text-sm flex">
                        <a href="{{ route('post.show', $post['slug_'.$currentLanguage]) }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-6 mx-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>

                        </a>
                        <a href="{{ route('posts.edit', $post['slug_'.$currentLanguage]) }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-6 mx-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>

                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-300">
                        Geen berichten gevonden.
                    </td>
                </tr>
            @endforelse
            </tbody>

        </table>
    </div>

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>
