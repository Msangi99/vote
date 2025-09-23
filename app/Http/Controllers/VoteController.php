<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\League;
use App\Models\Metch;
use App\Models\Player;
use App\Models\Team;
use App\Models\Vote;
use App\Services\ClickPesaService;
use Carbon\Carbon; 
use Illuminate\Http\Request;

class VoteController extends Controller
{
    protected $clickPesa;

    public function __construct(ClickPesaService $clickPesa)
    {
        $this->clickPesa = $clickPesa;
    }

    public function index()
    {
        $today = Carbon::today();
        $matches = Metch::with(['homeTeam', 'awayTeam'])
            ->whereDate('match_date', $today)
            ->orwhereDate('match_date', '>', $today)
            ->get();
        $categories = Category::all();

        return view('vote.index', compact('matches', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'match_id' => 'required|exists:matches,id',
            'category_id' => 'required|exists:categories,id',
            'player_id' => 'required', // Player ID can be player_id or coach_name
        ]);

        $category = Category::find($request->category_id);
        $teamId = null;
        $playerId = null;
        $coachName = null;

        if ($category->name === 'best coach') {
            $team = Team::where('coach_name', $request->player_id)->first();
            if ($team) {
                $teamId = $team->id;
                $coachName = $request->player_id;
            } else {
                return redirect()->back()->withErrors(['player_id' => 'Coach not found.']);
            }
        } else {
            $player = Player::find($request->player_id);
            if ($player) {
                $teamId = $player->team_id;
                $playerId = $request->player_id;
            } else {
                return redirect()->back()->withErrors(['player_id' => 'Player not found.']);
            }
        }

        Vote::create([
            'user_id' => '1',
            'match_id' => $request->match_id,
            'category_id' => $request->category_id,
            'player_id' => $playerId,
            'team_id' => $teamId,
            'coach_name' => $coachName,
        ]);

        return redirect()->route('vote.index')->with('success', 'Your vote has been recorded!');
    }

    public function track(Request $request)
    {
        $leagues = League::all();
        $teams = collect(); // Initialize as empty collection

        if ($request->has('league_id') && $request->league_id != '') {
            $teams = Team::where('league_id', $request->league_id)->get();
        } else {
            $teams = Team::all(); // If no league selected, show all teams
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Only proceed with data fetching if a date filter is provided
        if (!$startDate && !$endDate) {
            return view('vote.track', compact('leagues', 'teams'))
                ->with(['categorizedVotes' => collect(), 'categories' => Category::all()]);
        }

        $playerVotesQuery = Vote::query();
        $playerVotesQuery->with(['player', 'category', 'match.homeTeam', 'match.awayTeam', 'team']);
        $playerVotesQuery->join('matches', 'votes.match_id', '=', 'matches.id');
        $playerVotesQuery->leftJoin('players', 'votes.player_id', '=', 'players.id'); // Join players table
        $playerVotesQuery->leftJoin('teams as player_teams', 'players.team_id', '=', 'player_teams.id'); // Join teams for player's team

        if ($startDate && $endDate) {
            $playerVotesQuery->whereBetween('matches.match_date', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()]);
        } elseif ($startDate) {
            $playerVotesQuery->whereDate('matches.match_date', Carbon::parse($startDate)->startOfDay());
        } elseif ($endDate) {
            $playerVotesQuery->whereDate('matches.match_date', Carbon::parse($endDate)->endOfDay());
        }

        if ($request->has('league_id') && $request->league_id != '') {
            $playerVotesQuery->where(function ($q) use ($request) {
                $q->where('player_teams.league_id', $request->league_id)
                    ->orWhereHas('match.homeTeam', function ($q2) use ($request) {
                        $q2->where('league_id', $request->league_id);
                    })
                    ->orWhereHas('match.awayTeam', function ($q2) use ($request) {
                        $q2->where('league_id', $request->league_id);
                    });
            });
        }
        if ($request->has('team_id') && $request->team_id != '') {
            $playerVotesQuery->where(function ($q) use ($request) {
                $q->where('players.team_id', $request->team_id)
                    ->orWhere('votes.team_id', $request->team_id); // For coach votes, if team_id is directly on vote
            });
        }

        $playerVotes = $playerVotesQuery
            ->select(
                'votes.player_id',
                'votes.category_id',
                'votes.match_id',
                'matches.match_date',
                \DB::raw('count(*) as total_votes')
            )
            ->groupBy('votes.player_id', 'votes.category_id', 'votes.match_id', 'matches.match_date')
            ->orderBy('matches.match_date', 'asc')
            ->get();

        $bestCoachCategory = Category::where('name', 'best coach')->first();
        $coachVotesQuery = Vote::query();
        $coachVotesQuery->with(['match.homeTeam', 'match.awayTeam']);
        $coachVotesQuery->join('teams', 'votes.team_id', '=', 'teams.id');
        $coachVotesQuery->join('matches', 'votes.match_id', '=', 'matches.id');
        if ($bestCoachCategory) {
            $coachVotesQuery->where('votes.category_id', $bestCoachCategory->id);
        } else {
            // If 'best coach' category doesn't exist, ensure no coach votes are fetched
            $coachVotesQuery->whereRaw('1 = 0');
        }

        if ($startDate && $endDate) {
            $coachVotesQuery->whereBetween('matches.match_date', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()]);
        } elseif ($startDate) {
            $coachVotesQuery->whereDate('matches.match_date', Carbon::parse($startDate)->startOfDay());
        } elseif ($endDate) {
            $coachVotesQuery->whereDate('matches.match_date', Carbon::parse($endDate)->endOfDay());
        }

