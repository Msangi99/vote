<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- ✅ Fixed broken CDN link -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        .stat-card {
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-50">

    <div class="flex min-h-screen">

        <!-- Sidebar Placeholder -->
        <x-sidebar class="w-64 bg-white shadow-lg hidden md:block" />

        <!-- Main Content -->
        <div class="flex-1">

            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                    <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Dashboard</h1>
                    @auth
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-700 font-medium">Hello, <span class="font-semibold text-indigo-600">{{ Auth::user()->name }}!</span></span>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-5 rounded-lg shadow-sm hover:shadow transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-5 rounded-lg shadow-sm hover:shadow transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                            Login
                        </a>
                    @endauth
                </div>
            </header>

            <!-- Main Padding Wrapper -->
            <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

                <!-- Date Filter -->
                <section class="mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Filter Data by Date</h2>
                        <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-center gap-4">
                            <div class="flex items-center">
                                <input type="radio" id="filter-today" name="date_range" value="today" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" {{ request('date_range', 'all') == 'today' ? 'checked' : '' }}>
                                <label for="filter-today" class="ml-2 text-sm text-gray-700">Today</label>
                            </div>
                            {{-- <div class="flex items-center">
                                <input type="radio" id="filter-week" name="date_range" value="week" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" {{ request('date_range') == 'week' ? 'checked' : '' }}>
                                <label for="filter-week" class="ml-2 text-sm text-gray-700">This Week</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="filter-month" name="date_range" value="month" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" {{ request('date_range') == 'month' ? 'checked' : '' }}>
                                <label for="filter-month" class="ml-2 text-sm text-gray-700">This Month</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="filter-year" name="date_range" value="year" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" {{ request('date_range') == 'year' ? 'checked' : '' }}>
                                <label for="filter-year" class="ml-2 text-sm text-gray-700">This Year</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="filter-all" name="date_range" value="all" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" {{ request('date_range') == 'all' ? 'checked' : '' }}>
                                <label for="filter-all" class="ml-2 text-sm text-gray-700">All Time</label>
                            </div> --}}

                            <div class="flex items-center gap-2 ml-auto">
                                <label for="start_date" class="text-sm text-gray-700">From:</label>
                                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="form-input rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="flex items-center gap-2">
                                <label for="end_date" class="text-sm text-gray-700">To:</label>
                                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="form-input rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-5 rounded-lg shadow-sm hover:shadow transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                                Apply Filter
                            </button>
                        </form>
                    </div>
                </section>

                <!-- Stats Overview -->
                <section class="mb-10">
                    <h2 class="text-lg font-semibold text-gray-600 uppercase tracking-wide mb-4">Overview</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5">
                        <!-- Total Matches -->
                        <div class="stat-card bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-sm border border-blue-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-blue-800">Total Matches</p>
                                    <p class="mt-1 text-3xl font-extrabold text-blue-600">{{ $totalMatches }}</p>
                                </div>
                                <div class="p-3 bg-blue-500 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.165-1.275-.47-1.827m-8.53 1.827V20M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.165-1.275.47-1.827m0 0A9.002 9.002 0 0112 5a9.002 9.002 0 014.53 11.173M12 12h.01" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Total Leagues -->
                        <div class="stat-card bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-sm border border-green-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-green-800">Total Leagues</p>
                                    <p class="mt-1 text-3xl font-extrabold text-green-600">{{ $totalLeagues }}</p>
                                </div>
                                <div class="p-3 bg-green-500 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Total Teams -->
                        <div class="stat-card bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-sm border border-purple-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-purple-800">Total Teams</p>
                                    <p class="mt-1 text-3xl font-extrabold text-purple-600">{{ $totalTeams }}</p>
                                </div>
                                <div class="p-3 bg-purple-500 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Total Players -->
                        <div class="stat-card bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-xl shadow-sm border border-yellow-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-yellow-800">Total Players</p>
                                    <p class="mt-1 text-3xl font-extrabold text-yellow-600">{{ $totalPlayers }}</p>
                                </div>
                                <div class="p-3 bg-yellow-500 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Total Votes -->
                        <div class="stat-card bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl shadow-sm border border-red-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-red-800">Total Votes</p>
                                    <p class="mt-1 text-3xl font-extrabold text-red-600">{{ $totalVotes }}</p>
                                </div>
                                <div class="p-3 bg-red-500 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Total Monetization -->
                        <div class="stat-card bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-xl shadow-sm border border-indigo-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-indigo-800">Total Vote Value</p>
                                    <p class="mt-1 text-2xl font-extrabold text-indigo-600">Tsh: {{ number_format($totalMonetization, 2) }}</p>
                                </div>
                                <div class="p-3 bg-indigo-500 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 12v-1m-9-8h1.5M3 12h1.5m14.25 0H21m-4.5 0H19M12 21c-3.866 0-7-3.134-7-7s3.134-7 7-7 7 3.134 7 7-3.134 7-7 7z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Leagues Overview -->
                <section class="mb-10">
                    <h2 class="text-lg font-semibold text-gray-600 uppercase tracking-wide mb-4">Leagues</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                        @forelse ($leagues as $league)
                            <div class="league-card bg-gradient-to-r from-indigo-100 to-purple-300 p-6 rounded-xl shadow-sm border border-gray-100 cursor-pointer hover:shadow-md hover:border-indigo-300 transition duration-200 ease-in-out" data-league-id="{{ $league->id }}">
                                <h3 class="text-xl font-bold text-gray-800">{{ $league->name }}</h3>
                                <p class="text-gray-600 text-sm mt-1">Total Teams: <span class="font-semibold">{{ $league->teams_count }}</span></p>
                            </div>
                        @empty
                            <p class="text-gray-500">No leagues available.</p>
                        @endforelse
                    </div>
                </section>

                <!-- Matches by League Table -->
                <section id="matches-by-league-section" class="mb-10 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800">Matches for Selected League</h2>
                    </div>
                    <div class="p-6">
                        <div id="matches-by-league-content">
                            <p class="text-center py-10 text-gray-500 font-medium">Click on a league card above to see its matches.</p>
                        </div>
                    </div>
                </section>

                <!-- Upcoming Matches -->
                <section class="mb-10 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800">Upcoming Matches</h2>
                    </div>
                    <div class="p-6">
                        @if ($upcomingMatches->isEmpty())
                            <div class="text-center py-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.466-.972-6.08-2.585m10.16 0A7.962 7.962 0 0112 9c-2.34 0-4.466.972-6.08 2.585M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="text-gray-500 font-medium">No upcoming matches scheduled.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Home Team</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Away Team</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($upcomingMatches as $match)
                                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                                    {{ \Carbon\Carbon::parse($match->match_date)->format('M d, Y • H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $match->homeTeam->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $match->awayTeam->name }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </section>

                <!-- Additional Content -->
                <section class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Additional Dashboard Content</h2>
                    <p class="text-gray-600">This area can be used for charts, graphs, or other detailed statistics. Below is a graph of votes over time.</p>
                    <div class="mt-6 bg-gray-50 rounded-lg p-4">
                        <canvas id="votesChart" class="w-full h-64"></canvas>
                    </div>
                </section>

            </main>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to generate random colors for pie charts
            function generateRandomColors(num) {
                const colors = [];
                for (let i = 0; i < num; i++) {
                    const r = Math.floor(Math.random() * 255);
                    const g = Math.floor(Math.random() * 255);
                    const b = Math.floor(Math.random() * 255);
                    colors.push(`rgba(${r}, ${g}, ${b}, 0.7)`);
                }
                return colors;
            }

            // Chart.js initialization for Votes Over Time (existing chart)
            const voteData = @json($voteData);
            const dates = voteData.map(data => data.date);
            const counts = voteData.map(data => data.count);

            const ctxVotesChart = document.getElementById('votesChart').getContext('2d');
            new Chart(ctxVotesChart, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Total Votes',
                        data: counts,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.2)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Votes Over Time'
                        }
                    },
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                tooltipFormat: 'MMM D, YYYY',
                                displayFormats: {
                                    day: 'MMM D'
                                }
                            },
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Votes'
                            },
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

            // // Votes by Category Pie Chart
            // const votesByCategoryData = 0;
            // const categoryLabels = votesByCategoryData.map(data => data.category_name);
            // const categoryCounts = votesByCategoryData.map(data => data.vote_count);
            // const categoryColors = generateRandomColors(categoryLabels.length);

            // const ctxVotesByCategory = document.getElementById('votesByCategoryChart').getContext('2d');
            // new Chart(ctxVotesByCategory, {
            //     type: 'pie',
            //     data: {
            //         labels: categoryLabels,
            //         datasets: [{
            //             label: 'Votes by Category',
            //             data: categoryCounts,
            //             backgroundColor: categoryColors,
            //             hoverOffset: 4
            //         }]
            //     },
            //     options: {
            //         responsive: true,
            //         maintainAspectRatio: false,
            //         plugins: {
            //             legend: {
            //                 position: 'right',
            //             },
            //             title: {
            //                 display: false,
            //                 text: 'Votes by Category'
            //             }
            //         }
            //     }
            // });

            // // Votes by Team Pie Chart
            // const votesByTeamData = 0;
            // const teamLabels = votesByTeamData.map(data => data.team_name);
            // const teamCounts = votesByTeamData.map(data => data.vote_count);
            // const teamColors = generateRandomColors(teamLabels.length);

            // const ctxVotesByTeam = document.getElementById('votesByTeamChart').getContext('2d');
            // new Chart(ctxVotesByTeam, {
            //     type: 'pie',
            //     data: {
            //         labels: teamLabels,
            //         datasets: [{
            //             label: 'Votes by Team',
            //             data: teamCounts,
            //             backgroundColor: teamColors,
            //             hoverOffset: 4
            //         }]
            //     },
            //     options: {
            //         responsive: true,
            //         maintainAspectRatio: false,
            //         plugins: {
            //             legend: {
            //                 position: 'right',
            //             },
            //             title: {
            //                 display: false,
            //                 text: 'Votes by Team'
            //             }
            //         }
            //     }
            // });

            // Monetization by Category Pie Chart
            // const monetizationByCategoryData = 0;
            // const monetizationLabels = monetizationByCategoryData.map(data => data.category_name);
            // const monetizationValues = monetizationByCategoryData.map(data => data.total_monetization);
            // const monetizationColors = generateRandomColors(monetizationLabels.length);

            // const ctxMonetizationByCategory = document.getElementById('monetizationByCategoryChart').getContext('2d');
            // new Chart(ctxMonetizationByCategory, {
            //     type: 'pie',
            //     data: {
            //         labels: monetizationLabels,
            //         datasets: [{
            //             label: 'Monetization by Category',
            //             data: monetizationValues,
            //             backgroundColor: monetizationColors,
            //             hoverOffset: 4
            //         }]
            //     },
            //     options: {
            //         responsive: true,
            //         maintainAspectRatio: false,
            //         plugins: {
            //             legend: {
            //                 position: 'right',
            //             },
            //             title: {
            //                 display: false,
            //                 text: 'Monetization by Category'
            //             }
            //         }
            //     }
            // });

            // League card click functionality
            const leagueCards = document.querySelectorAll('.league-card');
            const matchesByLeagueSection = document.getElementById('matches-by-league-section');
            const matchesByLeagueContent = document.getElementById('matches-by-league-content');

            leagueCards.forEach(card => {
                card.addEventListener('click', function() {
                    const leagueId = this.dataset.leagueId;
                    
                    // Remove active class from all cards
                    leagueCards.forEach(lc => lc.classList.remove('border-indigo-500', 'ring-2', 'ring-indigo-200'));
                    // Add active class to clicked card
                    this.classList.add('border-indigo-500', 'ring-2', 'ring-indigo-200');

                    const startDate = document.getElementById('start_date').value;
                    const endDate = document.getElementById('end_date').value;

                    let fetchUrl = `/leagues/${leagueId}/upcoming-matches`;
                    // The backend handles date filtering for upcoming matches, so no need to pass start_date/end_date here.
                    // If future requirements include filtering upcoming matches by a specific range,
                    // these parameters would need to be re-added and handled in the controller.

                    fetch(fetchUrl)
                        .then(response => response.json())
                        .then(matches => {
                            matchesByLeagueContent.innerHTML = ''; // Clear previous content
                            if (matches.length > 0) {
                                let tableHtml = `
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Home Team</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Away Team</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                `;
                                matches.forEach(match => {
                                    const matchDate = new Date(match.match_date).toLocaleString('en-US', {
                                        month: 'short', day: 'numeric', year: 'numeric',
                                        hour: 'numeric', minute: 'numeric', hour12: true
                                    });
                                    tableHtml += `
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">${matchDate}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${match.home_team.name}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${match.away_team.name}</td>
                                        </tr>
                                    `;
                                });
                                tableHtml += `
                                            </tbody>
                                        </table>
                                    </div>
                                `;
                                matchesByLeagueContent.innerHTML = tableHtml;
                            } else {
                                matchesByLeagueContent.innerHTML = `
                                    <div class="text-center py-10">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.466-.972-6.08-2.585m10.16 0A7.962 7.962 0 0112 9c-2.34 0-4.466.972-6.08 2.585M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <p class="text-gray-500 font-medium">No matches found for this league with the current date filter.</p>
                                    </div>
                                `;
                            }
                            matchesByLeagueSection.classList.remove('hidden'); // Show the section
                        })
                        .catch(error => {
                            console.error('Error fetching matches:', error);
                            matchesByLeagueContent.innerHTML = `<p class="text-red-500 text-center py-10">Failed to load matches. Please try again.</p>`;
                            matchesByLeagueSection.classList.remove('hidden');
                        });
                });
            });
        });
    </script>
</body>
</html>
