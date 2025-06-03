<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-[#EDEDEC] leading-tight">
            Admin Statistics
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-[#222222] text-black dark:text-[#EDEDEC]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Section : Overview Cards --}}
            <div class="bg-white dark:bg-[#272727] p-6 shadow-sm rounded-lg">
                <h3 class="text-2xl font-bold text-center mb-5">Overall Site Overview</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-lg">
                    <!-- Total Users -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Total Users:</span>
                        <span class="float-right">{{ $stats['total_users'] }}</span>
                    </div>

                    <!-- Total Games Played -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Games Played:</span>
                        <span class="float-right">{{ $stats['total_games'] }}</span>
                    </div>

                    <!-- Total Bets -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Total Bets Placed:</span>
                        <span class="float-right">{{ number_format($stats['total_bets'], 2) }} $</span>
                    </div>

                    <!-- Total Payouts -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Total Payouts:</span>
                        <span class="float-right">{{ number_format($stats['total_payouts'], 2) }} $</span>
                    </div>

                    <!-- Total Profit -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Total Profit:</span>
                        <span class="float-right">{{ number_format($stats['total_profit'], 2) }} $</span>
                    </div>

                    <!-- Average Bet -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Average Bet:</span>
                        <span class="float-right">{{ number_format($stats['average_bet'], 2) }} $</span>
                    </div>

                    <!-- Biggest Win -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Biggest Win:</span>
                        <span class="float-right">{{ number_format($stats['biggest_win'], 2) }} $</span>
                    </div>

                    <!-- Biggest Loss -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Biggest Loss:</span>
                        <span class="float-right">-{{ number_format($stats['biggest_loss'], 2) }} $</span>
                    </div>

                    <!-- Current Active Users -->
                    <div class="bg-gray-100 dark:bg-[#1e1e1e] p-4 rounded-lg shadow">
                        <span class="font-semibold">Users Active Now:</span>
                        <span class="float-right">{{ $stats['users_online'] }}</span>
                    </div>
                </div>
            </div>
            
            {{-- Section : Stats Chart --}}
            <div class="bg-white dark:bg-[#272727] p-6 shadow-sm rounded-lg mt-8">
                <h3 class="text-2xl font-bold text-center mb-5">Global Stats Over Time (Cumulative)</h3>

                <div class="mt-4">
                    <canvas id="statsChart" width="600" height="400"></canvas>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const labels = @json($stats['history']->pluck('date'));
                    const totals = @json($stats['history']->pluck('total'));
                    const wins = @json($stats['history']->pluck('wins'));
                    const busts = @json($stats['history']->pluck('busts'));
                    const blackjacks = @json($stats['history']->pluck('blackjacks'));

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
                                    text: 'Global Stats Over Time (Cumulative)'
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

                    new Chart(document.getElementById('statsChart'), config);
                </script>
            </div>
            {{-- Section : Financial Graph --}}
            <div class="bg-white dark:bg-[#272727] p-6 shadow-sm rounded-lg mt-8">
                <h3 class="text-2xl font-bold text-center mb-5">Cumulative Money Flow Over Time</h3>

                <div class="mt-4">
                    <canvas id="moneyChart" width="600" height="400"></canvas>
                </div>

                <script>
                    const betHistory = @json($stats['history']->pluck('total_bets'));
                    const payoutHistory = @json($stats['history']->pluck('total_payouts'));

                    const cumulativeBets = cumulativeSum(betHistory);
                    const cumulativePayouts = cumulativeSum(payoutHistory);

                    const financialData = {
                        labels: labels, // same labels as first chart
                        datasets: [
                            {
                                label: 'Cumulative Bets',
                                data: cumulativeBets,
                                borderColor: 'rgba(37, 99, 235, 1)', // blue-600
                                backgroundColor: 'rgba(37, 99, 235, 0.3)',
                                tension: 0.3,
                            },
                            {
                                label: 'Cumulative Payouts',
                                data: cumulativePayouts,
                                borderColor: 'rgba(132, 204, 22, 1)', // lime-500
                                backgroundColor: 'rgba(132, 204, 22, 0.3)',
                                tension: 0.3,
                            },
                            {
                                label: 'Profit (Payouts - Bets)',
                                data: cumulativePayouts.map((val, i) => val - cumulativeBets[i]),
                                borderColor: 'rgba(249, 115, 22, 1)', // orange-500
                                backgroundColor: 'rgba(249, 115, 22, 0.2)',
                                borderDash: [5, 5],
                                tension: 0.3,
                            }
                        ]
                    };

                    const financialConfig = {
                        type: 'line',
                        data: financialData,
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Cumulative Money Flow (Bets, Payouts & Profit)'
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

                    new Chart(document.getElementById('moneyChart'), financialConfig);
                </script>
            </div>

        </div>
    </div>
</x-app-layout>
