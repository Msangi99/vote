<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\VoteController; // Add this line
use App\Http\Controllers\LeagueController;



// Registration Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Login Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function (Illuminate\Http\Request $request) {
        $dateRange = $request->input('date_range', 'today');
        $customStartDate = $request->input('start_date');
        $customEndDate = $request->input('end_date');

        $startDate = null;
        $endDate = null;

        if ($customStartDate && $customEndDate) {
            $startDate = \Carbon\Carbon::parse($customStartDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($customEndDate)->endOfDay();
        } else {
            switch ($dateRange) {
                case 'today':
                    $startDate = \Carbon\Carbon::today();
                    $endDate = \Carbon\Carbon::tomorrow()->subSecond();
                    break;
                case 'week':
                    $startDate = \Carbon\Carbon::now()->startOfWeek();
                    $endDate = \Carbon\Carbon::now()->endOfWeek();
                    break;
                case 'month':
                    $startDate = \Carbon\Carbon::now()->startOfMonth();
                    $endDate = \Carbon\Carbon::now()->endOfMonth();
                    break;
                case 'year':
                    $startDate = \Carbon\Carbon::now()->startOfYear();
                    $endDate = \Carbon\Carbon::now()->endOfYear();
                    break;
                case 'all':
                default:
                    // No date filter
                    break;
            }
        }

        $queryMatches = App\Models\Metch::query();
        $queryLeagues = App\Models\League::query();
        $queryTeams = App\Models\Team::query();
        $queryPlayers = App\Models\Player::query();
        $queryVotes = App\Models\Vote::query();

        if ($startDate && $endDate) {
            $queryMatches->whereBetween('created_at', [$startDate, $endDate]);
            $queryLeagues->whereBetween('created_at', [$startDate, $endDate]);
            $queryTeams->whereBetween('created_at', [$startDate, $endDate]);
            $queryPlayers->whereBetween('created_at', [$startDate, $endDate]);
            $queryVotes->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalMatches = $queryMatches->count();
        $totalLeagues = $queryLeagues->count();
        $totalTeams = $queryTeams->count();
        $totalPlayers = $queryPlayers->count();
        $totalVotes = $queryVotes->count();
        $totalMonetization = $queryVotes->sum('price');

        $upcomingMatches = App\Models\Metch::with(['homeTeam', 'awayTeam'])
            ->whereDate('match_date', '>=', \Carbon\Carbon::today())
            ->orderBy('match_date', 'asc')
            ->limit(5)
            ->get();

        // Fetch vote data for the graph
        $voteData = App\Models\Vote::query();
        if ($startDate && $endDate) {
            $voteData->whereBetween('created_at', [$startDate, $endDate]);
        }
        $voteData = $voteData->selectRaw('DATE(created_at) as date, count(*) as count')
                             ->groupBy('date')
                             ->orderBy('date', 'asc')
                             ->get();

        // Fetch all leagues for the dashboard cards, eager loading their teams and counting them
        $leagues = App\Models\League::withCount('teams')->get();

        return view('dashboard', compact('totalMatches', 'totalLeagues', 'totalTeams', 'totalPlayers', 'totalVotes', 'totalMonetization', 'upcomingMatches', 'voteData', 'leagues'));
    })->name('dashboard');

    // Category Routes
    Route::resource('categories', CategoryController::class);

    // Team Routes
    Route::resource('teams', TeamController::class);

    // Player Routes
    Route::resource('players', PlayerController::class);

    // Match Routes
    Route::resource('matches', MatchController::class);

    // League Routes
    Route::resource('leagues', LeagueController::class);

    // Voting Routes
    Route::get('/vote', [VoteController::class, 'index'])->name('vote.index');
    Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');
    Route::get('/votes/track', [VoteController::class, 'track'])->name('votes.track');
    Route::get('/matches/{matchId}/players', [VoteController::class, 'getPlayersByMatch']);
    Route::get('/teams/{teamId}/coaches', [VoteController::class, 'getCoachesByTeam']);

    Route::get('/matches/{matchId}/players', [VoteController::class, 'getPlayersByMatch']);
    Route::get('/leagues/{league}/teams', [LeagueController::class, 'getTeamsByLeague']);
    Route::get('/leagues/{league}/upcoming-matches', [LeagueController::class, 'getUpcomingMatches']);

    Route::post('clickpesa/handle', [VoteController::class, 'storeGuestVote'])->name('clickpesa.callback');
});

//Route::get('/votes/track', [VoteController::class, 'track'])->name('votes.track');

// Guest Voting Routes (no authentication required)
Route::get('/', [VoteController::class, 'guestVoteForm'])->name('guest.vote.form');
Route::post('/guest-vote', [VoteController::class, 'storeGuestVote'])->name('guest.vote.store');
Route::get('/leagues/{leagueId}/matches', [MatchController::class, 'getMatchesByLeague']);
