<div class="max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-primary-light rounded-full mb-4">
            <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-text-primary">Sistem Antrian Layanan UGM (UGM Services)</h2>
        <p class="text-text-secondary mt-2">Sistem Antrian Unit Layanan Terpadu</p>
    </div>

    <!-- Service Selection -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$lastQueue): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button
                    wire:click="takeQueue(<?php echo e($service['id']); ?>)"
                    wire:loading.attr="disabled"
                    class="relative group p-8 bg-surface border-2 border-border rounded-2xl
                           hover:border-primary hover:shadow-xl hover:bg-primary-light/30 transition-all duration-200
                           disabled:opacity-50 disabled:cursor-not-allowed text-left min-h-[250px] flex flex-col justify-center"
                >
                    <div class="flex items-center gap-5">
                        <div class="flex-shrink-0 w-16 h-16 bg-primary rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-2xl"><?php echo e($service['prefix']); ?></span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-text-primary group-hover:text-primary transition-colors">
                                <?php echo e($service['name']); ?>

                            </h3>
                            <p class="text-sm text-text-secondary mt-1">
                                Kode: <?php echo e($service['code']); ?>

                            </p>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service['queues_count'] > 0): ?>
                        <div class="mt-4 flex items-center gap-1.5 text-sm text-warning font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span><?php echo e($service['queues_count']); ?> antrian menunggu</span>
                        </div>
                    <?php else: ?>
                        <div class="mt-4 flex items-center gap-1.5 text-sm text-success font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Tersedia</span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Queue Number Display -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lastQueue): ?>
        <div class="text-center queue-item">
            <div class="bg-surface border-2 border-primary rounded-2xl p-8 shadow-xl">
                <div class="w-16 h-16 bg-success/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <p class="text-text-secondary mb-2">Nomor Antrian Anda</p>
                <p class="text-7xl font-bold text-primary mb-2 tracking-wider">
                    <?php echo e($lastQueue['queue_number']); ?>

                </p>
                <p class="text-text-secondary mb-6">
                    <?php echo e(\App\Models\Service::find($selectedServiceId)?->name); ?>

                </p>

                <div class="bg-primary-light rounded-lg p-4 mb-6">
                    <p class="text-text-secondary text-sm">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($waitingAhead > 0): ?>
                            Masih ada <span class="font-bold text-primary"><?php echo e($waitingAhead); ?></span> antrian di depan Anda
                        <?php else: ?>
                            Anda adalah antrian berikutnya! Silakan bersiap.
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>

                <button
                    wire:click="$set('lastQueue', null)"
                    class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors font-medium"
                >
                    Ambil Antrian Lain
                </button>
            </div>

            <p class="text-text-secondary text-sm mt-4">
                Harap menunggu nomor Anda dipanggil dan muncul di layar monitor
            </p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Loading State -->
    <div wire:loading wire:target="takeQueue" class="fixed inset-0 bg-black/30 flex items-center justify-center z-50">
        <div class="bg-surface rounded-xl p-8 shadow-2xl text-center">
            <div class="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
            <p class="text-text-primary font-medium">Memproses...</p>
        </div>
    </div>
</div>
<?php /**PATH D:\AI & Plugin Experiment\sistem_antrian_humas\resources\views/livewire/queue-registration.blade.php ENDPATH**/ ?>