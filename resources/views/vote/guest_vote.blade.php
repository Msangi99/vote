<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🗳️ Guest Voting</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* ============= FOOTBALL ANIMATION BACKGROUND ============= */
        .ball-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
        }

        .ball {
            position: absolute;
            width: 50px;
            height: 50px;
            background-image: url('{{ asset('ball.png') }}');
            background-size: cover;
            opacity: 0.08;
            animation: float 20s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            25%      { transform: translateY(-30px) rotate(90deg); }
            50%      { transform: translateY(0px) rotate(180deg); }
            75%      { transform: translateY(30px) rotate(270deg); }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 flex items-center justify-center min-h-screen p-4">

    <!-- Animated Football Background -->
    <div class="ball-bg">
        @for ($i = 0; $i < 5; $i++)
            <div class="ball" style="top: {{ (20 + $i * 15) % 90 }}%; left: {{ (10 + $i * 20) % 85 }}%; animation-delay: -{{ $i * 4 }}s;"></div>
        @endfor
    </div>

    <!-- Main Voting Card -->
    <div class="w-full max-w-xl">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

            <!-- Header -->
            <div class="bg-gray-800 py-5 text-center">
                <h1 class="text-2xl font-bold text-white">🗳️ Cast Your Vote</h1>
                <p class="text-blue-100 mt-1 text-sm">Support your favorite player or coach!</p>
            </div>

            <!-- Success Alert -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">✅ Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Error Alert -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">❌ Error!</strong>
                    <span class="block sm:inline">Vote failed: {{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Voting Form -->
            <div class="p-6">
                <form action="{{ route('guest.vote.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- League Selection -->
                    <div>
                        <label for="league_id" class="block text-sm font-semibold text-gray-700 mb-1">🏆 Select League</label>
                        <select id="league_id" name="league_id" required
                                class="w-full px-4 py-2.5 text-base border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            <option value="" disabled selected>Select a league</option>
                            @foreach ($leagues as $league)
                                <option value="{{ $league->id }}">{{ $league->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Match Selection (Disabled until league selected) -->
                    <div>
                        <label for="match_id" class="block text-sm font-semibold text-gray-700 mb-1">⚽ Select Match</label>
                        <select id="match_id" name="match_id" required disabled
                                class="w-full px-4 py-2.5 text-base border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            <option value="" disabled selected>Select a match</option>
                        </select>
                    </div>

                    <!-- Category Selection -->
                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-1">🏅 Select Category</label>
                        <select id="category_id" name="category_id" required
                                class="w-full px-4 py-2.5 text-base border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            <option value="" disabled selected>Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Player/Coach Selection (Disabled until match & category selected) -->
                    <div>
                        <label id="player_or_coach_label" for="player_id"
                               class="block text-sm font-semibold text-gray-700 mb-1">🌟 Select Player</label>
                        <select id="player_id" name="player_id" required disabled
                                class="w-full px-4 py-2.5 text-base border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            <option value="" disabled selected>Please select match and category first</option>
                        </select>
                    </div>

                    <!-- Vote Package Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">💰 Choose Votes Package</label>
                        <div class="grid grid-cols-1 gap-3">
                            @foreach ([['votes' => 1, 'price' => '300 TSH'], ['votes' => 3, 'price' => '500 TSH'], ['votes' => 5, 'price' => '800 TSH']] as $pkg)
                                <label class="flex items-center gap-2 border border-gray-200 rounded-lg px-4 py-2 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition">
                                    <input type="radio" name="num_votes" value="{{ $pkg['votes'] }}" {{ $loop->first ? 'required' : '' }}
                                           class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <span class="text-base">{{ $pkg['votes'] }} Vote{{ $pkg['votes'] > 1 ? 's' : '' }} = {{ $pkg['price'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="w-full px-6 py-3 text-lg font-semibold text-white rounded-lg shadow-md bg-green-700 hover:bg-gradient-to-r hover:from-blue-700 hover:to-green-400 hover:shadow-lg transform hover:-translate-y-0.5 transition duration-200">
                            🗳️ SUBMIT VOTE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const leagueSelect = document.getElementById('league_id');
            const matchSelect = document.getElementById('match_id');
            const categorySelect = document.getElementById('category_id');
            const playerSelect = document.getElementById('player_id');
            const playerOrCoachLabel = document.getElementById('player_or_coach_label');

            // Initialize states
            matchSelect.disabled = true;
            playerSelect.disabled = true;

            /**
             * Updates player/coach dropdown based on selected match & category.
             * Auto-selects last option after population.
             */
            function updatePlayerList() {
                const matchId = matchSelect.value;
                const categoryId = categorySelect.value;
                const categoryName = categorySelect.options[categorySelect.selectedIndex]?.text || '';

                // Reset player/coach select
                playerSelect.innerHTML = '<option value="" disabled selected>Loading...</option>';
                playerSelect.disabled = true;

                // Update label and input name
                if (categoryName.toLowerCase().includes('coach')) {
                    playerOrCoachLabel.textContent = '🌟 Select Coach';
                    playerSelect.name = 'coach_id';
                } else {
                    playerOrCoachLabel.textContent = '🌟 Select Player';
                    playerSelect.name = 'player_id';
                }

                if (!matchId || !categoryId) return;

                const endpoint = `/matches/${matchId}/players`;

                if (categoryName.toLowerCase().includes('coach')) {
                    fetch(endpoint)
                        .then(res => res.json())
                        .then(players => {
                            const teamIds = [...new Set(players.map(p => p.team_id))];
                            const coachPromises = teamIds.map(teamId =>
                                fetch(`/teams/${teamId}/coaches`).then(r => r.json())
                            );

                            Promise.all(coachPromises)
                                .then(allCoaches => {
                                    playerSelect.innerHTML = '<option value="" disabled selected>Select a coach</option>';
                                    const coaches = allCoaches.flat();
                                    coaches.forEach(coach => {
                                        const opt = document.createElement('option');
                                        opt.value = coach.id;
                                        opt.textContent = coach.name;
                                        playerSelect.appendChild(opt);
                                    });

                                    playerSelect.disabled = false;

                                    // ✅ Auto-select last option
                                    if (coaches.length > 0) {
                                        playerSelect.selectedIndex = playerSelect.options.length - 1;
                                    }
                                })
                                .catch(() => {
                                    playerSelect.innerHTML = '<option value="" disabled>Failed to load coaches</option>';
                                    playerSelect.disabled = true;
                                });
                        })
                        .catch(() => {
                            playerSelect.innerHTML = '<option value="" disabled>Failed to load data</option>';
                            playerSelect.disabled = true;
                        });
                } else {
                    fetch(endpoint)
                        .then(res => res.json())
                        .then(players => {
                            playerSelect.innerHTML = '<option value="" disabled selected>Select a player</option>';
                            players.forEach(player => {
                                const opt = document.createElement('option');
                                opt.value = player.id;
                                opt.textContent = player.name;
                                playerSelect.appendChild(opt);
                            });

                            playerSelect.disabled = false;

                            // ✅ Auto-select last option
                            if (players.length > 0) {
                                playerSelect.selectedIndex = playerSelect.options.length - 1;
                            }
                        })
                        .catch(() => {
                            playerSelect.innerHTML = '<option value="" disabled>Failed to load players</option>';
                            playerSelect.disabled = true;
                        });
                }
            }

            /**
             * Updates match dropdown based on selected league.
             */
            function updateMatchList() {
                const leagueId = leagueSelect.value;

                matchSelect.innerHTML = '<option value="" disabled selected>Loading matches...</option>';
                matchSelect.disabled = true;
                playerSelect.innerHTML = '<option value="" disabled>Please select match and category first</option>';
                playerSelect.disabled = true;

                if (!leagueId) return;

                fetch(`/leagues/${leagueId}/matches`)
                    .then(res => res.json())
                    .then(matches => {
                        matchSelect.innerHTML = '<option value="" disabled selected>Select a match</option>';
                        matches.forEach(match => {
                            const opt = document.createElement('option');
                            opt.value = match.id;
                            const matchDate = new Date(match.match_date).toLocaleString();
                            opt.textContent = `${match.home_team.name} VS ${match.away_team.name} — ${matchDate}`;
                            matchSelect.appendChild(opt);
                        });
                        matchSelect.disabled = false;
                        updatePlayerList(); // Re-check in case category was already selected
                    })
                    .catch(() => {
                        matchSelect.innerHTML = '<option value="" disabled>Failed to load matches</option>';
                        matchSelect.disabled = true;
                    });
            }

            // Event Listeners
            leagueSelect.addEventListener('change', updateMatchList);
            matchSelect.addEventListener('change', updatePlayerList);
            categorySelect.addEventListener('change', updatePlayerList);

            // Initialize
            updateMatchList();
        });
    </script>
</body>
</html>