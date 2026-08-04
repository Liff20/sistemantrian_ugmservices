<div class="w-[1920px] h-[1080px] flex flex-col py-8 px-6" x-data="{ currentTime: '' }" x-init="
    currentTime = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    setInterval(() => {
        currentTime = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }, 1000);
">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="w-20 h-20 bg-primary rounded-2xl flex items-center justify-center p-2">
                <img src="/images/logo_ugm_putih.png" alt="Logo UGM" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="text-5xl font-bold text-white tracking-wide">Sistem Antrian Layanan Terpadu UGM</h1>
                <p class="text-2xl text-blue-200">UGM Services</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-5xl font-bold text-white" x-text="currentTime"></p>
            <p class="text-2xl text-blue-200">{{ now()->format('d F Y') }}</p>
        </div>
    </div>

    <div class="flex-1 grid grid-cols-3 gap-6 min-h-0">
        <!-- Current Calls -->
        <div class="col-span-2 flex flex-col min-h-0">
            <h2 class="text-3xl font-bold text-secondary mb-4 text-center uppercase tracking-wider">
                Sekarang Dipanggil
            </h2>

            <!-- Current Calls (fixed height so video box stays in place) -->
            <div class="h-[280px] flex-shrink-0">
                @if(count($currentCalls) > 0)
                    <div class="grid grid-cols-2 gap-4 h-full">
                        @foreach($currentCalls as $call)
                            <div class="called-card bg-surface rounded-2xl p-8 text-center border-4 border-secondary flex flex-col items-center justify-center">
                                <p class="text-xl text-text-secondary mb-2">{{ $call->counter->name ?? 'Loket' }}</p>
                                <p class="text-8xl font-bold text-primary mb-2 tracking-wider leading-none">
                                    {{ $call->queue_number }}
                                </p>
                                <p class="text-xl text-text-secondary">
                                    {{ $call->service->name ?? '' }}
                                </p>
                                @if($call->status === 'called')
                                    <div class="mt-3 inline-flex items-center gap-2 px-4 py-1 bg-warning/10 text-warning rounded-full text-sm">
                                        <span class="w-2 h-2 bg-warning rounded-full animate-pulse"></span>
                                        Baru Dipanggil
                                    </div>
                                @else
                                    <div class="mt-3 inline-flex items-center gap-2 px-4 py-1 bg-success/10 text-success rounded-full text-sm">
                                        <span class="w-2 h-2 bg-success rounded-full"></span>
                                        Sedang Dilayani
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white/10 rounded-2xl text-center flex flex-col items-center justify-center h-full">
                        <p class="text-4xl text-white/60">Belum ada panggilan</p>
                        <p class="text-2xl text-white/40 mt-2">Silakan menunggu petugas memanggil nomor antrian</p>
                    </div>
                @endif
            </div>

            <!-- Video Player (auto-play, loop, multiple videos) -->
            <div class="mt-4 bg-transparent rounded-2xl overflow-hidden flex-1 min-h-0 relative"
                 x-data="{
                    videos: [
                        'video_ (1).mp4',
                        'video_ (2).mp4',
                        'video_ (3).mp4',
                        'video_ (4).mp4'
                    ],
                    currentIndex: 0,
                    soundEnabled: false,
                    isMuted: true,
                    videoEl: null,
                    init() {
                        this.videoEl = this.$el.querySelector('video');
                        let basePath = '/videos/';
                        this.videoEl.addEventListener('ended', () => {
                            this.currentIndex = (this.currentIndex + 1) % this.videos.length;
                            this.videoEl.src = basePath + encodeURIComponent(this.videos[this.currentIndex]);
                            this.videoEl.play();
                        });
                        // Try to autoplay with sound; if blocked, show the enable-sound overlay
                        this.videoEl.play().then(() => {
                            this.soundEnabled = true;
                        }).catch(() => {
                            this.soundEnabled = false;
                        });
                    },
                    enableSound() {
                        this.videoEl.muted = false;
                        this.videoEl.volume = 1.0;
                        this.videoEl.play();
                        this.soundEnabled = true;
                        this.isMuted = false;
                    },
                    nextVideo() {
                        this.currentIndex = (this.currentIndex + 1) % this.videos.length;
                        this.videoEl.src = '/videos/' + encodeURIComponent(this.videos[this.currentIndex]);
                        this.videoEl.play();
                    },
                    prevVideo() {
                        this.currentIndex = (this.currentIndex - 1 + this.videos.length) % this.videos.length;
                        this.videoEl.src = '/videos/' + encodeURIComponent(this.videos[this.currentIndex]);
                        this.videoEl.play();
                    },
                    toggleMute() {
                        this.videoEl.muted = !this.videoEl.muted;
                        this.isMuted = this.videoEl.muted;
                        if (!this.videoEl.muted) {
                            this.videoEl.volume = 1.0;
                            this.soundEnabled = true;
                        }
                    }
                 }">
                <video class="w-full h-full object-contain bg-transparent" autoplay muted playsinline
                       :src="'/videos/' + encodeURIComponent(videos[currentIndex])">
                    Browser tidak mendukung video.
                </video>

                <!-- Previous button (left side) -->
                <button @click="prevVideo()"
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-14 h-14 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center transition z-20">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                    </svg>
                </button>

                <!-- Next button (right side) -->
                <button @click="nextVideo()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-14 h-14 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center transition z-20">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.59 16.59 10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                    </svg>
                </button>

                <!-- Mute/Unmute button (bottom right) -->
                <button @click="toggleMute()"
                        class="absolute bottom-3 right-3 w-12 h-12 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center transition z-20">
                    <svg x-show="isMuted" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3 3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4 9.91 6.09 12 8.18V4z"/>
                    </svg>
                    <svg x-show="!isMuted" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                    </svg>
                </button>

                <!-- Enable sound overlay (shown only when autoplay with sound is blocked) -->
                <div x-show="!soundEnabled"
                     x-transition.opacity
                     class="absolute inset-0 flex items-center justify-center bg-black/50 cursor-pointer z-10"
                     @click="enableSound()">
                    <div class="flex flex-col items-center gap-3 text-white">
                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                        </svg>
                        <span class="text-2xl font-semibold">Klik untuk mengaktifkan suara</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Waiting List -->
        <div class="flex flex-col min-h-0">
            <h2 class="text-3xl font-bold text-secondary mb-4 text-center uppercase tracking-wider">
                Antrian
            </h2>

            <div class="bg-white/10 rounded-2xl p-6 flex flex-col flex-1 min-h-0">
            <div class="flex-1 overflow-y-auto space-y-2 min-h-0">
                @forelse($waitingQueues as $queue)
                    <div class="queue-item bg-white/20 rounded-xl px-4 py-3 flex items-center justify-between">
                        <span class="text-3xl font-bold text-white tracking-wider">
                            {{ $queue->queue_number }}
                        </span>
                        <span class="text-lg text-blue-200">
                            {{ $queue->service->name ?? '' }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-2xl text-white/60">Tidak ada antrian</p>
                    </div>
                @endforelse
            </div>

            <!-- Stats -->
            <div class="mt-4 pt-4 border-t border-white/20">
                <div class="grid grid-cols-2 gap-3">
                    @php
                        $services = \App\Models\Service::where('is_active', true)->get();
                    @endphp
                    @foreach($services as $service)
                        <div class="bg-white/10 rounded-lg p-3 text-center">
                            <p class="text-lg text-blue-200">{{ $service->name }}</p>
                            <p class="text-3xl font-bold text-white">
                                {{ $waitingCounts[$service->id] ?? 0 }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- Auto-refresh polling -->
    <div wire:poll.2s="refreshDisplay"></div>
</div>
