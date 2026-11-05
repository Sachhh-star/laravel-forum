<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8 space-y-4">
        <h1 class="text-2xl font-semibold text-gray-900">Users</h1>

        @if (session('status'))
            <p class="text-green-700">{{ session('status') }}</p>
        @endif
        <x-input-error :messages="$errors->get('delete')" />

        <div class="bg-white shadow-sm rounded-lg divide-y divide-gray-100">
            @foreach ($users as $user)
                <div class="p-4 flex items-center justify-between gap-4">
                    <div>
                        <a href="{{ route('users.show', $user) }}" class="font-semibold text-gray-900 hover:underline">{{ $user->username }}</a>
                        <span class="ms-2 text-sm text-gray-500">{{ $user->role }}</span>
                    </div>

                    @unless ($user->is(Auth::user()))
                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Deze user en alle berichten van deze user verwijderen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm underline text-red-600">Verwijderen</button>
                        </form>
                    @endunless
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
