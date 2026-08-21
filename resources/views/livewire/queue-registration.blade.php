<div class="max-w-4xl mx-auto" wire:poll.2s="refreshServices">
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
    @if(!$lastQueue && !$pendingServiceId)
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach($services as $service)
                <button
                    wire:click="showContactForm({{ $service['id'] }})"
                    wire:loading.attr="disabled"
                    class="relative group p-8 bg-surface border-2 border-border rounded-2xl
                           hover:border-primary hover:shadow-xl hover:bg-primary-light/30 transition-all duration-200
                           disabled:opacity-50 disabled:cursor-not-allowed text-left min-h-[250px] flex flex-col justify-center"
                >
                    <div class="flex items-center gap-5">
                        <div class="flex-shrink-0 w-16 h-16 bg-primary rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-2xl">{{ $service['prefix'] }}</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-text-primary group-hover:text-primary transition-colors">
                                {{ $service['name'] }}
                            </h3>
                            <p class="text-sm text-text-secondary mt-1">
                                Kode: {{ $service['code'] }}
                            </p>
                        </div>
                    </div>
                    @if($service['queues_count'] > 0)
                        <div class="mt-4 flex items-center gap-1.5 text-sm text-warning font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $service['queues_count'] }} antrian menunggu</span>
                        </div>
                    @else
                        <div class="mt-4 flex items-center gap-1.5 text-sm text-success font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Tersedia</span>
                        </div>
                    @endif
                </button>
            @endforeach
        </div>
    @endif

    <!-- Contact Form (email + WhatsApp) before issuing number -->
    @if(!$lastQueue && $pendingServiceId)
        @php($pendingService = \App\Models\Service::find($pendingServiceId))
        <div class="max-w-lg mx-auto">
            <div class="bg-surface border-2 border-primary rounded-2xl p-8 shadow-xl">
                <div class="text-center mb-6">
                    <h3 class="text-xl font-bold text-text-primary">Lengkapi Data Anda</h3>
                    <p class="text-text-secondary mt-1 text-sm">
                        Layanan: <span class="font-semibold text-primary">{{ $pendingService?->name ?? '-' }}</span>
                    </p>
                    <p class="text-text-secondary mt-1 text-xs">
                        Email dan nomor WhatsApp Anda digunakan untuk mengirim Survei Kepuasan Masyarakat (SKM).
                    </p>
                </div>

                <form wire:submit="takeQueue({{ $pendingServiceId }})" class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-text-primary mb-1">Email</label>
                        <input
                            type="email"
                            id="email"
                            wire:model="email"
                            placeholder="contoh@email.com"
                            class="w-full px-4 py-2.5 bg-white border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary"
                        >
                        @error('email')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="whatsapp" class="block text-sm font-medium text-text-primary mb-1">Nomor WhatsApp</label>
                        <input
                            type="tel"
                            id="whatsapp"
                            wire:model="whatsapp"
                            inputmode="numeric"
                            placeholder="08xxxxxxxxxx"
                            class="w-full px-4 py-2.5 bg-white border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary"
                        >
                        @error('whatsapp')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button
                            type="button"
                            wire:click="cancelContactForm"
                            class="flex-1 px-4 py-3 border-2 border-border rounded-lg text-text-secondary hover:bg-surface-hover transition-colors font-medium"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="flex-1 px-4 py-3 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors font-medium disabled:opacity-50"
                        >
                            Ambil Nomor Antrian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Queue Number Display -->
    @if($lastQueue)
        <div class="text-center queue-item">
            <div class="bg-surface border-2 border-primary rounded-2xl p-8 shadow-xl">
                <div class="w-16 h-16 bg-success/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <p class="text-text-secondary mb-2">Nomor Antrian Anda</p>
                <p class="text-7xl font-bold text-primary mb-2 tracking-wider">
                    {{ $lastQueue['queue_number'] }}
                </p>
                <p class="text-text-secondary mb-6">
                    {{ \App\Models\Service::find($selectedServiceId)?->name }}
                </p>

                <div class="bg-primary-light rounded-lg p-4 mb-6">
                    <p class="text-text-secondary text-sm">
                        @if($waitingAhead > 0)
                            Masih ada <span class="font-bold text-primary">{{ $waitingAhead }}</span> antrian di depan Anda
                        @else
                            Anda adalah antrian berikutnya! Silakan bersiap.
                        @endif
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
    @endif

    <!-- Loading State -->
    <div wire:loading wire:target="takeQueue" class="fixed inset-0 bg-black/30 flex items-center justify-center z-50">
        <div class="bg-surface rounded-xl p-8 shadow-2xl text-center">
            <div class="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
            <p class="text-text-primary font-medium">Memproses...</p>
        </div>
    </div>
</div>
