<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Player</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans flex min-h-screen">

    <!-- Sidebar -->
    <x-sidebar />

    <!-- Main Content -->
    <div class="flex-1 p-6 md:p-10">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <h1 class="text-3xl font-extrabold text-gray-800">Edit Player</h1>
            <a href="{{ route('players.index') }}" 
               class="mt-4 md:mt-0 inline-flex items-center px-5 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                ← Back to Players
            </a>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="mb-8 p-5 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-medium text-red-800 mb-2">Please correct the following errors:</h3>
                        <ul class="list-disc list-inside text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-8">
                <form action="{{ route('players.update', $player->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Player Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Player Name</label>
                        <input type="text"
                               id="name"
                               name="name"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 ease-in-out"
                               value="{{ old('name', $player->name) }}"
                               required
                               autofocus
                               placeholder="e.g. Cristiano Ronaldo">
                    </div>

                    <!-- Team -->
                    <div class="mb-6">
                        <label for="team_id" class="block text-sm font-semibold text-gray-700 mb-2">Team</label>
                        <select name="team_id"
                                id="team_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 ease-in-out"
                                required>
                            <option value="">Select a Team</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team->id }}" {{ old('team_id', $player->team_id) == $team->id ? 'selected' : '' }}>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            ✏️ Update Player
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>