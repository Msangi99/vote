<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit League</title>
    <!-- ✅ Fixed broken CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <x-sidebar class="w-64 bg-gray-900 text-white shadow-xl hidden md:block" />

        <!-- Main Content -->
        <div class="flex-1">

            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-4xl mx-auto px-6 py-4">
                    <h1 class="text-2xl font-extrabold text-gray-800">Edit League</h1>
                </div>
            </header>

            <!-- Main Padding Wrapper -->
            <main class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

                <!-- Form Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <p class="text-sm text-gray-600">Update the league details below.</p>
                    </div>

                    <div class="p-6">
                        <form action="{{ route('leagues.update', $league->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- League Name Field -->
                            <div class="mb-6">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    League Name
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    value="{{ old('name', $league->name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 sm:text-sm"
                                    placeholder="e.g. English Premier League"
                                    required
                                >
                                <!-- Optional: Display validation error -->
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col-reverse sm:flex-row sm:justify-between gap-3">
                                <a href="{{ route('leagues.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg shadow-sm transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 rounded-lg shadow-sm hover:shadow transition duration-200"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Update League
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </main>
        </div>
    </div>
</body>
</html>