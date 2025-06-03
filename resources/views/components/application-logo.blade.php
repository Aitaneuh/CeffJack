<div class="relative flex justify-center items-center">
        <div class="text-center">
            <h1 class="text-[50px] font-mono">
                <span class="text-red-600">Ceff</span><br />
                <span class="dark:text-[#EDEDEC] text-[#1b1b18]">Jack</span>
            </h1>
        </div>

        <!-- Images positionnées à droite sans casser le centrage -->
        <div class="relative ml-3 w-[100px]">
            <img src="{{ asset('images/ace_of_hearts.png') }}" alt="Ace of Hearts" class="rounded shadow-md w-full" />
            <img src="{{ asset('images/king_of_spades.png') }}" alt="King of Spades" class="absolute top-2 left-2 rotate-[30deg] w-full" />
        </div>
    </div>
