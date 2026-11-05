<nav class="bg-white border-b border-gray-200">
    <div class="max-w-4xl mx-auto px-4 py-4 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-6">
            <a href="{{ route('home') }}" class="font-semibold text-lg text-gray-900">Software Development Forum</a>

            @auth
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Users</a>
                @endif
            @endauth
        </div>

        <div class="flex items-center gap-4 text-sm">
            @auth
                <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-gray-900">{{ Auth::user()->username }}</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-gray-900">Uitloggen</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Inloggen</a>
                <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-900">Registreren</a>
            @endauth
        </div>
    </div>
</nav>
