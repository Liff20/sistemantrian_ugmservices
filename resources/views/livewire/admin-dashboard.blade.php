<div class="max-w-4xl mx-auto" wire:poll.2s>
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-primary-light rounded-full mb-4">
            <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9V5m0 0V3m0 2h2m-2 0H9m6 4h6m-6 4h6m-6 4h6M5 9h2m0 0V7m0 2v2m0 0H5m2 0h2m6 4v2m0 0h-2m2 0h2" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-text-primary">Panel Admin</h2>
        <p class="text-text-secondary mt-2">Kelola dan monitor sistem antrian</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="bg-surface border border-border rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-primary">{{ $stats['total_queues'] }}</p>
            <p class="text-sm text-text-secondary mt-1">Total Antrian</p>
        </div>
        <div class="bg-surface border border-border rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-warning">{{ $stats['waiting_queues'] }}</p>
            <p class="text-sm text-text-secondary mt-1">Menunggu</p>
        </div>
        <div class="bg-surface border border-border rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-info">{{ $stats['called_queues'] + $stats['serving_queues'] }}</p>
            <p class="text-sm text-text-secondary mt-1">Dipanggil/Dilayani</p>
        </div>
        <div class="bg-surface border border-border rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-success">{{ $stats['completed_queues'] }}</p>
            <p class="text-sm text-text-secondary mt-1">Selesai</p>
        </div>
    </div>

    <!-- Per-Service Stats -->
    <div class="bg-surface border border-border rounded-xl p-6 mb-8">
        <h3 class="text-lg font-semibold text-text-primary mb-4">Statistik per Layanan</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-text-secondary border-b border-border">
                        <th class="text-left py-3 px-4">Layanan</th>
                        <th class="text-center py-3 px-4">Kode</th>
                        <th class="text-center py-3 px-4">Prefix</th>
                        <th class="text-center py-3 px-4">Nomor Terakhir</th>
                        <th class="text-center py-3 px-4">Total Antrian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stats['services'] as $service)
                        <tr class="border-b border-border/50 hover:bg-primary-light/30 transition-colors">
                            <td class="py-3 px-4 font-medium text-text-primary">{{ $service->name }}</td>
                            <td class="text-center py-3 px-4 text-text-secondary">{{ $service->code }}</td>
                            <td class="text-center py-3 px-4">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-primary-light rounded-lg text-primary font-bold">
                                    {{ $service->prefix }}
                                </span>
                            </td>
                            <td class="text-center py-3 px-4 font-mono text-text-primary">{{ $service->prefix }}{{ str_pad($service->current_number, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="text-center py-3 px-4">
                                <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 bg-primary-light rounded-full text-primary font-semibold text-xs">
                                    {{ $service->queues_count }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Download Section -->
    <div class="bg-surface border border-border rounded-xl p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-text-primary">Download Rekap Harian</h3>
                <p class="text-sm text-text-secondary mt-1">Download data antrian dalam format CSV (dapat dibuka di Excel)</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('queue.download', ['date' => now()->format('Y-m-d')]) }}"
               class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors font-medium text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download Hari Ini ({{ now()->format('d/m/Y') }})
            </a>
        </div>
    </div>

    <!-- Reset Section -->
    <div class="bg-surface border border-border rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-text-primary">Reset Sistem</h3>
                <p class="text-sm text-text-secondary mt-1">Hapus semua data antrian dan reset nomor ke awal</p>
            </div>
            @if(!$showConfirmReset)
                <button
                    wire:click="confirmReset"
                    class="px-6 py-2.5 bg-danger text-white rounded-lg hover:bg-danger/90 transition-colors font-medium text-sm flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Reset Semua
                </button>
            @endif
        </div>

        <!-- Confirmation -->
        @if($showConfirmReset)
            <div class="bg-danger/5 border-2 border-danger/20 rounded-xl p-6">
                <div class="flex items-start gap-3 mb-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-danger/10 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-danger text-lg">Konfirmasi Reset</h4>
                        <p class="text-text-secondary text-sm mt-1">
                            Tindakan ini akan <strong class="text-danger">menghapus semua data antrian</strong> dan 
                            <strong class="text-danger">mereset nomor urut</strong> ke awal. Tindakan ini tidak dapat dibatalkan!
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-text-secondary mb-2">
                        Ketik <strong class="text-danger font-mono">reset</strong> untuk konfirmasi:
                    </label>
                    <input
                        type="text"
                        wire:model.live="confirmText"
                        placeholder="Ketik 'reset' di sini..."
                        class="w-full px-4 py-2.5 bg-background border-2 rounded-lg text-text-primary placeholder-text-secondary/50 focus:outline-none transition-colors
                            @if($resetStatus === 'error') border-danger @else border-border focus:border-primary @endif"
                    >
                    @if($resetStatus === 'error')
                        <p class="text-danger text-sm mt-1">Silakan ketik "reset" untuk konfirmasi.</p>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <button
                        wire:click="resetAll"
                        wire:loading.attr="disabled"
                        class="px-6 py-2.5 bg-danger text-white rounded-lg hover:bg-danger/90 transition-colors font-medium text-sm disabled:opacity-50"
                    >
                        <span wire:loading.remove>Ya, Reset Semua</span>
                        <span wire:loading>Mereset...</span>
                    </button>
                    <button
                        wire:click="cancelReset"
                        class="px-6 py-2.5 bg-surface border border-border text-text-secondary rounded-lg hover:bg-background transition-colors font-medium text-sm"
                    >
                        Batal
                    </button>
                </div>
            </div>
        @endif

        <!-- Success Message -->
        @if($resetStatus === 'success')
            <div class="mt-4 bg-success/5 border border-success/20 rounded-xl p-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-success text-sm">Sistem berhasil di-reset! Semua data antrian telah dihapus dan nomor urut dikembalikan ke awal.</p>
            </div>
        @endif
    </div>
</div>
