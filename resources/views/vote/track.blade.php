<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Votes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }

        /* Style DataTables controls to match Tailwind */
        .dataTables_wrapper .dataTables_filter input {
            @apply border border-gray-300 rounded-lg px-4 py-2 text-sm w-auto;
        }
        .dataTables_wrapper .dataTables_length select {
            @apply border border-gray-300 rounded-lg px-3 py-2 text-sm;
        }
        .dataTables_wrapper .dataTables_info {
            @apply text-sm text-gray-600;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            @apply px-3 py-1 rounded mx-0.5 text-sm border border-gray-300;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-blue-600 text-white border-blue-600;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            @apply bg-blue-50 border-blue-500;
        }

        /* Column filter inputs */
        .column-filter {
            @apply mt-2 block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans flex min-h-screen">

    <!-- Sidebar -->
    <x-sidebar />

    <!-- Main Content -->
    <div class="flex-1 p-6 md:p-10">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-8">Track Player Votes</h1>

        <!-- Filter Form -->
        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
            <form action="{{ route('votes.track') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div>
                    <label for="start_date_filter" class="block text-sm font-semibold text-gray-700 mb-2">From Date</label>
                    <input type="date"
                           name="start_date"
                           id="start_date_filter"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 ease-in-out"
                           value="{{ request('start_date') }}">
                </div>
                <div>
                    <label for="end_date_filter" class="block text-sm font-semibold text-gray-700 mb-2">To Date</label>
                    <input type="date"
                           name="end_date"
                           id="end_date_filter"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 ease-in-out"
                           value="{{ request('end_date') }}">
                </div>
                <div>
                    <label for="league_filter" class="block text-sm font-semibold text-gray-700 mb-2">Filter by League</label>
                    <select name="league_id"
                            id="league_filter"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 ease-in-out">
                        <option value="">All Leagues</option>
                        @foreach ($leagues as $league)
                            <option value="{{ $league->id }}" {{ request('league_id') == $league->id ? 'selected' : '' }}>
                                {{ $league->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="team_filter" class="block text-sm font-semibold text-gray-700 mb-2">Filter by Team</label>
                    <select name="team_id"
                            id="team_filter"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 ease-in-out"
                            {{ request('league_id') ? '' : 'disabled' }}>
                        <option value="">All Teams</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" {{ request('team_id') == $team->id ? 'selected' : '' }}>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-4">
                    <button type="submit"
                            class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        🔍 Apply
                    </button>
                    <a href="{{ route('votes.track') }}"
                       class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                        🧹 Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabs & Tables -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">

                <!-- Tab Navigation -->
                <div class="flex border-b border-gray-200 mb-6">
                    @if (request('start_date') || request('end_date'))
                        @foreach ($categories as $category)
                            <button
                                class="py-3 px-5 text-sm font-medium text-gray-600 hover:text-blue-600 focus:outline-none transition {{ $loop->first ? 'border-b-2 border-blue-500 text-blue-500' : '' }}"
                                onclick="showCategory('{{ $category->id }}')"
                                id="tab-{{ $category->id }}"
                            >
                                {{ $category->name }}
                            </button>
                        @endforeach
                    @else
                        <p class="text-gray-600 py-3 px-5">Please apply a date filter to view categories.</p>
                    @endif
                </div>

                <!-- Tab Content -->
                @if (!request('start_date') && !request('end_date'))
                    <div class="py-12 text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2zm3-7h.01M12 15h.01"></path>
                        </svg>
                        <p class="text-lg text-gray-600">Please select a date range and apply the filter to view vote data.</p>
                    </div>
                @elseif ($categorizedVotes->isEmpty())
                    <div class="py-12 text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-lg text-gray-600">No player votes recorded yet or matching your filter.</p>
                    </div>
                @else
                    @foreach ($categorizedVotes as $category)
                        <div id="category-{{ $category->id }}" class="tab-content {{ $loop->first ? 'active' : '' }}">
                            <h2 class="text-xl font-semibold mb-4 pb-2 border-b border-gray-200">{{ $category->name }} Votes</h2>

                            @if ($category->name == 'best coach')
                                @if ($category->coachVotes->isEmpty())
                                    <p class="text-gray-600 py-6">No votes for this category yet.</p>
                                @else
                                    <div class="overflow-x-auto">
                                        <table id="table-coach-{{ $category->id }}" class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Match Date</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Match</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Coach Name</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Votes</th>
                                                </tr>
                                            </thead>
                                            <tfoot class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 pt-4 pb-2"><input type="text" placeholder="Filter Date" class="column-filter"></th>
                                                    <th class="px-6 pt-4 pb-2"><input type="text" placeholder="Filter Match" class="column-filter"></th>
                                                    <th class="px-6 pt-4 pb-2"><input type="text" placeholder="Filter Coach" class="column-filter"></th>
                                                    <th class="px-6 pt-4 pb-2"><input type="text" placeholder="Filter Votes" class="column-filter"></th>
                                                </tr>
                                            </tfoot>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach ($category->coachVotes as $index => $coachVote)
                                                    <tr class="{{ $index === 0 && $coachVote->total_votes > 0 ? 'bg-green-100' : '' }}">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            {{ \Carbon\Carbon::parse($coachVote->match_date)->format('Y-m-d') }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ $coachVote->match->homeTeam->name ?? 'N/A' }} vs {{ $coachVote->match->awayTeam->name ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 flex items-center">
                                                            {{ $coachVote->coach_name }}
                                                            @if ($index === 0 && $coachVote->total_votes > 0)
                                                                <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                                            {{ $coachVote->total_votes }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            @else
                                @if ($category->playerVotes->isEmpty())
                                    <p class="text-gray-600 py-6">No votes for this category yet.</p>
                                @else
                                    <div class="overflow-x-auto">
                                        <table id="table-player-{{ $category->id }}" class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Match Date</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Match</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Player</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Votes</th>
                                                </tr>
                                            </thead>
                                            <tfoot class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 pt-4 pb-2"><input type="text" placeholder="Filter Date" class="column-filter"></th>
                                                    <th class="px-6 pt-4 pb-2"><input type="text" placeholder="Filter Match" class="column-filter"></th>
                                                    <th class="px-6 pt-4 pb-2"><input type="text" placeholder="Filter Player" class="column-filter"></th>
                                                    <th class="px-6 pt-4 pb-2"><input type="text" placeholder="Filter Votes" class="column-filter"></th>
                                                </tr>
                                            </tfoot>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach ($category->playerVotes as $index => $playerVote)
                                                    <tr class="{{ $index === 0 && $playerVote->total_votes > 0 ? 'bg-green-100' : '' }}">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            {{ \Carbon\Carbon::parse($playerVote->match_date)->format('Y-m-d') }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ $playerVote->match->homeTeam->name ?? 'N/A' }} vs {{ $playerVote->match->awayTeam->name ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 flex items-center">
                                                            {{ $playerVote->player->name ?? 'N/A' }}
                                                            @if ($index === 0)
                                                                 <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                                            {{ $playerVote->total_votes }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        let tables = [];

        function initDataTable(tableId, columnCount) {
            let table = $(`#${tableId}`).DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "pageLength": 10,
                "language": {
                    "search": "🔍 Search:",
                    "paginate": {
                        "previous": "← Previous",
                        "next": "Next →"
                    }
                }
            });

            // Add column filters (skip none — all columns filterable here)
            for (let i = 0; i < columnCount; i++) {
                $(`#${tableId} tfoot th`).eq(i).html(
                    `<input type="text" placeholder="Filter ${$(`#${tableId} thead th`).eq(i).text()}" class="column-filter" />`
                );
            }

            // Apply column filters
            table.columns().every(function(index) {
                var that = this;
                $('input', this.footer()).on('keyup change clear', function() {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });

            tables.push(table);
            return table;
        }

        function showCategory(categoryId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tabContent => {
                tabContent.classList.remove('active');
            });

            // Deactivate all tabs
            document.querySelectorAll('.flex.border-b button').forEach(tabButton => {
                tabButton.classList.remove('border-b-2', 'border-blue-500', 'text-blue-500');
            });

            // Show selected tab
            document.getElementById('category-' + categoryId).classList.add('active');
            document.getElementById('tab-' + categoryId).classList.add('border-b-2', 'border-blue-500', 'text-blue-500');

            // Initialize DataTable if not already done
            let coachTableId = `table-coach-${categoryId}`;
            let playerTableId = `table-player-${categoryId}`;

            if (document.getElementById(coachTableId) && !$.fn.dataTable.isDataTable(`#${coachTableId}`)) {
                initDataTable(coachTableId, 4);
            }

            if (document.getElementById(playerTableId) && !$.fn.dataTable.isDataTable(`#${playerTableId}`)) {
                initDataTable(playerTableId, 4);
            }
        }

        // Initialize first tab on load
        document.addEventListener('DOMContentLoaded', () => {
            const firstTab = document.querySelector('.flex.border-b button');
            if (firstTab) {
                setTimeout(() => {
                    firstTab.click();
                }, 100);
            }

            const leagueFilter = document.getElementById('league_filter');
            const teamFilter = document.getElementById('team_filter');
            const initialLeagueId = leagueFilter.value;
            const initialTeamId = teamFilter.value; // Capture initial team_id

            if (initialLeagueId) {
                fetchTeamsForFilter(initialLeagueId, initialTeamId);
            }

            leagueFilter.addEventListener('change', function () {
                const leagueId = this.value;
                teamFilter.innerHTML = '<option value="">All Teams</option>';
                teamFilter.disabled = true;

                if (leagueId) {
                    fetchTeamsForFilter(leagueId);
                }
            });

            function fetchTeamsForFilter(leagueId, selectedTeamId = null) {
                fetch(`/leagues/${leagueId}/teams`)
                    .then(response => response.json())
                    .then(teams => {
                        teams.forEach(team => {
                            const option = document.createElement('option');
                            option.value = team.id;
                            option.textContent = team.name;
                            if (selectedTeamId && team.id == selectedTeamId) {
                                option.selected = true;
                            }
                            teamFilter.appendChild(option);
                        });
                        teamFilter.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching teams for filter:', error);
                        alert('Failed to load teams for filter. Please try again.');
                    });
            }
        });
    </script>
</body>
</html>
