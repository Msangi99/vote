<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        /* Override DataTables default styles to match Tailwind */
        #teamsTable_wrapper .dataTables_filter input {
            @apply border border-gray-300 rounded-lg px-4 py-2 text-sm w-auto;
        }
        #teamsTable_wrapper .dataTables_length select {
            @apply border border-gray-300 rounded-lg px-3 py-2 text-sm;
        }
        #teamsTable_wrapper .dataTables_info {
            @apply text-sm text-gray-600;
        }
        #teamsTable_wrapper .dataTables_paginate .paginate_button {
            @apply px-3 py-1 rounded mx-0.5 text-sm;
        }
        #teamsTable_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-blue-600 text-white;
        }
        #teamsTable_wrapper .dataTables_paginate .paginate_button:hover {
            @apply bg-blue-100;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans flex min-h-screen">

    <!-- Sidebar -->
    <x-sidebar />

    <!-- Main Content -->
    <div class="flex-1 p-6 md:p-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <h1 class="text-3xl font-extrabold text-gray-800">Teams</h1>
            <a href="{{ route('teams.create') }}" 
               class="mt-4 md:mt-0 inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                ➕ Create New Team
            </a>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="mb-8 p-5 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm flex items-start">
                <svg class="w-6 h-6 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <table id="teamsTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">League</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Coach</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($teams as $team)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $team->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $team->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $team->league->name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $team->coach_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('teams.edit', $team->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded shadow-sm hover:shadow transition duration-200 ease-in-out">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('teams.destroy', $team->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to delete this team?')"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded shadow-sm hover:shadow transition duration-200 ease-in-out">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#teamsTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "pageLength": 10,
                "columnDefs": [
                    { "orderable": false, "targets": 4 } // Disable sorting on Actions
                ],
                "language": {
                    "search": "🔍 Search:",
                    "paginate": {
                        "previous": "← Previous",
                        "next": "Next →"
                    }
                }
            });
        });
    </script>
</body>
</html>