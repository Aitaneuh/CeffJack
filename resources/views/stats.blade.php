<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-[#EDEDEC] leading-tight">
            {{ __('Your Blackjack Statistics') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-[#222222] text-black dark:text-[#EDEDEC]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-[#272727] p-6 shadow-sm rounded-lg">

                <h3 class="text-2xl font-bold text-center mb-5">Statistics Overview</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-lg">
                    <!-- Games Played -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Games Played:</span>
                        <span class="float-right">{{ $stats['games_played'] }}</span>
                    </div>

                    <!-- Wins -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Wins:</span>
                        <span class="float-right">{{ $stats['wins'] }}</span>
                    </div>

                    <!-- Win % -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Win Percentage:</span>
                        <span class="float-right">
                            @if ($stats['games_played'] > 0)
                                {{ round(($stats['wins'] / $stats['games_played']) * 100, 2) }}%
                            @else 0%
                            @endif
                        </span>
                    </div>

                    <!-- Busts -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Busts:</span>
                        <span class="float-right">{{ $stats['busts'] }}</span>
                    </div>

                    <!-- Blackjacks -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Blackjacks:</span>
                        <span class="float-right">{{ $stats['blackjacks'] }}</span>
                    </div>

                    <!-- Blackjack % -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Blackjack Percentage:</span>
                        <span class="float-right">
                            @if ($stats['games_played'] > 0)
                                {{ round(($stats['blackjacks'] / $stats['games_played']) * 100, 2) }}%
                            @else 0%
                            @endif
                        </span>
                    </div>

                    <!-- Stand Average Score -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Avg Stand Score:</span>
                        <span class="float-right">{{ $stats['average_stand_score'] ?? '17.5' }}</span>
                    </div>

                    <!-- Avg Bet -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Average Bet:</span>
                        <span class="float-right">{{ $stats['average_bet'] ?? '50' }} $</span>
                    </div>

                    <!-- Total Earned -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Total Winnings:</span>
                        <span class="float-right">{{ $stats['total_won'] ?? '4100' }} $</span>
                    </div>

                    <!-- Win per Game -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Avg Profit per Game:</span>
                        <span class="float-right">
                            @if ($stats['games_played'] > 0)
                                {{ round((($stats['total_won'] ?? 1900) - ($stats['total_bet'] ?? 900)) / $stats['games_played'], 2) }} $
                            @else 0 $
                            @endif
                        </span>
                    </div>

                    <!-- Max Win -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Biggest Win:</span>
                        <span class="float-right">{{ $stats['max_win'] ?? '400' }} $</span>
                    </div>

                    <!-- Max Loss -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Biggest Loss:</span>
                        <span class="float-right">{{ $stats['max_loss'] ?? '-150' }} $</span>
                    </div>
                </div>


                <div class="mt-10 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Account created on: <span class="font-semibold">{{ $stats['account_created_at']->format('F j, Y') }}</span>
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-[#272727] p-6 shadow-sm rounded-lg">
                <h3 class="text-xl font-bold mb-4 text-center">Games Overview</h3>
                <div class="mt-6">
                    <canvas id="statsChart" width="600" height="400"></canvas>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    // On récupère les labels (les dates)
                    const labels = @json($history->pluck('date'));

                    // On récupère les données brutes
                    const totals = @json($history->pluck('total'));
                    const wins = @json($history->pluck('wins'));
                    const busts = @json($history->pluck('busts'));
                    const blackjacks = @json($history->pluck('blackjacks'));

                    // Fonction pour cumuler un tableau
                    function cumulativeSum(array) {
                        let result = [];
                        array.reduce((acc, val, i) => result[i] = acc + val, 0);
                        return result;
                    }

                    const cumulativeTotals = cumulativeSum(totals);
                    const cumulativeWins = cumulativeSum(wins);
                    const cumulativeBusts = cumulativeSum(busts);
                    const cumulativeBlackjacks = cumulativeSum(blackjacks);

                    const data = {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Games Played',
                                data: cumulativeTotals,
                                borderColor: 'rgba(59, 130, 246, 1)', // blue-500
                                backgroundColor: 'rgba(59, 130, 246, 0.3)',
                                tension: 0.3,
                            },
                            {
                                label: 'Wins',
                                data: cumulativeWins,
                                borderColor: 'rgba(16, 185, 129, 1)', // green-500
                                backgroundColor: 'rgba(16, 185, 129, 0.3)',
                                tension: 0.3,
                            },
                            {
                                label: 'Busts',
                                data: cumulativeBusts,
                                borderColor: 'rgba(239, 68, 68, 1)', // red-500
                                backgroundColor: 'rgba(239, 68, 68, 0.3)',
                                tension: 0.3,
                            },
                            {
                                label: 'Blackjacks',
                                data: cumulativeBlackjacks,
                                borderColor: 'rgba(234, 179, 8, 1)', // yellow-500
                                backgroundColor: 'rgba(234, 179, 8, 0.3)',
                                tension: 0.3,
                            }
                        ]
                    };

                    const config = {
                        type: 'line',
                        data: data,
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Blackjack Stats Over Time (Cumulative)'
                                },
                                legend: {
                                    position: 'top',
                                    labels: {
                                        color: window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? '#EDEDEC' : '#333'
                                    }
                                },
                            },
                            scales: {
                                x: {
                                    ticks: {
                                        color: window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? '#EDEDEC' : '#333'
                                    },
                                    grid: {
                                        color: window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? '#444' : '#ccc'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? '#EDEDEC' : '#333'
                                    },
                                    grid: {
                                        color: window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? '#444' : '#ccc'
                                    }
                                }
                            }
                        },
                    };

                    // Affichage
                    new Chart(document.getElementById('statsChart'), config);
                </script>

                    
            </div>
            <div class="bg-white dark:bg-[#272727] p-6 shadow-sm rounded-lg">
                <h3 class="text-xl font-bold mb-4 text-center">Money Overview</h3>
                <div class="mt-6">
                    <canvas id="moneyChart" width="600" height="400"></canvas>
                </div>

                <script>
                    const wonHistory = @json($history->pluck('total_won'));
                    const betHistory = @json($history->pluck('total_bet'));

                    // Calcul du profit net
                    const profitHistory = wonHistory.map((won, i) => won - betHistory[i]);

                    // Calculs cumulatifs
                    const cumulativeWon = cumulativeSum(wonHistory);
                    const cumulativeBet = cumulativeSum(betHistory);
                    const cumulativeProfit = cumulativeSum(profitHistory);

                    const moneyData = {
                        labels: labels, // mêmes labels que l'autre graphique (les dates)
                        datasets: [
                            {
                                label: 'Total Winnings ($)',
                                data: cumulativeWon,
                                borderColor: 'rgba(34, 197, 94, 1)', // green-600
                                backgroundColor: 'rgba(34, 197, 94, 0.2)',
                                tension: 0.3,
                            },
                            {
                                label: 'Total Bets ($)',
                                data: cumulativeBet,
                                borderColor: 'rgba(59, 130, 246, 1)', // blue-500
                                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                                tension: 0.3,
                            },
                            {
                                label: 'Net Profit ($)',
                                data: cumulativeProfit,
                                borderColor: 'rgba(234, 88, 12, 1)', // orange-600
                                backgroundColor: 'rgba(234, 88, 12, 0.2)',
                                tension: 0.3,
                            }
                        ]
                    };

                    const moneyConfig = {
                        type: 'line',
                        data: moneyData,
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Cumulative Winnings, Bets, and Net Profit'
                                },
                                legend: {
                                    position: 'top',
                                    labels: {
                                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#EDEDEC' : '#333'
                                    }
                                },
                            },
                            scales: {
                                x: {
                                    ticks: {
                                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#EDEDEC' : '#333'
                                    },
                                    grid: {
                                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#444' : '#ccc'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#EDEDEC' : '#333'
                                    },
                                    grid: {
                                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#444' : '#ccc'
                                    }
                                }
                            }
                        },
                    };

                    new Chart(document.getElementById('moneyChart'), moneyConfig);
                </script>
            </div>

            </div>
        </div>
    </div>
</x-app-layout>
