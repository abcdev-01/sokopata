<header class="bg-white shadow-sm border-b">
    <div class="px-6 py-4 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold">SokoPata Dashboard</h2>
            <p class="text-sm text-gray-500">Manage your marketplace activity</p>
        </div>

        <div class="flex items-center gap-4">
            @auth
                <span class="text-sm text-gray-600">
                    {{ auth()->user()->name }}
                </span>
            @endauth

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded bg-red-500 text-white text-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>