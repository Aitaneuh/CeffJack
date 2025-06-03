<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CeffJack</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- JetBrains Mono -->
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        mono: ['"JetBrains Mono"', 'monospace']
                    }
                }
            }
        };
    </script>
</head>
<body class="bg-[#FDFDFC] dark:bg-[#222222] text-[#1b1b18] flex p-6 lg:p-8 min-h-screen flex-col font-mono">

    <!-- Header navigation -->
    <header class="w-full text-sm mb-[150px] lg:mb-[200px] flex flex-col gap-4">
        @if (Route::has('login'))
        <div class="flex items-center justify-between w-full">
            <p class="dark:text-[#EDEDEC] text-[#1b1b18]">
                See on <b><a href="https://github.com/Aitaneuh" class="underline hover:decoration-red-600">GitHub.com</a></b>
            </p>
            <nav class="flex gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        </div>
        @endif
    </header>

    <!-- Main logo centered -->
    <div class="relative flex justify-center items-center min-h-[300px]">
        <div class="text-center">
            <h1 class="text-[64px] lg:text-[128px] font-mono">
                <span class="text-red-600">Ceff</span><br />
                <span class="dark:text-[#EDEDEC] text-[#1b1b18]">Jack</span>
            </h1>
        </div>

        <!-- Images positionnées à droite sans casser le centrage -->
        <div class="relative ml-10 w-[120px] lg:w-[255px]">
            <img src="{{ asset('images/ace_of_hearts.png') }}" alt="Ace of Hearts" class="rounded shadow-md w-full" />
            <img src="{{ asset('images/king_of_spades.png') }}" alt="King of Spades" class="absolute top-4 left-4 rotate-[30deg] w-full" />
        </div>
    </div>

    <!-- Quote -->
    <p class="mt-[200px] text-[20px] text-center dark:text-[#FDFDFC] text-[#0a0a0a]">
        “I’m not addicted, I just can’t stop playing.”
    </p>

    @if (Route::has('login'))
        <div class="h-14 hidden lg:block"></div>
    @endif
</body>
</html>
