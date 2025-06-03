<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-[#EDEDEC] leading-tight">
            {{ __('Blackjack Rules') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-[#222222] text-black dark:text-[#EDEDEC]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-[#272727] p-6 shadow-sm rounded-lg space-y-6">
                <h3 class="text-2xl font-bold">Objective</h3>
                <p>
                    The goal of Blackjack is to beat the dealer by getting a hand value as close as possible to <strong>21</strong> without going over.
                </p>

                <h3 class="text-2xl font-bold">Card Values</h3>
                <ul class="list-disc list-inside space-y-1">
                    <li>Number cards are worth their face value (2–10).</li>
                    <li>Face cards (Jack, Queen, King) are worth <strong>10</strong> points.</li>
                    <li>Aces are worth <strong>1 or 11</strong>, whichever is more favorable for your hand.</li>
                </ul>

                <h3 class="text-2xl font-bold">How to Play</h3>
                <ol class="list-decimal list-inside space-y-1">
                    <li>You start with two cards. The dealer also gets two cards (one hidden).</li>
                    <li>You can choose to:
                        <ul class="ml-4 list-disc">
                            <li><strong>Hit</strong>: draw another card.</li>
                            <li><strong>Stand</strong>: keep your current hand.</li>
                        </ul>
                    </li>
                    <li>If your hand exceeds 21, you <strong>bust</strong> and lose.</li>
                    <li>When you stand, the dealer reveals their hidden card and draws until reaching at least 17.</li>
                    <li>Whoever is closer to 21 without busting wins. A tie is a <strong>push</strong>.</li>
                </ol>

                <h3 class="text-2xl font-bold">Blackjack</h3>
                <p>
                    A "Blackjack" is when your first two cards are an Ace and a 10-point card. It's the best possible hand!
                </p>

                <h3 class="text-2xl font-bold">Important Notes</h3>
                <ul class="list-disc list-inside space-y-1">
                    <li>If both the player and the dealer bust, the dealer wins.</li>
                    <li>The dealer must hit until they reach at least 17.</li>
                    <li>No splitting, doubling down or insurance is available in this simplified version.</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
