<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8 space-y-6">
        <a href="{{ route('users.index') }}" class="text-sm underline text-gray-600">Terug naar Users</a>

        @if (session('status'))
            <p class="text-green-700">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('users.update', $user) }}" class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            @csrf
            @method('PATCH')

            <h1 class="text-2xl font-semibold text-gray-900">{{ $user->username }}</h1>

            <p class="text-sm text-gray-500">
                Aangemaakt op {{ $user->created_at->format('d-m-Y H:i') }} · Laatst aangepast op {{ $user->updated_at->format('d-m-Y H:i') }}
            </p>

            <div>
                <x-input-label for="name" value="Naam" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="username" value="Gebruikersnaam" />
                <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" value="E-mail" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="role" value="Rol" />
                <select id="role" name="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="user" @selected(old('role', $user->role) === 'user')>user</option>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>admin</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <x-primary-button>Opslaan</x-primary-button>
        </form>
    </div>
</x-app-layout>
