<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8 space-y-6">
        <a href="{{ route('threads.show', $thread) }}" class="text-sm underline text-gray-600">Terug naar {{ $thread->title }}</a>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ $topic->title }}</h1>
            <p class="mt-2 text-gray-700 whitespace-pre-line">{{ $topic->body }}</p>
            <p class="mt-3 text-sm text-gray-500">Door {{ $topic->user->username }}</p>

            @auth
                <div class="mt-3 flex items-center gap-4 text-sm">
                    @if (Auth::user()->canEdit($topic))
                        <a href="{{ route('topics.edit', [$thread, $topic]) }}" class="underline text-gray-700">Aanpassen</a>
                    @endif

                    @if (Auth::user()->isAdmin())
                        <form method="POST" action="{{ route('topics.destroy', [$thread, $topic]) }}" onsubmit="return confirm('Deze topic verwijderen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="underline text-red-600">Verwijderen</button>
                        </form>
                    @endif
                </div>
            @endauth
        </div>

        <div class="space-y-3">
            <h2 class="text-lg font-semibold text-gray-900">Replies</h2>

            @forelse ($topic->replies as $reply)
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <p class="text-gray-700 whitespace-pre-line">{{ $reply->body }}</p>
                    <p class="mt-2 text-sm text-gray-500">Door {{ $reply->user->username }}</p>

                    @auth
                        <div class="mt-2 flex items-center gap-4 text-sm">
                            @if (Auth::user()->canEdit($reply))
                                <a href="{{ route('replies.edit', [$thread, $topic, $reply]) }}" class="underline text-gray-700">Aanpassen</a>
                            @endif

                            @if (Auth::user()->isAdmin())
                                <form method="POST" action="{{ route('replies.destroy', [$thread, $topic, $reply]) }}" onsubmit="return confirm('Deze reply verwijderen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="underline text-red-600">Verwijderen</button>
                                </form>
                            @endif
                        </div>
                    @endauth
                </div>
            @empty
                <p class="text-gray-600">Er zijn nog geen replies.</p>
            @endforelse
        </div>

        @auth
            <form method="POST" action="{{ route('replies.store', [$thread, $topic]) }}" class="bg-white shadow-sm rounded-lg p-6 space-y-4">
                @csrf

                <div>
                    <x-input-label for="body" value="Jouw reactie" />
                    <textarea id="body" name="body" rows="4" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('body') }}</textarea>
                    <x-input-error :messages="$errors->get('body')" class="mt-2" />
                </div>

                <x-primary-button>Opslaan</x-primary-button>
            </form>
        @endauth
    </div>
</x-app-layout>
