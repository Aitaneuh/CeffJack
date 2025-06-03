<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-[#EDEDEC] leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-[#222222]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Carte de bienvenue --}}
            <div class="bg-white dark:bg-[#272727] dark:text-[#EDEDEC] overflow-hidden shadow rounded-lg p-6">
                <h3 class="text-2xl font-bold mb-2">Welcome back, {{ Auth::user()->name }} !</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    You're logged in as <strong>{{ Auth::user()->email }}</strong>.
                </p>
            </div>

            {{-- Statistiques rapides --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-[#272727] dark:text-[#EDEDEC] p-4 rounded-lg shadow">
                    <h4 class="text-lg font-semibold">Games Played</h4>
                    <p class="text-2xl mt-2 font-bold">{{ $stats['games_played'] }}</p>
                </div>
                <div class="bg-white dark:bg-[#272727] dark:text-[#EDEDEC] p-4 rounded-lg shadow">
                    <h4 class="text-lg font-semibold">Wins</h4>
                    <p class="text-2xl mt-2 font-bold text-green-500">{{ $stats['wins'] }}</p>
                </div>
                <div class="bg-white dark:bg-[#272727] dark:text-[#EDEDEC] p-4 rounded-lg shadow">
                    <h4 class="text-lg font-semibold">Win Rate</h4>
                    <p class="text-2xl mt-2 font-bold">{{ $stats['win_rate'] }}%</p>
                </div>
                <div class="bg-white dark:bg-[#272727] dark:text-[#EDEDEC] p-4 rounded-lg shadow">
                    <h4 class="text-lg font-semibold">Balance</h4>
                    <p class="text-2xl mt-2 font-bold">{{ formatNumberAbbreviated(Auth::user()->balance) }} $</p>
                </div>
            </div>

            {{-- Dernière partie --}}
            <div class="bg-white dark:bg-[#272727] dark:text-[#EDEDEC] p-6 rounded-lg shadow">
                <h3 class="text-xl font-bold mb-4">Last Game</h3>

                @if ($lastGame)
                    <p>
                        You 
                        @if ($lastGame->result === 'won')
                            <span class="font-semibold text-green-500">won</span>
                        @elseif ($lastGame->result === 'lost')
                            <span class="font-semibold text-red-500">lost</span>
                        @else
                            <span class="font-semibold text-yellow-500">tied</span>
                        @endif
                        your last game with a total of {{ $lastGame->player_total ?? '?' }}!
                    </p>

                    <div class="mt-4 flex gap-2">
                        @foreach ($lastGame->player as $card)
                            <img src="{{ asset('images/' . $card['card'] . '.png') }}" class="w-20 rounded shadow" alt="Card">
                        @endforeach
                    </div>
                @else
                    <p class="italic text-gray-500 dark:text-gray-400">You haven't played any game yet.</p>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex justify-between items-center">
                <a href="{{ route('blackjack') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded shadow text-lg font-bold">
                    Play Blackjack
                </a>

                @if($bonusCooldown > 0)
                    @php
                        // Format heure en 24h, par exemple "16:02"
                        $formattedTime = $nextClaimTime->format('H:i');
                    @endphp

                    <button disabled class="bg-gray-400 cursor-not-allowed px-6 py-4 rounded text-white font-bold">
                        Bonus available at {{ $formattedTime }}
                    </button>
                @else
                    <form method="POST" action="{{ route('bonus.claim') }}">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 px-6 py-4 rounded text-white font-bold">
                            Claim Bonus
                        </button>
                    </form>
                @endif


                <a href="{{ route('rules') }}" class="text-sm underline text-red-500 hover:text-red-600">
                    View Blackjack Rules
                </a>
            </div>
        </div>
    </div>
</x-app-layout>