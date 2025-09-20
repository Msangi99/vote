<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Models\Metch; // Assuming Metch is the Match model
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeagueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leagues = League::withCount('teams')->get();
        return view('leagues.index', compact('leagues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('leagues.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        League::create($request->all());

        return redirect()->route('leagues.index')
            ->with('success', 'League created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $league = League::findOrFail($id);
        return view('leagues.show', compact('league'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $league = League::findOrFail($id);
        return view('leagues.edit', compact('league'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $league = League::findOrFail($id);
        $league->update($request->all());

        return redirect()->route('leagues.index')
            ->with('success', 'League updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $league = League::findOrFail($id);
        $league->delete();

        return redirect()->route('leagues.index')
            ->with('success', 'League deleted successfully.');
    }

    public function getTeamsByLeague(League $league)
    {
        
        return response()->json($league->teams);
    }

    public function getUpcomingMatches(League $league)
    {
        $upcomingMatches = $league->matches()
            ->where('match_date', '>', Carbon::now())
            ->with(['homeTeam', 'awayTeam'])
            ->orderBy('match_date')
            ->get();

        return response()->json($upcomingMatches);
    }
}
