<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Antrian Humas' }} - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-background text-text-primary font-sans antialiased">
    <div class="min-h-screen relative">
        <!-- Background Image -->
        <div class="fixed inset-0 -z-10">
            <img src="{{ asset('images/background.png') }}" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-white/80"></div>
        </div>
        <!-- Header -->
        <header class="bg-primary text-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center">
                            <img src="{{ asset('images/logo_ugm_putih.png') }}" alt="Logo UGM" class="h-15 w-auto">
                        </div>
                        <div>
                            <h1 class="text-lg font-bold">Sistem Antrian Layanan UGM</h1>
                            <p class="text-xs text-blue-200">UGM Services</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <nav class="hidden sm:flex items-center gap-4 text-sm">
                            <a href="{{ route('queue.registration') }}" class="text-blue-200 hover:text-white transition-colors {{ request()->routeIs('queue.registration') ? 'text-white font-semibold' : '' }}">
                                Ambil Antrian
                            </a>
                            @if(!request()->routeIs('queue.registration'))
                                <a href="{{ route('queue.admin') }}" class="text-blue-200 hover:text-white transition-colors {{ request()->routeIs('queue.admin') ? 'text-white font-semibold' : '' }}">
                                    Admin
                                </a>
                            @endif
                        </nav>
                        <div class="text-sm text-blue-200">
                            {{ now()->format('d F Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
