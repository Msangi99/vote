<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Players</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        /* Style DataTables controls to match Tailwind */
        #playersTable_wrapper .dataTables_filter input {
            @apply border border-gray-300 rounded-lg px-4 py-2 text-sm w-auto;
        }
        #playersTable_wrapper .dataTables_length select {
            @apply border border-gray-300 rounded-lg px-3 py-2 text-sm;
        }
        #playersTable_wrapper .dataTables_info {
            @apply text-sm text-gray-600;
        }
        #playersTable_wrapper .dataTables_paginate .paginate_button {
            @apply px-3 py-1 rounded mx-0.5 text-sm border border-gray-300;
        }
        #playersTable_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-blue-600 text-white border-blue-600;
        }
        #playersTable_wrapper .dataTables_paginate .paginate_button:hover {
            @apply bg-blue-50 border-blue-500;
        }

        /* Column filter inputs */
        .column-filter {
            @apply mt-2 block w-full px-3 py-2 h-8 text-sm border border-gray-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent;
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
            <h1 class="text-3xl font-extrabold text-gray-800">Players</h1>
            <a href="{{ route('players.create') }}" 
               class="mt-4 md:mt-0 inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                ➕ Add New Player
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
                <table id="playersTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Team</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <th class="px-6 pt-4 pb-2">
                                <input type="text" placeholder="Filter ID" class="column-filter">
                            </th>
                            <th class="px-6 pt-4 pb-2">
                                <input type="text" placeholder="Filter Name" class="column-filter">
                            </th>
                            <th class="px-6 pt-4 pb-2">
                                <input type="text" placeholder="Filter Team" class="column-filter">
                            </th>
                            <th class="px-6 pt-4 pb-2">
                                <!-- No filter for Actions -->
                            </th>
                        </tr>
                    </tfoot>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($players as $player)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $player->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $player->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $player->team->name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('players.edit', $player->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded shadow-sm hover:shadow transition duration-200 ease-in-out">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('players.destroy', $player->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to delete this player?')"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded shadow-sm hover:shadow transition duration-200 ease-in-out">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No players found</p>
                                    <p class="mt-1">Add your first player to get started.</p>
                                    <a href="{{ route('players.create') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">➕ Add Player</a>
                                </td>
                            </tr>
                        @endforelse
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
            // Initialize DataTable
            var table = $('#playersTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "pageLength": 10,
                "columnDefs": [
                    { "orderable": false, "targets": 3 } // Disable sorting on Actions
                ],
                "language": {
                    "search": "🔍 Global Search:",
                    "paginate": {
                        "previous": "← Previous",
                        "next": "Next →"
                    }
                }
            });

            // Add column filters
            $('#playersTable tfoot th').each(function(index) {
                if (index < 3) { // Only for first 3 columns (ID, Name, Team)
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Filter '+title+'" class="column-filter" />');
                }
            });

            // Apply column filters
            table.columns().every(function(index) {
                if (index < 3) { // Only for filterable columns
                    var that = this;
                    $('input', this.footer()).on('keyup change clear', function() {
                        if (that.search() !== this.value) {
                            that.search(this.value).draw();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>