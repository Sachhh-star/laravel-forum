<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <form method="POST" action="{{ route('replies.update', [$thread, $topic, $reply]) }}" class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            @csrf
            @method('PATCH')

            <h1 class="text-2xl font-semibold text-gray-900">Reply aanpassen</h1>

            <div>
                <x-input-label for="body" value="Tekst" />
                <textarea id="body" name="body" rows="5" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('body', $reply->body) }}</textarea>
                <x-input-error :messages="$errors->get('body')" class="mt-2" />
            </div>

            <x-primary-button>Opslaan</x-primary-button>
        </form>
    </div>
</x-app-layout>
