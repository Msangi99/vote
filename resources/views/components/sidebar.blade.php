<!-- Sidebar -->
<div class="w-64 bg-gray-900 text-white flex flex-col shadow-2xl fixed top-0 left-0 h-full z-50">
    <!-- Logo / Brand -->
    <div class="p-5 border-b border-gray-700">
        <img src="{{ asset('WhatsApp Image 2025-09-16 at 19.31.12_62e45b1b.jpg') }}" alt="" class="h-30 w-full">
    </div>

    <div class="p-5 border-b border-gray-700">
        <div class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
            <h1
                class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">
                Admin Panel
            </h1>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-6 space-y-2 overflow-y-auto">

        @php
            $currentRoute = request()->route()->getName(); // Get current route name
        @endphp

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group
           {{ $currentRoute === 'dashboard' ? 'bg-indigo-800 text-white border-l-4 border-indigo-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 mr-3 transition-colors
               {{ $currentRoute === 'dashboard' ? 'text-indigo-300' : 'text-gray-400 group-hover:text-indigo-300' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z" />
            </svg>
            Dashboard
        </a>

        <!-- Leagues -->
        @can('view-leagues')
            <a href="/leagues"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group
            {{ request()->is('leagues*') ? 'bg-green-800 text-white border-l-4 border-green-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-3 transition-colors
                {{ request()->is('leagues*') ? 'text-green-300' : 'text-gray-400 group-hover:text-green-300' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Leagues
            </a>
        @endcan

        <!-- Categories -->
        @can('view-categories')
            <a href="/categories"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group
            {{ request()->is('categories*') ? 'bg-yellow-800 text-white border-l-4 border-yellow-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-3 transition-colors
                {{ request()->is('categories*') ? 'text-yellow-300' : 'text-gray-400 group-hover:text-yellow-300' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                Categories
            </a>
        @endcan

        <!-- Teams -->
        @can('view-teams')
            <a href="/teams"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group
            {{ request()->is('teams*') ? 'bg-blue-800 text-white border-l-4 border-blue-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-3 transition-colors
                {{ request()->is('teams*') ? 'text-blue-300' : 'text-gray-400 group-hover:text-blue-300' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Teams
            </a>
        @endcan

        <!-- Players -->
        @can('view-players')
            <a href="/players"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group
            {{ request()->is('players*') ? 'bg-purple-800 text-white border-l-4 border-purple-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-3 transition-colors
                {{ request()->is('players*') ? 'text-purple-300' : 'text-gray-400 group-hover:text-purple-300' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Players
            </a>
        @endcan

        <!-- Matches -->
        @can('view-matches')
            <a href="/matches"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group
            {{ request()->is('matches*') ? 'bg-red-800 text-white border-l-4 border-red-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-3 transition-colors
                {{ request()->is('matches*') ? 'text-red-300' : 'text-gray-400 group-hover:text-red-300' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.165-1.275-.47-1.827m-8.53 1.827V20M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.165-1.275.47-1.827m0 0A9.002 9.002 0 0112 5a9.002 9.002 0 014.53 11.173M12 12h.01" />
                </svg>
                Matches
            </a>
        @endcan

        <!-- Track Votes -->
        @can('view-track-votes')
            <a href="{{ route('votes.track') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group
            {{ $currentRoute === 'votes.track' ? 'bg-pink-800 text-white border-l-4 border-pink-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-3 transition-colors
                {{ $currentRoute === 'votes.track' ? 'text-pink-300' : 'text-gray-400 group-hover:text-pink-300' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Track Votes
            </a>
        @endcan

        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group text-gray-300 hover:bg-gray-800 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 transition-colors text-gray-400 group-hover:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v-1a3 3 0 00-3-3H6a3 3 0 00-3 3v1m8 12H9a2 2 0 01-2-2v-1c0-.962.307-1.846.836-2.574m9.574 2.574c.216.381.344.831.344 1.32 0 1.079-.86 1.939-1.939 1.939H10.585c.055.229.09.469.09.711V19a2 2 0 01-2 2h-.092c-.229 0-.469-.035-.711-.092M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Logout
            </button>
        </form>

    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-700">
        <div class="flex items-center text-sm text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 14a3.001 3.001 0 002.83 2M12 20.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" />
            </svg>
            Admin User
        </div>
    </div>
</div>
