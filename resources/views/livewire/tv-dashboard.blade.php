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
                    videos: {{ json_encode($videos) }},
                    currentIndex: 0,
                    soundEnabled: false,
                    videoEl: null,
                    init() {
                        this.videoEl = this.$el.querySelector('video');
                        if (!this.videos.length) {
                            this.soundEnabled = true;
                            return;
                        }
                        this.videoEl.addEventListener('ended', () => {
                            this.currentIndex = (this.currentIndex + 1) % this.videos.length;
                            this.videoEl.src = '/' + this.videos[this.currentIndex];
                            this.videoEl.play();
                        });
                        this.videoEl.play().catch(() => {});
                        this.soundEnabled = false;
                    },
                    enableSound() {
                        this.soundEnabled = true;
                        if (window.unlockAudio) {
                            window.unlockAudio();
                        }
                    },
                    nextVideo() {
                        if (!this.videos.length) return;
                        this.currentIndex = (this.currentIndex + 1) % this.videos.length;
                        this.videoEl.src = '/' + this.videos[this.currentIndex];
                        this.videoEl.play();
                    },
                    prevVideo() {
                        if (!this.videos.length) return;
                        this.currentIndex = (this.currentIndex - 1 + this.videos.length) % this.videos.length;
                        this.videoEl.src = '/' + this.videos[this.currentIndex];
                        this.videoEl.play();
                    }
                 }">
                @if(count($videos) > 0)
                    <video class="w-full h-full object-contain bg-transparent" autoplay muted playsinline
                           :src="'/' + videos[currentIndex]">
                        Browser tidak mendukung video.
                    </video>
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-center">
                        <svg class="w-20 h-20 text-white/40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <p class="text-2xl text-white/50">Belum ada video</p>
                        <p class="text-lg text-white/30 mt-1">Letakkan file video di folder <code class="text-blue-200">public/videos</code></p>
                    </div>
                @endif

                <!-- Previous button (left side) -->
                <button x-show="videos.length" @click="prevVideo()"
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-14 h-14 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center transition z-20">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                    </svg>
                </button>

                <!-- Next button (right side) -->
                <button x-show="videos.length" @click="nextVideo()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-14 h-14 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center transition z-20">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.59 16.59 10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                    </svg>
                </button>

                <!-- Enable sound overlay (shown until user unlocks audio once) -->
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

@script
<script>
    let announcementVoice = localStorage.getItem('tv-announcement-voice') !== '0';
    let videoSound = localStorage.getItem('tv-video-sound') !== '0';
    let audioUnlocked = false;
    let cachedVoice = null;

    const pickIndonesianVoice = () => {
        if (!('speechSynthesis' in window)) return null;
        if (cachedVoice) return cachedVoice;
        const voices = window.speechSynthesis.getVoices();
        const idVoices = voices.filter(v =>
            (v.lang || '').toLowerCase().replace('_', '-').startsWith('id')
        );
        if (!idVoices.length) return null;
        // Prefer a female Indonesian voice when available.
        const female = idVoices.find(v =>
            /gadis|damayanti|google|wanita|female|indah|pertiwi|andika/i.test(v.name || '')
        );
        cachedVoice = female || idVoices[0];
        return cachedVoice;
    };

    // Warm up the voice list as soon as the browser provides it.
    if ('speechSynthesis' in window) {
        pickIndonesianVoice();
        window.speechSynthesis.addEventListener('voiceschanged', () => {
            cachedVoice = null;
            pickIndonesianVoice();
        });
    }

    const applyVideoSound = () => {
        const video = document.querySelector('video');
        if (!video) return;
        video.muted = !(audioUnlocked && videoSound);
    };

    window.unlockAudio = () => {
        audioUnlocked = true;
        applyVideoSound();
    };

    const speakQueue = (queueNumber, counterName) => {
        if (!audioUnlocked || !announcementVoice) return;
        if (!('speechSynthesis' in window)) return;

        const numberSpelled = String(queueNumber).replace(/(.)/g, '$1 ').trim();
        const text = 'Nomor antrian ' + numberSpelled + ', silakan menuju ' + counterName;

        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'id-ID';
        utterance.rate = 0.95;

        const voice = pickIndonesianVoice();
        if (voice) {
            utterance.voice = voice;
            utterance.pitch = 1.05;
        } else {
            utterance.pitch = 1.2;
        }

        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(utterance);
    };

    $wire.$watch('announcementVoice', (v) => {
        announcementVoice = !!v;
        localStorage.setItem('tv-announcement-voice', v ? '1' : '0');
    });

    $wire.$watch('videoSound', (v) => {
        videoSound = !!v;
        localStorage.setItem('tv-video-sound', v ? '1' : '0');
        applyVideoSound();
    });

    $wire.$watch('announcement', (announcement) => {
        if (!announcement || !announcement.queueNumber) return;
        speakQueue(announcement.queueNumber, announcement.counterName);
    });
</script>
@endscript
