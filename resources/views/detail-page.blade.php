<x-home-layout>
    <div class="max-w-7xl items-center mx-auto justify-center mt-10">
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
    </div>
</x-home-layout>
