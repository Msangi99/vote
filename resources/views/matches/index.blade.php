<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matches</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* Style DataTables controls to match Tailwind */
        #matchesTable_wrapper .dataTables_filter input {
            @apply border border-gray-300 rounded-lg px-4 py-2 text-sm w-auto;
        }

        #matchesTable_wrapper .dataTables_length select {
            @apply border border-gray-300 rounded-lg px-3 py-2 text-sm;
        }

        #matchesTable_wrapper .dataTables_info {
            @apply text-sm text-gray-600;
        }

        #matchesTable_wrapper .dataTables_paginate .paginate_button {
            @apply px-3 py-1 rounded mx-0.5 text-sm border border-gray-300;
        }

        #matchesTable_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-blue-600 text-white border-blue-600;
        }

        #matchesTable_wrapper .dataTables_paginate .paginate_button:hover {
            @apply bg-blue-50 border-blue-500;
        }

        .column-filter {
            @apply mt-2 block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent;
        }

        /* Matchup styling */
        .matchup-cell {
            @apply font-medium;
        }

        .matchup-cell .home-team {
            @apply font-bold text-gray-900;
        }

        .matchup-cell .vs {
            @apply mx-2 text-gray-400;
        }

        .matchup-cell .away-team {
            @apply text-gray-700;
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="flex min-h-screen">

        <!-- Sidebar (Assuming Blade Component) -->
        <x-sidebar class="w-64 bg-white shadow-lg hidden md:block" />

        <!-- Main Content -->
        <div class="flex-1">

            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-6 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">⚽ Matches</h1>
                    <a href="{{ route('matches.create') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-plus mr-2"></i> Create New Match
                    </a>
                </div>
            </header>

            <!-- Main Padding Wrapper -->
            <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

                <!-- Success Alert -->
                @if (session('success'))
                    <div class="mb-8 p-5 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm flex items-start">
                        <svg class="w-6 h-6 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- League Filter Card -->
                <section class="mb-8 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">FilterWhere</h2>
                    <form action="{{ route('matches.index') }}" method="GET" class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div class="w-full sm:w-auto">
                            <label for="league_filter" class="block text-sm font-medium text-gray-700 mb-1">League</label>
                            <select id="league_filter" name="league_id"
                                class="form-select w-full sm:w-60 rounded-lg shadow-sm border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">All Leagues</option>
                                @foreach ($leagues as $league)
                                    <option value="{{ $league->id }}"
                                        {{ request('league_id') == $league->id ? 'selected' : '' }}>
                                        {{ $league->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 whitespace-nowrap">
                            <i class="fas fa-filter mr-2"></i> Apply Filter
                        </button>
                    </form>
                </section>

                <!-- Table Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Scheduled Matches</h2>
                    </div>
                    <div class="p-1">
                        <table id="matchesTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        ID</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Matchup</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        League</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Date & Time</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <th class="px-6 pt-4 pb-2">—</th>
                                    <th class="px-6 pt-4 pb-2">
                                        <input type="text" placeholder="Filter teams..." class="column-filter">
                                    </th>
                                    <th class="px-6 pt-4 pb-2">
                                        <input type="text" placeholder="Filter league" class="column-filter">
                                    </th>
                                    <th class="px-6 pt-4 pb-2">
                                        <input type="text" placeholder="Filter date" class="column-filter">
                                    </th>
                                    <th class="px-6 pt-4 pb-2">—</th>
                                </tr>
                            </tfoot>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($matches as $match)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150 group">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            #{{ $match->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm matchup-cell">
                                            <span class="home-team">{{ $match->homeTeam->name ?? '—' }}</span>
                                            <span class="vs">vs</span>
                                            <span class="away-team">{{ $match->awayTeam->name ?? '—' }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $match->homeTeam->league->name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ \Carbon\Carbon::parse($match->match_date)->format('M j, Y g:i A') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('matches.edit', $match->id) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded shadow-sm hover:shadow transition duration-200 ease-in-out">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>
                                            <form action="{{ route('matches.destroy', $match->id) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Are you sure you want to delete this match?')"
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded shadow-sm hover:shadow transition duration-200 ease-in-out">
                                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-20 h-20 text-gray-300 mb-4" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.4 0-.78-.146-1.054-.402L8.4 18.294A2 2 0 017.211 16H2.79a2 2 0 01-1.789-2.894l3.5-7A2 2 0 015.236 5h4.018a2 2 0 011.789.894l3.5 7a2 2 0 01.266 1.106zM9 11V9a2 2 0 012-2h2a2 2 0 012 2v2m-6 0h6m-6 0a2 2 0 00-2 2v4a2 2 0 002 2h6a2 2 0 002-2v-4a2 2 0 00-2-2h-6z">
                                                    </path>
                                                </svg>
                                                <p class="text-lg font-medium text-gray-700">No matches scheduled</p>
                                                <p class="mt-1 text-gray-500">Create your first match to get started.</p>
                                                <a href="{{ route('matches.create') }}"
                                                    class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm hover:shadow transition duration-200">
                                                    <i class="fas fa-plus mr-2"></i> Create Match
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#matchesTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "pageLength": 10,
                "columnDefs": [
                    {
                        "orderable": false,
                        "targets": [4] // Only Actions column now (index 4)
                    }
                ],
                "language": {
                    "search": "🔍 Search All:",
                    "paginate": {
                        "previous": "← Previous",
                        "next": "Next →"
                    },
                    "info": "Showing _START_ to _END_ of _TOTAL_ matches",
                    "emptyTable": "No matches available",
                    "zeroRecords": "No matching records found"
                }
            });

            // Setup column filters — skip ID (0) and Actions (4)
            $('#matchesTable tfoot th').each(function (index) {
                if (index > 0 && index < 4) { // Matchup (1), League (2), Date (3)
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Filter ' + title +
                        '" class="column-filter" />');
                }
            });

            // Apply column filters
            table.columns().every(function (index) {
                if (index > 0 && index < 4) { // Only filter Matchup, League, Date
                    var that = this;
                    $('input', this.footer()).on('keyup change clear', function () {
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