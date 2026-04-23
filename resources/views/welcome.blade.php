<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('Welcome') }} - {{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <script>
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark');
            }
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body
        class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full max-w-4xl mx-auto text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <flux:button :href="route('dashboard')" variant="primary" wire:navigate>
                            Dashboard
                        </flux:button>
                    @else
                        <flux:button :href="route('login')" wire:navigate>
                            Sign In
                        </flux:button>

                        @if (Route::has('register'))
                            <flux:button :href="route('register')" variant="primary" wire:navigate>
                                Get Started
                            </flux:button>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <div class="flex items-center justify-center w-full lg:grow">

            <main class="flex w-full lg:max-w-4xl max-w-6xl flex-col-reverse gap-10 lg:flex-row lg:items-center">

                <!-- LEFT -->
                <div class="flex-1 space-y-6 text-center lg:text-left">
                    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl dark:text-white">
                        Your personal space to reflect 📝
                    </h1>

                    <p class="text-gray-600 dark:text-gray-400">
                        Capture your thoughts, track your mood, and understand yourself better over time.
                    </p>

                    <!-- MINI CHART -->
                    <div class="flex items-end gap-2 h-16 justify-center lg:justify-start">
                        <div class="w-3 bg-green-400 rounded-sm h-[60%]"></div>
                        <div class="w-3 bg-yellow-400 rounded-sm h-[40%]"></div>
                        <div class="w-3 bg-blue-400 rounded-sm h-[75%]"></div>
                        <div class="w-3 bg-red-400 rounded-sm h-[30%]"></div>
                        <div class="w-3 bg-purple-400 rounded-sm h-[55%]"></div>
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-500">
                        Your mood trends at a glance
                    </p>

                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-center lg:justify-start">
                        @if (Route::has('login'))
                            @auth
                                <flux:button :href="route('dashboard')" variant="primary" wire:navigate>
                                    Dashboard
                                </flux:button>
                            @else
                                @if (Route::has('register'))
                                    <flux:button :href="route('register')" variant="primary" wire:navigate>
                                        Get Started
                                    </flux:button>
                                @endif
                                <flux:button :href="route('login')" wire:navigate>
                                    Sign In
                                </flux:button>
                            @endauth
                        @endif
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="flex-1 relative">

                    <!-- GRADIENT BACKGROUND -->
                    <div class="absolute inset-0 -z-10 blur-2xl opacity-40
                bg-gradient-to-tr from-indigo-400 via-purple-400 to-pink-400
                dark:from-indigo-900 dark:via-purple-900 dark:to-pink-900">
                    </div>

                    <!-- CARD -->
                    <div id="entryCard" class="rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm bg-white dark:bg-[#111]
                       transition duration-300 hover:scale-[1.03] hover:shadow-xl">

                        <p id="entryDate" class="text-sm text-gray-500 mb-2"></p>

                        <h2 id="entryTitle" class="text-lg font-semibold mb-2 dark:text-gray-50"></h2>

                        <p id="entryContent" class="text-gray-600 dark:text-gray-400 text-sm"></p>
                    </div>

                </div>

            </main>
        </div>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif


        <script>
            const entries = [
                {
                    date: "April 20",
                    title: "Feeling productive 💪",
                    content: "Finally finished something I've been putting off. Small steps matter."
                },
                {
                    date: "April 18",
                    title: "A bit tired 😴",
                    content: "Low energy today, but still showed up. That counts."
                },
                {
                    date: "April 15",
                    title: "Motivated 🚀",
                    content: "Got a new idea and can't stop thinking about it!"
                },
                {
                    date: "April 10",
                    title: "Calm and focused 🌿",
                    content: "Everything feels slower today, in a good way."
                }
            ];

            function setRandomEntry() {
                const entry = entries[Math.floor(Math.random() * entries.length)];

                document.getElementById('entryDate').textContent = entry.date;
                document.getElementById('entryTitle').textContent = entry.title;
                document.getElementById('entryContent').textContent = entry.content;
            }

            setRandomEntry();

            // change every 5s
            setInterval(setRandomEntry, 5000);
        </script>
    </body>

</html>