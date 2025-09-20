<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leagues</title>
    <!-- ✅ Fixed CDN links -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        /* Style DataTables to match Tailwind */
        #leaguesTable_wrapper {
            @apply font-sans;
        }
        #leaguesTable thead th {
            @apply bg-gray-50 text-gray-600 text-left text-xs font-semibold uppercase tracking-wider border-b border-gray-200;
        }
        #leaguesTable tbody tr {
            @apply hover:bg-gray-50 transition-colors duration-150;
        }
        #leaguesTable tbody td {
            @apply py-3 px-4 border-b border-gray-100 text-sm text-gray-700;
        }
        #leaguesTable_paginate .paginate_button {
            @apply px-3 py-1 mx-0.5 rounded border border-gray-300 text-sm text-gray-700 hover:bg-gray-100 hover:border-gray-400;
        }
        #leaguesTable_paginate .paginate_button.current {
            @apply bg-indigo-600 text-white border-indigo-600;
        }
        .dataTables_filter input {
            @apply ml-2 p-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500;
        }
        .dataTables_length select {
            @apply p-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500;
        }
        .dataTables_info {
            @apply text-sm text-gray-600;
        }
    </style>
</head>
<body class="bg-gray-50">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <x-sidebar class="w-64 bg-gray-900 text-white shadow-xl hidden md:block" />

        <!-- Main Content -->
        <div class="flex-1">

            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                    <h1 class="text-2xl font-extrabold text-gray-800">Leagues</h1>
                    <a href="{{ route('leagues.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium py-2.5 px-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span>Add New League</span>
                    </a>
                </div>
            </header>

            <!-- Main Padding Wrapper -->
            <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

                <!-- Success Alert (Conditional) -->
                @if (session('success'))
                    <div class="mb-8 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded-lg shadow-sm flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Leagues Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-800">Manage Leagues</h2>
                    </div>

                    <div class="p-6">
                        <table id="leaguesTable" class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                                    <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                    <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody> 
                                @foreach ($leagues as $league)
                                    <tr>
                                        <td>{{ $league->id }}</td>
                                        <td>{{ $league->name }}</td>
                                        <td class="space-x-2">
                                            <a href="{{ route('leagues.edit', $league->id) }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded-md shadow-sm transition duration-200">
                                                Edit
                                            </a>
                                            <form action="{{ route('leagues.destroy', $league->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-md shadow-sm transition duration-200" onclick="return confirm('Are you sure?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#leaguesTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "pageLength": 5, // Adjust as needed
                "columnDefs": [
                    { "orderable": false, "targets": 2 } // Disable sorting on Actions
                ],
                "language": {
                    "search": "🔍 Search:",
                    "lengthMenu": "Show _MENU_ entries per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ leagues",
                    "paginate": {
                        "previous": "‹",
                        "next": "›"
                    }
                }
            });
        });
    </script>
</body>
</html>