<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\League;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::all();
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        $leagues = League::all();
        return view('teams.create', compact('leagues'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:teams|max:255',
            'league_id' => 'required|exists:leagues,id',
            'coach_name' => 'required|string|max:255',
        ]);

        Team::create($request->all());
        return redirect()->route('teams.index')->with('success', 'Team created successfully.');
    }

    public function edit(Team $team)
    {
        $leagues = League::all();
        return view('teams.edit', compact('team', 'leagues'));
    }

    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|unique:teams,name,' . $team->id . '|max:255',
            'league_id' => 'required|exists:leagues,id',
            'coach_name' => 'required|string|max:255',
        ]);

        $team->update($request->all());
        return redirect()->route('teams.index')->with('success', 'Team updated successfully.');
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Team deleted successfully.');
    }
}
