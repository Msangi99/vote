<?php

namespace App\Http\Controllers;

use App\Models\Metch;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\League; // Import League model

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $query = Metch::with('homeTeam', 'awayTeam');

        if ($request->has('league_id') && $request->input('league_id') != '') {
            $leagueId = $request->input('league_id');
            $query->whereHas('homeTeam', function ($q) use ($leagueId) {
                $q->where('league_id', $leagueId);
            })->orWhereHas('awayTeam', function ($q) use ($leagueId) {
                $q->where('league_id', $leagueId);
            });
        }

        $matches = $query->get();
        $leagues = League::all(); // Fetch all leagues for the filter dropdown

        return view('matches.index', compact('matches', 'leagues'));
    }

    public function create()
    {
        $leagues = League::all();
        return view('matches.create', compact('leagues'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'home' => 'required|exists:teams,id',
            'away' => 'required|exists:teams,id|different:home_team_id',
            'match_date' => 'required|date',
            'score' => 'nullable|string|max:255',
        ]);

        Metch::create($request->all());
        return redirect()->route('matches.index')->with('success', 'Match created successfully.');
    }

    public function edit(Metch $match)
    {
        $teams = Team::all();
        return view('matches.edit', compact('match', 'teams'));
    }

    public function update(Request $request, Metch $match)
    {
        $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'match_date' => 'required|date',
            'score' => 'nullable|string|max:255',
        ]);

        $match->update($request->all());
        return redirect()->route('matches.index')->with('success', 'Match updated successfully.');
    }

    public function destroy(Metch $match)
    {
        $match->delete();
        return redirect()->route('matches.index')->with('success', 'Match deleted successfully.');
    }

    public function getMatchesByLeague(Request $request, $leagueId)
    {
        $query = Metch::with(['homeTeam', 'awayTeam'])
            ->whereHas('homeTeam', function ($q) use ($leagueId) {
                $q->where('league_id', $leagueId);
            })
            ->orWhereHas('awayTeam', function ($q) use ($leagueId) {
                $q->where('league_id', $leagueId);
            });

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $query->whereBetween('match_date', [\Carbon\Carbon::parse($startDate)->startOfDay(), \Carbon\Carbon::parse($endDate)->endOfDay()]);
        } elseif ($startDate) {
            $query->whereDate('match_date', \Carbon\Carbon::parse($startDate)->startOfDay());
        } elseif ($endDate) {
            $query->whereDate('match_date', \Carbon\Carbon::parse($endDate)->endOfDay());
        }

        $matches = $query->get();

        return response()->json($matches);
    }
}
