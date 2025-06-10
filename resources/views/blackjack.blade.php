<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Blackjack
            </h2>
            <div class="text-sm text-gray-600 dark:text-gray-300">
                Balance: 
                <span class="font-semibold text-green-600 dark:text-green-400">
                    {{ number_format(auth()->user()->balance, 2) }} $
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 dark:bg-[#222222] text-black dark:text-[#EDEDEC]">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#272727] p-6 shadow-sm rounded-lg space-y-6">

                {{-- Cartes du croupier et du joueur côte à côte --}}
                <div class="flex flex-col md:flex-row justify-between gap-6">
                    {{-- Joueur --}}
                    <div class="flex-1">
                        <h3 class="text-lg font-bold mb-2">Your Hand:</h3>
                        <div class="flex items-center flex-wrap">
                            @foreach ($game['player'] as $card)
                                <img src="{{ asset('images/' . strtolower($card['card']) . '.png') }}"
                                     alt="{{ $card['card'] }}"
                                     class="w-24 m-3 rounded-md shadow" loading="lazy">
                            @endforeach
                            <span class="ml-2 font-semibold text-lg">[Total: {{ $game['player_total'] }}]</span>
                        </div>
                    </div>


                    {{-- Dealer --}}
                    <div class="flex-1">
                        <h3 class="text-lg font-bold mb-2">Dealer's Hand:</h3>
                        <div class="flex items-center flex-wrap">
                            @if ($game['state'] === 'playing')
                                <img src="{{ asset('images/' . strtolower($game['dealer'][0]['card']) . '.png') }}"
                                     alt="{{ $game['dealer'][0]['card'] }}"
                                     class="w-24 rounded-md m-3 shadow" loading="lazy">
                                <img src="{{ asset('images/card_back.png') }}"
                                     alt="Hidden Card"
                                     class="w-24 rounded-md m-3 shadow" loading="lazy">
                                @php
                                    $value = $game['dealer'][0]['value'] === 1 ? 11 : $game['dealer'][0]['value'];
                                @endphp
                                <span class="ml-2 font-semibold text-lg">[Total: {{ $value }}+]</span>
                            @else
                                @foreach ($game['dealer'] as $card)
                                    <img src="{{ asset('images/' . strtolower($card['card']) . '.png') }}"
                                         alt="{{ $card['card'] }}"
                                         class="w-24 rounded-md m-3 shadow" loading="lazy">
                                @endforeach
                                <span class="ml-2 font-semibold text-lg">[Total: {{ $game['dealer_total'] }}]</span>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Actions + Résultat + Mise --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">

                    {{-- Boutons de jeu --}}
                    <div class="space-x-3">
                        @if ($game['state'] === 'playing')
                            <form method="POST" action="{{ route('blackjack.hit') }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                    Hit
                                </button>
                            </form>

                            <form method="POST" action="{{ route('blackjack.stand') }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                                    Stand
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- Résultat --}}
                    @if ($game['state'] !== 'playing')
                        <div class="text-lg font-bold">
                            @if ($game['state'] === 'won')
                                <span class="text-green-500">You won!</span>
                            @elseif ($game['state'] === 'lost')
                                <span class="text-red-500">You lost!</span>
                            @else
                                <span class="text-yellow-500">Draw!</span>
                            @endif
                        </div>
                    @endif

                    {{-- Formulaire de mise --}}
                    @if ($game['state'] !== 'playing')
                        <form method="POST" action="{{ route('blackjack.reset') }}" class="flex items-center space-x-3">
                            @csrf
                            <label for="bet" class="text-sm font-medium">Bet:</label>
                            <input type="number" name="bet" id="bet" min="1" max="{{ auth()->user()->balance ?? 50000 }}" required value="{{ old('bet', $lastBet ?? 1000) }}"
                                class="w-24 p-2 rounded bg-white text-black border dark:bg-[#1a1a1a] dark:text-white">

                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Play Again
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
