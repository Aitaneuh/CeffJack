<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-[#EDEDEC] leading-tight">
            {{ __('History') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-[#222222]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @forelse ($games as $entry)
                <div class="bg-white dark:bg-[#272727] shadow-md rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $entry->created_at->format('d M Y, H:i') }}
                        </span>

                        <span class="text-sm font-semibold 
                            @if($entry['result'] === 'won') text-green-600 
                            @elseif($entry['result'] === 'lost') text-red-600 
                            @else text-yellow-600 @endif">
                            {{ ucfirst($entry['result']) }}
                        </span>
                    </div>

                    <div class="mb-2">
                        <h3 class="font-bold text-gray-800 dark:text-[#EDEDEC] mb-1">Dealer's Hand:</h3>
                        <div class="flex space-x-4 items-center">
                            @foreach ($entry['dealer'] as $card)
                                <img 
                                    src="{{ asset('images/' . $card['card'] . '.png') }}" 
                                    alt="{{ $card['card'] }}" 
                                    class="w-16 h-auto rounded"
                                    loading="lazy"
                                >
                            @endforeach
                            <span class="ml-4 font-bold dark:text-[#EDEDEC]">[Total: {{ $entry['dealer_total'] }}]</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h3 class="font-bold text-gray-800 dark:text-[#EDEDEC] mb-1">Your Hand:</h3>
                        <div class="flex space-x-4 items-center">
                            @foreach ($entry['player'] as $card)
                                <img 
                                    src="{{ asset('images/' . $card['card'] . '.png') }}" 
                                    alt="{{ $card['card'] }}" 
                                    class="w-16 h-auto rounded"
                                    loading="lazy"
                                />
                            @endforeach
                            <span class="ml-4 font-bold dark:text-[#EDEDEC]">[Total: {{ $entry['player_total'] }}]</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-600 dark:text-gray-300 py-12">
                    No games played yet.
                </div>
            @endforelse
            <div class="mt-8 flex justify-center">
                {{ $games->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
