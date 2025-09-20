<div>
    <!-- Card -->
    <div class="w-full max-w-xl">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gray-800 py-5 text-center">
                <h1 class="text-2xl font-bold text-white">🗳️ Cast Your Vote</h1>
                <p class="text-blue-100 mt-1 text-sm">Support your favorite player or coach!</p>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">Vote failed: {{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Body -->
            <div class="p-6">
                <form wire:submit.prevent="store" class="space-y-6">
                    <!-- League -->
                    <div>
                        <label for="league_id" class="block text-sm font-semibold text-gray-700 mb-1">🏆 Select
                            League</label>
                        <select id="league_id" wire:model="selectedLeague"
                            class="w-full px-4 py-2.5 text-base border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            <option value="">Select a League</option>
                            @foreach ($leagues as $league)
                                <option value="{{ $league->id }}">{{ $league->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedLeague') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Match -->
                    <div>
                        <label for="match_id" class="block text-sm font-semibold text-gray-700 mb-1">⚽ Select
                            Match</label>
                        <select id="match_id" wire:model="selectedMatch"
                            class="w-full px-4 py-2.5 text-base border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            {{ count($matches) == 0 ? 'disabled' : '' }}>
                            <option value="">Select a Match</option>
                            @foreach ($matches as $match)
                                <option value="{{ $match->id }}">
                                    {{ $match->homeTeam->name }} VS {{ $match->awayTeam->name }} ||
                                    {{ \Carbon\Carbon::parse($match->match_date)->format('M d, Y H:i') }}
                                </option>
                            @endforeach
                        </select>
                        @error('selectedMatch') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-1">🏅 Select
                            Category</label>
                        <select id="category_id" wire:model="selectedCategory"
                            class="w-full px-4 py-2.5 text-base border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            <option value="">Select a Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedCategory') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Player / Coach -->
                    <div>
                        <label id="player_or_coach_label" for="player_id"
                            class="block text-sm font-semibold text-gray-700 mb-1">
                            @php
                                $categoryName = '';
                                if ($selectedCategory) {
                                    $cat = $categories->firstWhere('id', $selectedCategory);
                                    if ($cat) {
                                        $categoryName = $cat->name;
                                    }
                                }
                            @endphp
                            🌟 Select {{ strtolower($categoryName) === 'best coach' ? 'Coach' : 'Player' }}
                        </label>
                        <select id="player_id" wire:model="selectedPlayer"
                            class="w-full px-4 py-2.5 text-base border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            {{ count($players) == 0 ? 'disabled' : '' }}>
                            <option value="">Select a
                                {{ strtolower($categoryName) === 'best coach' ? 'Coach' : 'Player' }}</option>
                            @foreach ($players as $player)
                                <option value="{{ $player['id'] }}">{{ $player['name'] }}</option>
                            @endforeach
                        </select>
                        @error('selectedPlayer') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Votes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">💰 Choose Votes Package</label>
                        <div class="grid grid-cols-1 gap-3">
                            <label
                                class="flex items-center gap-2 border border-gray-200 rounded-lg px-4 py-2 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition">
                                <input type="radio" name="num_votes" value="1" wire:model="numVotes"
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <span class="text-base">1 Vote = 300 TSH</span>
                            </label>
                            <label
                                class="flex items-center gap-2 border border-gray-200 rounded-lg px-4 py-2 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition">
                                <input type="radio" name="num_votes" value="3" wire:model="numVotes"
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <span class="text-base">3 Votes = 500 TSH</span>
                            </label>
                            <label
                                class="flex items-center gap-2 border border-gray-200 rounded-lg px-4 py-2 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition">
                                <input type="radio" name="num_votes" value="5" wire:model="numVotes"
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <span class="text-base">5 Votes = 800 TSH</span>
                            </label>
                        </div>
                        @error('numVotes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit"
                            class="w-full px-6 py-3 text-lg font-semibold text-white rounded-lg shadow-md bg-green-700 hover:from-blue-700 hover:to-green-400 hover:shadow-lg transform hover:-translate-y-0.5 transition">
                            SUBMIT VOTE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
