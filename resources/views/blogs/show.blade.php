<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Show Blog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg  px-6 py-4 dark:text-white">
                <div>
                    <span>Title: {{ $blog->title }}</span>
                </div>
                <div>
                    <span>Content: {{ $blog->content }}</span>
                </div>
                <div>
                    <span>Status: {{ $blog->status }}</span>
                </div>
                <div>
                    <span>Image: <img src="{{ $blog->imageUrl() }}" alt="{{ $blog->title . ' Image' }}"></span>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
