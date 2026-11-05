<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8 space-y-4">
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-2xl font-semibold text-gray-900">Threads</h1>

            @auth
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('threads.create') }}" class="text-sm underline text-gray-700">Nieuwe thread</a>
                @endif
            @endauth
        </div>

        @forelse ($threads as $thread)
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h2 class="font-semibold text-gray-900">
                    <a href="{{ route('threads.show', $thread) }}" class="hover:underline">{{ $thread->title }}</a>
                </h2>
                <p class="truncate text-gray-600">{{ $thread->description }}</p>
            </div>
        @empty
            <p class="text-gray-600">Er zijn nog geen threads.</p>
        @endforelse
    </div>
</x-app-layout>
