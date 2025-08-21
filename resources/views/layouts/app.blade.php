<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Pterodactyl' }}</title>

    @include('layouts.head')

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ mix('css/theme.css') }}" />
</head>
<body class="bg-dark text-white font-sans antialiased transition-all">
    <div id="app" class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-card border-r border-gray shadow-lg flex flex-col">
            <div class="p-6">
                <a href="{{ route('index') }}" class="flex items-center space-x-2 text-accent hover:text-white glow transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                    <span class="font-bold text-lg">Pterodactyl</span>
                </a>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex-1 px-4 py-2 space-y-1">
                @foreach($sidebarItems as $item)
                    <a href="{{ $item['url'] }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm text-gray-300 hover:bg-accent hover:text-dark glow transition-all">
                        <span class="{{ $item['icon'] }}"></span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="bg-card border-b border-gray p-4 flex items-center justify-between shadow-lg">
                <div class="flex items-center space-x-4">
                    <button class="text-gray-400 hover:text-accent transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <input type="search" 
                           placeholder="Search Something..." 
                           class="bg-dark border border-gray rounded-xl px-4 py-2 text-sm w-80 focus:outline-none focus:ring-2 focus:ring-accent transition-all" />
                </div>

                <div class="flex items-center space-x-4">
                    @if(auth()->check())
                        <div class="text-sm text-gray-400">👤 {{ auth()->user()->username }}</div>
                    @endif
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6 overflow-y-auto">
                @yield('content')
            </div>
        </main>
    </div>

    @include('layouts.footer')
</body>
</html>
