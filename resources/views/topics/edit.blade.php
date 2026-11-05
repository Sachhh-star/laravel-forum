<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <form method="POST" action="{{ route('topics.update', [$thread, $topic]) }}" class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            @csrf
            @method('PATCH')

            <h1 class="text-2xl font-semibold text-gray-900">Topic aanpassen</h1>

            <div>
                <x-input-label for="title" value="Titel" />
                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $topic->title)" required />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="body" value="Tekst" />
                <textarea id="body" name="body" rows="6" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('body', $topic->body) }}</textarea>
                <x-input-error :messages="$errors->get('body')" class="mt-2" />
            </div>

            <x-primary-button>Opslaan</x-primary-button>
        </form>
    </div>
</x-app-layout>
