<x-home-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-24">
        <div
            class="mx-auto justify-center items-center mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-700 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
            <div class="grid grid-cols-3 gap-5">
                @forelse ($blogs as $blog)
                    <article class="flex max-w-xl flex-col items-start justify-between">
                        <div class="flex items-center gap-x-4 text-xs">
                            <time datetime="2020-02-12" class="text-gray-400">{{ $blog->created_at }}</time>
                        </div>
                        <div class="group relative grow">
                            <h3 class="mt-3 text-lg/6 font-semibold text-white group-hover:text-gray-300">
                                <a href="{{ route('detail-page', $blog) }}">
                                    <span class="absolute inset-0"></span>
                                    {{ $blog->title }}
                                </a>
                            </h3>
                            <p class="mt-5 line-clamp-3 text-sm/6 text-gray-400">{{ $blog->content }}</p>
                        </div>
                        <div class="relative mt-8 flex items-center gap-x-4 justify-self-end">
                            <img src="{{ $blog->imageUrl() }}" alt="" class="size-10 rounded-md bg-gray-800" />
                        </div>
                    </article>
                @empty
                    <span>No Blogs</span>
                @endforelse
            </div>
        </div>
    </div>
</x-home-layout>
