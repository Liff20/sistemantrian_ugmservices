<div class="h-full flex flex-col p-6" x-data="{ currentTime: '' }" x-init="
    currentTime = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    setInterval(() => {
        currentTime = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }, 1000);
">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <div class="w-20 h-10 flex items-center justify-center">
                            <img src="<?php echo e(asset('images/logo_ugm_putih.png')); ?>" alt="Logo UGM" class="h-19 w-auto">
                        </div>
            <div>
                <h1 class="text-4xl font-bold text-white tracking-wide">Sistem Antrian Layanan UGM</h1>
                <p class="text-xl text-blue-200">UGM Services</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-4xl font-bold text-white" x-text="currentTime"></p>
            <p class="text-lg text-blue-200"><?php echo e(now()->format('d F Y')); ?></p>
        </div>
    </div>

    <div class="flex-1 grid grid-cols-3 gap-6">
        <!-- Current Calls -->
        <div class="col-span-2">
            <h2 class="text-2xl font-bold text-secondary mb-4 text-center uppercase tracking-wider">
                Sekarang Dipanggil
            </h2>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($currentCalls) > 0): ?>
                <div class="grid grid-cols-2 gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $currentCalls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $call): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="called-card bg-surface rounded-2xl p-8 text-center border-4 border-secondary">
                            <p class="text-lg text-text-secondary mb-2"><?php echo e($call->counter->name ?? 'Loket'); ?></p>
                            <p class="text-7xl font-bold text-primary mb-2 tracking-wider">
                                <?php echo e($call->queue_number); ?>

                            </p>
                            <p class="text-lg text-text-secondary">
                                <?php echo e($call->service->name ?? ''); ?>

                            </p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($call->status === 'called'): ?>
                                <div class="mt-3 inline-flex items-center gap-2 px-4 py-1 bg-warning/10 text-warning rounded-full text-sm">
                                    <span class="w-2 h-2 bg-warning rounded-full animate-pulse"></span>
                                    Baru Dipanggil
                                </div>
                            <?php else: ?>
                                <div class="mt-3 inline-flex items-center gap-2 px-4 py-1 bg-success/10 text-success rounded-full text-sm">
                                    <span class="w-2 h-2 bg-success rounded-full"></span>
                                    Sedang Dilayani
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php else: ?>
                <div class="bg-white/10 rounded-2xl p-12 text-center">
                    <p class="text-3xl text-white/60">Belum ada panggilan</p>
                    <p class="text-lg text-white/40 mt-2">Silakan menunggu petugas memanggil nomor antrian</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Waiting List -->
        <div class="bg-white/10 rounded-2xl p-6 flex flex-col">
            <h2 class="text-2xl font-bold text-secondary mb-4 text-center uppercase tracking-wider">
                Antrian
            </h2>

            <div class="flex-1 overflow-y-auto space-y-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $waitingQueues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $queue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="queue-item bg-white/20 rounded-xl px-4 py-3 flex items-center justify-between">
                        <span class="text-2xl font-bold text-white tracking-wider">
                            <?php echo e($queue->queue_number); ?>

                        </span>
                        <span class="text-sm text-blue-200">
                            <?php echo e($queue->service->name ?? ''); ?>

                        </span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-8">
                        <p class="text-xl text-white/60">Tidak ada antrian</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Stats -->
            <div class="mt-4 pt-4 border-t border-white/20">
                <div class="grid grid-cols-2 gap-3">
                    <?php
                        $services = \App\Models\Service::where('is_active', true)->get();
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white/10 rounded-lg p-3 text-center">
                            <p class="text-sm text-blue-200"><?php echo e($service->name); ?></p>
                            <p class="text-2xl font-bold text-white">
                                <?php echo e($waitingCounts[$service->id] ?? 0); ?>

                            </p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-6 text-center">
        <p class="text-blue-200 text-sm">
            Harap menunggu nomor antrian Anda dipanggil
        </p>
    </div>

    <!-- Auto-refresh polling -->
    <div wire:poll.5s></div>
</div>
<?php /**PATH D:\AI & Plugin Experiment\sistem_antrian_humas\resources\views/livewire/tv-dashboard.blade.php ENDPATH**/ ?>