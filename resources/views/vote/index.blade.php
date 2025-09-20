<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex">
    <x-sidebar />

    <div class="flex-1 p-10">
        <h1 class="text-3xl font-bold mb-6">Cast Your Vote</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-8 rounded-lg shadow-md">
            <form action="{{ route('vote.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="match_id" class="block text-gray-700 text-sm font-bold mb-2">Select Match:</label>
                    <select name="match_id" id="match_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">-- Select a Match --</option>
                        @foreach ($matches as $match)
                            <option value="{{ $match->id }}">
                                {{ $match->homeTeam->name ?? 'N/A' }} vs {{ $match->awayTeam->name ?? 'N/A' }} ({{ \Carbon\Carbon::parse($match->match_date)->format('M d, Y H:i') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Select Category:</label>
                    <select name="category_id" id="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">-- Select a Category --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Select Player:</label>
                    <div id="players_list" class="grid grid-cols-2 gap-4">
                        <p class="text-gray-600">Select a match to see players.</p>
                    </div>
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Submit Vote
                </button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('match_id').addEventListener('change', function() {
            updatePlayerList();
        });

        document.getElementById('category_id').addEventListener('change', function() {
            updatePlayerList();
        });

        function updatePlayerList() {
            var matchId = document.getElementById('match_id').value;
            var categoryId = document.getElementById('category_id').value;
            var categoryName = document.getElementById('category_id').options[document.getElementById('category_id').selectedIndex].text;
            var playersList = document.getElementById('players_list');
            playersList.innerHTML = '<p class="text-gray-600">Loading...</p>';

            if (matchId && categoryId) {
                if (categoryName.toLowerCase() === 'best coach') {
                    fetch(`/matches/${matchId}/players`) // We need to get the teams from the match
                        .then(response => response.json())
                        .then(data => {
                            playersList.innerHTML = '';
                            if (data.length > 0) {
                                const teams = [...new Set(data.map(player => player.team_id))];
                                teams.forEach(teamId => {
                                    fetch(`/teams/${teamId}/coaches`)
                                        .then(response => response.json())
                                        .then(coaches => {
                                            coaches.forEach(coach => {
                                                playersList.innerHTML += `
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" class="form-radio" name="player_id" value="${coach.id}" required>
                                                        <span class="ml-2">${coach.name}</span>
                                                    </label>
                                                `;
                                            });
                                        });
                                });
                            } else {
                                playersList.innerHTML = '<p class="text-gray-600">No teams found for this match.</p>';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching teams:', error);
                            playersList.innerHTML = '<p class="text-red-500">Error loading coaches.</p>';
                        });
                } else {
                    fetch(`/matches/${matchId}/players`)
                        .then(response => response.json())
                        .then(data => {
                            playersList.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(player => {
                                    playersList.innerHTML += `
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio" name="player_id" value="${player.id}" required>
                                            <span class="ml-2">${player.name} (${player.team ? player.team.name : 'N/A'})</span>
                                        </label>
                                    `;
                                });
                            } else {
                                playersList.innerHTML = '<p class="text-gray-600">No players found for this match.</p>';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching players:', error);
                            playersList.innerHTML = '<p class="text-red-500">Error loading players.</p>';
                        });
                }
            } else {
                playersList.innerHTML = '<p class="text-gray-600">Select a match and category to see options.</p>';
            }
        }
    </script>
</body>
</html>
