<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Antrian Humas - TV Display</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body {
            background: linear-gradient(135deg, #0B457F 0%, #083764 100%);
            overflow: hidden;
            margin: 0;
            width: 100vw;
            height: 100vh;
        }
        /* Fixed FHD (1920x1080) canvas, scaled to fit any screen */
        .tv-stage {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .tv-container {
            width: 1920px;
            height: 1080px;
            flex-shrink: 0;
            transform-origin: center center;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #0B457F 0%, #083764 100%);
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(255, 212, 43, 0.3); }
            50% { box-shadow: 0 0 40px rgba(255, 212, 43, 0.6); }
        }
        .called-card {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        @keyframes slide-in {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .queue-item {
            animation: slide-in 0.3s ease-out;
        }
    </style>
</head>
<body>
    <div class="tv-stage" id="tv-stage">
        <div class="tv-container" id="tv-container">
            {{ $slot }}
        </div>
    </div>

    <script>
        // Scale the fixed 1920x1080 canvas to fit the current screen
        function fitTvToScreen() {
            const stage = document.getElementById('tv-stage');
            const container = document.getElementById('tv-container');
            if (!stage || !container) return;
            const scale = Math.min(
                window.innerWidth / 1920,
                window.innerHeight / 1080
            );
            container.style.transform = 'scale(' + scale + ')';
        }
        window.addEventListener('resize', fitTvToScreen);
        window.addEventListener('load', fitTvToScreen);
        document.addEventListener('DOMContentLoaded', fitTvToScreen);
        fitTvToScreen();
    </script>

    @livewireScripts
    @stack('scripts')
</body>
</html>
