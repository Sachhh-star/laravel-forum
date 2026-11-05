<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8 space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ $thread->title }}</h1>
            <p class="mt-2 text-gray-700 whitespace-pre-line">{{ $thread->description }}</p>
            <p class="mt-3 text-sm text-gray-500">Door {{ $thread->user->username }}</p>

            @auth
                <div class="mt-3 flex items-center gap-4 text-sm">
                    @if (Auth::user()->canEdit($thread))
                        <a href="{{ route('threads.edit', $thread) }}" class="underline text-gray-700">Aanpassen</a>
                    @endif

                    @if (Auth::user()->isAdmin())
                        <form method="POST" action="{{ route('threads.destroy', $thread) }}" onsubmit="return confirm('Deze thread verwijderen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="underline text-red-600">Verwijderen</button>
                        </form>
                    @endif
                </div>
            @endauth
        </div>

        <div class="space-y-3">
            <h2 class="text-lg font-semibold text-gray-900">Topics</h2>

            @forelse ($thread->topics as $topic)
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <a href="{{ route('topics.show', [$thread, $topic]) }}" class="font-semibold text-gray-900 hover:underline">{{ $topic->title }}</a>
                    <p class="text-sm text-gray-500">Door {{ $topic->user->username }}</p>
                </div>
            @empty
                <p class="text-gray-600">Er zijn nog geen topics in deze thread.</p>
            @endforelse
        </div>

        @auth
            <form method="POST" action="{{ route('topics.store', $thread) }}" class="bg-white shadow-sm rounded-lg p-6 space-y-4">
                @csrf

                <h2 class="text-lg font-semibold text-gray-900">Nieuwe topic</h2>

                <div>
                    <x-input-label for="title" value="Titel" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="body" value="Tekst" />
                    <textarea id="body" name="body" rows="5" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('body') }}</textarea>
                    <x-input-error :messages="$errors->get('body')" class="mt-2" />
                </div>

                <x-primary-button>Opslaan</x-primary-button>
            </form>
        @endauth
    </div>
</x-app-layout>
