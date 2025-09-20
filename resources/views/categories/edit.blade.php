<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <!-- ✅ Updated to Tailwind v3 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .focus-ring:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex">

    <!-- Sidebar -->
    <x-sidebar />

    <!-- Main Content -->
    <main class="flex-1 p-6 lg:p-10">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800">Edit Category</h1>
            <a href="{{ route('categories.index') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow transition focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Categories
            </a>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-md" role="alert">
                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="font-bold">Please fix the following errors:</h3>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg w-full max-w-md mx-auto">
            <form action="{{ route('categories.update', $category->id) }}" method="POST" id="category-form">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Category Name
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $category->name) }}"
                        required
                        autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition focus:outline-none focus-ring"
                        placeholder="Update category name"
                    >
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('categories.index') }}"
                        class="px-5 py-2.5 text-gray-700 bg-gray-200 hover:bg-gray-300 font-medium rounded-lg transition focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 focus:outline-none">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 focus:outline-none"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Optional: Loading State -->
    <script>
        document.getElementById('category-form').addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Updating...
            `;
        });
    </script>
</body>
</html>