        if ($request->has('league_id') && $request->league_id != '') {
            $coachVotesQuery->where('teams.league_id', $request->league_id);
        }
        if ($request->has('team_id') && $request->team_id != '') {
            $coachVotesQuery->where('votes.team_id', $request->team_id);
        }

        $coachVotes = $coachVotesQuery
            ->select(
                'votes.coach_name',
                'votes.match_id',
                'matches.match_date',
                \DB::raw('count(*) as total_votes')
            )
            ->groupBy('votes.coach_name', 'votes.match_id', 'matches.match_date')
            ->orderBy('matches.match_date', 'asc')
            ->get();

        $categories = Category::all();
        $categorizedVotes = $categories->map(function ($category) use ($playerVotes, $coachVotes) {
            if ($category->name === 'best coach') {
                $category->coachVotes = $coachVotes->sortByDesc('total_votes');
                $category->playerVotes = collect();
            } else {
                $category->playerVotes = $playerVotes->where('category_id', $category->id)->sortByDesc('total_votes');
                $category->coachVotes = collect();
            }
            return $category;
        });

        // return [
        //     'categorizedVotes' => $categorizedVotes,
        //     'categories' => $categories,
        //     'leagues' => $leagues,
        //     'teams' => $teams,
        // ];

        return view('vote.track', compact('categorizedVotes', 'categories', 'leagues', 'teams'));
    }

    public function getPlayersByMatch($matchId)
    {
        $match = Metch::with(['homeTeam.players', 'awayTeam.players'])->find($matchId);
        if (!$match) {
            return response()->json([]);
        }

        $players = collect();
        if ($match->homeTeam) {
            $players = $players->merge($match->homeTeam->players);
        }
        if ($match->awayTeam) {
            $players = $players->merge($match->awayTeam->players);
        }

        return response()->json($players);
    }

    public function getCoachesByTeam($teamId)
    {
        $team = Team::find($teamId);
        if (!$team) {
            return response()->json([]);
        }

        return response()->json([['id' => $team->coach_name, 'name' => $team->coach_name]]);
    }

    public function guestVoteForm()
    {

        $matches = Metch::with(['homeTeam', 'awayTeam'])->get();
        $categories = Category::all();
        $leagues = League::all(); // Fetch all leagues
        return view('vote.guest_vote', compact('matches', 'categories', 'leagues'));
    }

    public function storeGuestVote(Request $request)
    {
        //return $request->all();
        $request->validate([
            'match_id' => 'required|exists:matches,id',
            'category_id' => 'required|exists:categories,id',
            'player_id' => 'nullable|required_without:coach_id', // Player ID can be player_id or coach_name
            'coach_id' => 'nullable|required_without:player_id',
            'num_votes' => 'required|in:1,3,5',
        ]);

        $numVotes = (int) $request->num_votes;
        $price = 0;

        switch ($numVotes) {
            case 1:
                $price = 300;
                break;
            case 3:
                $price = 500;
                break;
            case 5:
                $price = 800;
                break;
        }

        $category = Category::find($request->category_id);
        $teamId = null;
        $playerId = null;
        $coachName = null;

        if ($category->name === 'best coach') {
            $team = Team::where('coach_name', $request->coach_id)->first();
            if ($team) {
                $teamId = $team->id;
                $coachName = $request->coach_id;
            } else {
                return "pop";
            }
        } else {
            $player = Player::find($request->player_id);
            if ($player) {
                $teamId = $player->team_id;
                $playerId = $request->player_id;
            } else {
                return "pup";
            }
        }

       $data = $this->clickPesa->initiateUSSD(
            "255628042409",
            1000,
            'TZS',
            'ORDER-' . time()
        );

        return response()->json($data);


        //return redirect()->route('guest.vote.form')->with('success', 'Your votes have been recorded!');
    }

    public function clickPesaHandle(Request $request)
    {
        return $request->all();
    }
}
