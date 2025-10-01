<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Blogs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800  shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex mb-2 justify-end">
                        @if (Auth::user()->role === \App\Enums\UserRole::USER)
                            <a href="{{ route('blogs.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md
                            font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500
                            active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                            transition ease-in-out duration-150">
                                Create New Blog
                            </a>
                        @endif
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left border border-gray-200 dark:border-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 border-b dark:border-gray-600">#</th>
                                    <th class="px-4 py-2 border-b dark:border-gray-600">Title</th>
                                    <th class="px-4 py-2 border-b dark:border-gray-600">Status</th>
                                    <th class="px-4 py-2 border-b dark:border-gray-600">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($blogs as $blog)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-2 border-b dark:border-gray-600">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2 border-b dark:border-gray-600">{{ $blog->title }}</td>
                                        <td class="px-4 py-2 border-b dark:border-gray-600">
                                            @if (Auth::user()->role === \App\Enums\UserRole::ADMIN)
                                                @php
                                                    // If using enums, you can use ->value for option values
                                                    $current = is_string($blog->status)
                                                        ? $blog->status
                                                        : $blog->status->value;
                                                @endphp
                                                <div class="flex items-center">
                                                    <select
                                                        class="status-select rounded border-gray-300 dark:bg-gray-800 dark:border-gray-600"
                                                        data-update-url="{{ route('blogs.status.update', $blog) }}">
                                                        @foreach (\App\Enums\BlogStatus::cases() as $case)
                                                            <option value="{{ $case->value }}"
                                                                @selected($current === $case->value)>
                                                                {{ Str::headline($case->value) }}
                                                            </option>
                                                        @endforeach
                                                    </select>


                                                    <span class="status-feedback text-xs text-gray-500"></span>
                                                </div>
                                            @else
                                                <span>{{ $blog->status }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 border-b dark:border-gray-600">
                                            <a href="{{ route('blogs.show', $blog) }}"
                                                class="text-blue-500 hover:underline px-4 py-2">View</a>
                                            @if ($blog->status === \App\Enums\BlogStatus::PENDING || $blog->status === \App\Enums\BlogStatus::REJECTED)
                                                @if (Auth::user()->role === \App\Enums\UserRole::USER)
                                                    <a href="{{ route('blogs.edit', $blog) }}"
                                                        class="text-green-500 hover:underline ms-2 px-4 py-2">Edit</a>

                                                    <form action="{{ route('blogs.destroy', $blog) }}" method="POST"
                                                        class="inline ms-2">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-500 hover:underline px-4 py-2"
                                                            onclick="return confirm('Are you sure you want to delete this blog?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No blogs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- CSRF meta (usually already present in app layout) --}}
                    @push('meta')
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                    @endpush
                    <script>
                        (function() {
                            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                            // Event delegation: listen on the table for status changes
                            document.addEventListener('change', async function(e) {
                                if (!e.target.classList.contains('status-select')) return;

                                const select = e.target;
                                const row = select.closest('tr');
                                const badge = row.querySelector('.status-badge');
                                const note = row.querySelector('.status-feedback');
                                const url = select.dataset.updateUrl;
                                const value = select.value;

                                // UI: disable while saving
                                select.disabled = true;
                                note.textContent = 'Saving…';

                                try {
                                    const res = await fetch(url, {
                                        method: 'PATCH',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': token,
                                            'Accept': 'application/json',
                                        },
                                        body: JSON.stringify({
                                            status: value
                                        })
                                    });

                                    if (!res.ok) {
                                        const data = await res.json().catch(() => ({}));
                                        throw new Error(data.message || 'Failed to update status.');
                                    }

                                    // Success: update badge look + text


                                    note.textContent = 'Saved.';
                                    setTimeout(() => note.textContent = '', 1200);
                                } catch (err) {
                                    note.textContent = err.message || 'Error';
                                    // optional: revert select to previous value (if you stored it)
                                } finally {
                                    select.disabled = false;
                                }
                            }, {
                                passive: true
                            });
                        })();
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
