{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Voting</title>
    @livewireStyles()
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
            /* Transparent football PNG */
            background-size: cover;
            opacity: 0.08;
            animation: float 20s infinite ease-in-out;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            25% {
                transform: translateY(-30px) rotate(90deg);
            }

            50% {
                transform: translateY(0px) rotate(180deg);
            }

            75% {
                transform: translateY(30px) rotate(270deg);
            }
        }
    </style>
</head>

<body
    class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 items-center justify-center min-h-screen p-4">

    <!-- Animated Football Background -->
    <div class="ball-bg">
        <div class="ball" style="top: 20%; left: 10%; animation-delay: 0s;"></div>
        <div class="ball" style="top: 60%; left: 80%; animation-delay: -4s;"></div>
        <div class="ball" style="top: 40%; left: 30%; animation-delay: -8s;"></div>
        <div class="ball" style="top: 80%; left: 60%; animation-delay: -12s;"></div>
        <div class="ball" style="top: 15%; left: 75%; animation-delay: -16s;"></div>
    </div>

    <!-- Card -->
    @livewire('guest-vote')
    <!-- JavaScript (unchanged) -->

</body>
@livewireScripts()

</html> --}}





<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Guest Voting</title>
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
      background-image: url('{{ asset("ball.png") }}'); /* Transparent football PNG */
      background-size: cover;
      opacity: 0.08;
      animation: float 20s infinite ease-in-out;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      25% { transform: translateY(-30px) rotate(90deg); }
      50% { transform: translateY(0px) rotate(180deg); }
      75% { transform: translateY(30px) rotate(270deg); }
    }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 flex items-center justify-center min-h-screen p-4">

  <!-- Animated Football Background -->
  <div class="ball-bg">
    <div class="ball" style="top: 20%; left: 10%; animation-delay: 0s;"></div>
    <div class="ball" style="top: 60%; left: 80%; animation-delay: -4s;"></div>
    <div class="ball" style="top: 40%; left: 30%; animation-delay: -8s;"></div>
    <div class="ball" style="top: 80%; left: 60%; animation-delay: -12s;"></div>
    <div class="ball" style="top: 15%; left: 75%; animation-delay: -16s;"></div>
  </div>

  <!-- Card -->
  <div class="w-full max-w-xl">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
      <!-- Header -->
      <div class="bg-gradient-to-r from-blue-600 to-indigo-700 py-5 text-center">
        <h1 class="text-2xl font-bold text-white">🗳️ Cast Your Vote</h1>
        <p class="text-blue-100 mt-1 text-sm">Support your favorite player or coach!</p>
      </div>
      @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
          <strong class="font-bold">Success!</strong>
          <span class="block sm:inline">{{ session('success') }}</span>
        </div>
      @endif

      @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
          <strong class="font-bold">Error!</strong>
          <span class="block sm:inline">Vote failed: {{ $errors->first() }}</span>
        </div>
      @endif

      {{-- <div class="">
        <img src="{{ asset('WhatsApp Image 2025-09-16 at 19.31.12_62e45b1b.jpg') }}" class="h-40 w-full" alt="">
      </div> --}}

      <!-- Body -->
      <div class="p-6">
        <form action="{{ route('guest.vote.store') }}" method="POST" class="space-y-6">
          @csrf

          <!-- League -->
          <div>
            <label for="league_id" class="block text-sm font-semibold text-gray-700 mb-1">🏆 Select League</label>
            <select id="league_id" name="league_id" required
              class="w-full px-4 py-2.5 text-base border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
              <option value=""></option>
              @foreach($leagues as $league)
                <option value="{{ $league->id }}">{{ $league->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Match -->
          <div>
            <label for="match_id" class="block text-sm font-semibold text-gray-700 mb-1">⚽ Select Match</label>
            <select id="match_id" name="match_id" required disabled
              class="w-full px-4 py-2.5 text-base border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
              <option value=""></option>
            </select>
          </div>

          <!-- Category -->
          <div>
            <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-1">🏅 Select Category</label>
            <select id="category_id" name="category_id" required
              class="w-full px-4 py-2.5 text-base border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
              <option value=""></option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Player / Coach -->
          <div>
            <label id="player_or_coach_label" for="player_id" class="block text-sm font-semibold text-gray-700 mb-1">🌟 Select Player</label>
            <select id="player_id" name="player_id" required disabled
              class="w-full px-4 py-2.5 text-base border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
              <option value=""></option>
            </select>
          </div>

          <!-- Votes -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">💰 Choose Votes Package</label>
            <div class="grid grid-cols-1 gap-3">
              <label class="flex items-center gap-2 border border-gray-200 rounded-lg px-4 py-2 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition">
                <input type="radio" name="num_votes" value="1" required class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                <span class="text-base">1 Vote = 300 TSH</span>
              </label>
              <label class="flex items-center gap-2 border border-gray-200 rounded-lg px-4 py-2 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition">
                <input type="radio" name="num_votes" value="3" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                <span class="text-base">3 Votes = 500 TSH</span>
              </label>
              <label class="flex items-center gap-2 border border-gray-200 rounded-lg px-4 py-2 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition">
                <input type="radio" name="num_votes" value="5" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                <span class="text-base">5 Votes = 800 TSH</span>
              </label>
            </div>
          </div>

          <!-- Submit -->
          <div>
            <button type="submit"
              class="w-full px-6 py-3 text-lg font-semibold text-white rounded-lg shadow-md bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 hover:shadow-lg transform hover:-translate-y-0.5 transition">
               SUBMIT VOTE
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- JavaScript (unchanged) -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {


      const leagueSelect = document.getElementById('league_id');
      const matchSelect = document.getElementById('match_id');
      const categorySelect = document.getElementById('category_id');
      const playerSelect = document.getElementById('player_id');
      const playerOrCoachLabel = document.getElementById('player_or_coach_label');

      matchSelect.disabled = true;
      playerSelect.disabled = true;

      function updatePlayerList() {
        const matchId = matchSelect.value;
        const categoryId = categorySelect.value;
        const categoryName = categorySelect.options[categorySelect.selectedIndex]?.text || '';
        playerSelect.innerHTML = '<option value=""></option>';
        playerSelect.disabled = true;

        if (categoryName.toLowerCase() === 'best coach') {
          playerOrCoachLabel.textContent = '🌟 Select Coach';
          playerSelect.name = 'coach_id';
        } else {
          playerOrCoachLabel.textContent = '🌟 Select Player';
          playerSelect.name = 'player_id';
        }

        if (matchId && categoryId) {
          if (categoryName.toLowerCase() === 'best coach') {
            fetch(`/matches/${matchId}/players`)
              .then(res => res.json())
              .then(data => {
                const teams = [...new Set(data.map(p => p.team_id))];
                teams.forEach(teamId => {
                  fetch(`/teams/${teamId}/coaches`)
                    .then(res => res.json())
                    .then(coaches => {
                      coaches.forEach(coach => {
                        const opt = document.createElement('option');
                        opt.value = coach.id;
                        opt.textContent = coach.name;
                        playerSelect.appendChild(opt);
                      });
                    });
                });
                playerSelect.disabled = false;
              })
              .catch(() => playerSelect.disabled = true);
          } else {
            fetch(`/matches/${matchId}/players`)
              .then(res => res.json())
              .then(players => {
                players.forEach(player => {
                  const opt = document.createElement('option');
                  opt.value = player.id;
                  opt.textContent = player.name;
                  playerSelect.appendChild(opt);
                });
                playerSelect.disabled = false;
              })
              .catch(() => playerSelect.disabled = true);
          }
        }
      }

      function updateMatchList() {
        const leagueId = leagueSelect.value;
        matchSelect.innerHTML = '<option value=""></option>';
        matchSelect.disabled = true;
        playerSelect.innerHTML = '<option value=""></option>';
        playerSelect.disabled = true;

        if (leagueId) {
          fetch(`/leagues/${leagueId}/matches`)
            .then(res => res.json())
            .then(matches => {
              matches.forEach(match => {
                const opt = document.createElement('option');
                opt.value = match.id;
                opt.textContent = `${match.home_team.name} VS ${match.away_team.name} || ${match.match_date}`;
                matchSelect.appendChild(opt);
              });
              matchSelect.disabled = false;
              updatePlayerList();
            })
            .catch(() => matchSelect.disabled = true);
        }
      }

      leagueSelect.addEventListener('change', updateMatchList);
      matchSelect.addEventListener('change', updatePlayerList);
      categorySelect.addEventListener('change', updatePlayerList);

      updateMatchList();
    });
  </script>
</body>
</html>
