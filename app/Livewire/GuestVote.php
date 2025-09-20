<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\League;
use App\Models\Metch; // Assuming Metch is the model for matches
use App\Models\Player;
use App\Models\Team;
use App\Models\Vote;
use Livewire\Component;

class GuestVote extends Component
{
    public $leagues;
    public $categories;
    public $matches = [];
    public $players = [];
    public $selectedLeague = '';
    public $selectedMatch = '';
    public $selectedCategory = '';
    public $selectedPlayer = '';
    public $numVotes = '';

    protected $rules = [
        'selectedLeague' => 'required|exists:leagues,id',
        'selectedMatch' => 'required|exists:matches,id',
        'selectedCategory' => 'required|exists:categories,id',
        'selectedPlayer' => 'required', // Can be player_id or coach_id
        'numVotes' => 'required|in:1,3,5',
    ];

    public function mount()
    {
        $this->leagues = League::all();
        $this->categories = Category::all();
    }

    public function updatedSelectedLeague($value)
    {
        $this->selectedMatch = '';
        $this->selectedPlayer = '';
        $this->matches = [];
        $this->players = [];

        if ($value) {
            $this->matches = Metch::where('league_id', $value)->with(['homeTeam', 'awayTeam'])->get();
        }
    }

    public function updatedSelectedMatch($value)
    {
        $this->selectedPlayer = '';
        $this->players = [];
        $this->updatePlayerList();
    }

    public function updatedSelectedCategory($value)
    {
        $this->selectedPlayer = '';
        $this->players = [];
        $this->updatePlayerList();
    }

    private function updatePlayerList()
    {
        if ($this->selectedMatch && $this->selectedCategory) {
            $category = Category::find($this->selectedCategory);

            if ($category && strtolower($category->name) === 'best coach') {
                $match = Metch::find($this->selectedMatch);
                if ($match) {
                    $homeTeamCoaches = Team::where('id', $match->home)->pluck('coach_name', 'id');
                    $awayTeamCoaches = Team::where('id', $match->away)->pluck('coach_name', 'id');
                    $this->players = $homeTeamCoaches->merge($awayTeamCoaches)->map(function ($name, $id) {
                        return ['id' => $id, 'name' => $name];
                    })->values()->toArray();
                }
            } else {
                $this->players = Player::where('match_id', $this->selectedMatch)->get()->toArray();
            }
        }
    }

    public function store()
    {
        $this->validate();

        $category = Category::find($this->selectedCategory);
        $isCoachVote = $category && strtolower($category->name) === 'best coach';

        $vote = new Vote();
        $vote->league_id = $this->selectedLeague;
        $vote->match_id = $this->selectedMatch;
        $vote->category_id = $this->selectedCategory;
        $vote->num_votes = $this->numVotes;

        if ($isCoachVote) {
            // For coach votes, selectedPlayer holds the team_id, and coach_name is retrieved from the team
            $team = Team::find($this->selectedPlayer);
            if ($team) {
                $vote->coach_id = $team->id; // Store team_id as coach_id
                $vote->player_id = null; // Ensure player_id is null for coach votes
            }
        } else {
            $vote->player_id = $this->selectedPlayer;
            $vote->coach_id = null; // Ensure coach_id is null for player votes
        }

        // Determine price based on numVotes
        $price = 0;
        switch ($this->numVotes) {
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
        $vote->price = $price;

        $vote->save();

        session()->flash('success', 'Your vote has been cast successfully!');

        // Reset form fields
        $this->reset(['selectedLeague', 'selectedMatch', 'selectedCategory', 'selectedPlayer', 'numVotes']);
        $this->matches = [];
        $this->players = [];
    }

    public function render()
    {
        return view('livewire.guest-vote');
    }
}
