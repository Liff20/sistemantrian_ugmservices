<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Antrian Humas - TV Display</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <style>
        body {
            background: linear-gradient(135deg, #0B457F 0%, #083764 100%);
            overflow: hidden;
        }
        .tv-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
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
<body class="tv-container">
    <?php echo e($slot); ?>


    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\AI & Plugin Experiment\sistem_antrian_humas\resources\views/layouts/tv.blade.php ENDPATH**/ ?>