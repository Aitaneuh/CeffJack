<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-[#EDEDEC] leading-tight">
            {{ __('Leaderboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-[#222222] text-black dark:text-[#EDEDEC]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#272727] p-6 shadow-sm rounded-lg">
                <h3 class="text-2xl font-bold text-center mb-6">Top Players</h3>

                <div class="overflow-x-auto">
                    <table class="w-full table-auto text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200">
                                <th class="px-4 py-2">#</th>
                                <th class="px-4 py-2">Player</th>
                                <th class="px-4 py-2">Games</th>
                                <th class="px-4 py-2">Wins</th>
                                <th class="px-4 py-2">Win Rate</th>
                                <th class="px-4 py-2">Total Bet</th>
                                <th class="px-4 py-2">Total Payout</th>
                                <th class="px-4 py-2">Profit</th>
                                <th class="px-4 py-2">Avg Bet</th>
                                <th class="px-4 py-2">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaderboard as $index => $player)
                                @php
                                    $winRate = $player->games_played > 0 ? round(($player->wins / $player->games_played) * 100, 2) : 0;
                                    $avgBet = $player->games_played > 0 ? round($player->total_bet / $player->games_played, 2) : 0;
                                @endphp
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-[#333]">
                                    <td class="px-4 py-3 font-semibold">{{ $leaderboard->firstItem() + $index }}</td>
                                    <td class="px-4 py-3">{{ $player->name }}</td>
                                    <td class="px-4 py-3">{{ $player->games_played }}</td>
                                    <td class="px-4 py-3">{{ $player->wins }}</td>
                                    <td class="px-4 py-3">{{ $winRate }}%</td>
                                    <td class="px-4 py-3">${{ number_format($player->total_bet, 2) }}</td>
                                    <td class="px-4 py-3">${{ number_format($player->total_payout, 2) }}</td>
                                    <td class="px-4 py-3 ${ $player->total_profit >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                        ${{ number_format($player->total_profit, 2) }}
                                    </td>
                                    <td class="px-4 py-3">${{ number_format($avgBet, 2) }}</td>
                                    <td class="px-4 py-3">${{ number_format($player->balance, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $leaderboard->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
