<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-text-primary">{{ $counter->name }}</h2>
            <p class="text-sm text-text-secondary">Melayani Semua Layanan</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm
                {{ count($waitingQueues) > 0 ? 'bg-warning/10 text-warning' : 'bg-success/10 text-success' }}">
                <span class="w-2 h-2 rounded-full {{ count($waitingQueues) > 0 ? 'bg-warning' : 'bg-success' }}"></span>
                {{ count($waitingQueues) }} antrian
            </span>
        </div>
    </div>

    <!-- Current Queue -->
    @if($currentQueue)
        <div class="bg-primary-light border-2 border-primary rounded-2xl p-8 mb-6 text-center called-card">
            <p class="text-text-secondary text-sm mb-2">Sedang Melayani</p>
            <p class="text-6xl font-bold text-primary mb-2 tracking-wider">
                {{ $currentQueue->queue_number }}
            </p>
            <p class="text-text-secondary text-sm mb-4">
                Dipanggil {{ $currentQueue->call_count }}x
            </p>

            <div class="flex items-center justify-center gap-3">
                @if($currentQueue->status === 'called')
                    <button
                        wire:click="startService"
                        class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors font-medium flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Mulai Layanan
                    </button>
                @endif

                @if($currentQueue->status === 'serving')
                    <button
                        wire:click="completeService"
                        class="px-6 py-3 bg-success text-white rounded-lg hover:bg-success/90 transition-colors font-medium flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Selesai
                    </button>
                @endif

                <button
                    wire:click="recall"
                    class="px-4 py-3 bg-secondary text-primary rounded-lg hover:bg-secondary-hover transition-colors font-medium flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Panggil Ulang
                </button>
            </div>
        </div>
    @else
        <div class="bg-surface border-2 border-dashed border-border rounded-2xl p-12 mb-6 text-center">
            <div class="w-16 h-16 bg-primary-light rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                </svg>
            </div>
            <p class="text-text-secondary">Belum ada antrian yang dipanggil</p>
            <p class="text-text-secondary text-sm mt-1">Klik tombol di bawah untuk memanggil antrian berikutnya</p>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex gap-3 mb-6">
        <button
            wire:click="callNext"
            wire:loading.attr="disabled"
            class="flex-1 px-6 py-4 bg-success text-white rounded-xl hover:bg-success/90 transition-all font-bold text-lg
                   disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-success/20
                   flex items-center justify-center gap-2"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
            Panggil Berikutnya
        </button>
    </div>

    <!-- Waiting List -->
    <div class="bg-surface rounded-xl border border-border">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between">
            <h3 class="font-semibold text-text-primary">Daftar Antrian</h3>
            <span class="text-sm text-text-secondary">{{ count($waitingQueues) }} menunggu</span>
        </div>

        @if(count($waitingQueues) > 0)
            <div class="divide-y divide-border max-h-96 overflow-y-auto">
                @foreach($waitingQueues as $index => $queue)
                    <div class="flex items-center justify-between px-4 py-3 hover:bg-primary-light/50 transition-colors queue-item">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-primary-light text-primary font-bold text-sm flex items-center justify-center">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <p class="font-semibold text-text-primary">{{ $queue->queue_number }}</p>
                                <p class="text-xs text-text-secondary">
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-primary-light text-primary font-medium">{{ $queue->service->name }}</span>
                                    {{ \Carbon\Carbon::parse($queue->created_at)->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <button
                            wire:click="skipQueue({{ $queue->id }})"
                            class="px-3 py-1.5 text-sm text-error hover:bg-error/10 rounded-lg transition-colors"
                        >
                            Lewati
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-4 py-8 text-center text-text-secondary">
                <p>Tidak ada antrian yang menunggu</p>
            </div>
        @endif
    </div>
</div>
