<div class="max-w-5xl mx-auto" wire:poll.5s>
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-primary-light rounded-full mb-4">
            <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-text-primary">Rekap Antrian</h2>
        <p class="text-text-secondary mt-2">Data antrian per tanggal</p>
    </div>

    <!-- Admin Sub-navigation -->
    <div class="flex items-center justify-center gap-3 mb-8">
        <a href="{{ route('queue.admin') }}"
           class="px-5 py-2.5 rounded-lg font-medium text-sm border transition-colors
                  bg-surface border-border text-text-secondary hover:border-primary hover:text-primary">
            Panel Admin
        </a>
        <a href="{{ route('queue.admin.rekap') }}"
           class="px-5 py-2.5 rounded-lg font-medium text-sm border transition-colors
                  bg-primary text-white border-primary">
            Rekap Antrian
        </a>
    </div>

    @forelse($groupedQueues as $date => $queues)
        @php($dateCarbon = \Carbon\Carbon::parse($date))
        <div class="bg-surface border border-border rounded-xl overflow-hidden mb-8">
            <!-- Date Group Header -->
            <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 bg-primary-light/40 border-b border-border">
                <div>
                    <h3 class="text-lg font-bold text-text-primary">
                        {{ $dateCarbon->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}
                    </h3>
                    <p class="text-sm text-text-secondary mt-0.5">{{ $queues->count() }} antrian</p>
                </div>
                <a href="{{ route('queue.download', ['date' => $date]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors font-medium text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export
                </a>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-text-secondary bg-background border-b border-border">
                            <th class="text-left py-3 px-6 font-semibold">Jam</th>
                            <th class="text-left py-3 px-6 font-semibold">Nomor Antrian</th>
                            <th class="text-left py-3 px-6 font-semibold">Layanan</th>
                            <th class="text-left py-3 px-6 font-semibold">Email</th>
                            <th class="text-left py-3 px-6 font-semibold">No HP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($queues as $queue)
                            @php($email = $queue->email)
                            @php($whatsapp = $queue->whatsapp)
                            <tr class="border-b border-border/50 hover:bg-primary-light/30 transition-colors">
                                <td class="py-3 px-6 font-mono text-text-secondary">{{ $queue->created_at?->format('H:i') ?? '-' }}</td>
                                <td class="py-3 px-6 font-bold text-primary">{{ $queue->queue_number }}</td>
                                <td class="py-3 px-6 text-text-primary">{{ $queue->service?->name ?? '-' }}</td>
                                <td class="py-3 px-6 text-text-secondary">
                                    @if($email)
                                        <div class="flex items-center gap-2" x-data="{ copied: false }">
                                            <span class="break-all">{{ $email }}</span>
                                            <button
                                                type="button"
                                                x-on:click="navigator.clipboard.writeText({{ Js::from($email) }}); copied = true; setTimeout(() => copied = false, 1500)"
                                                class="flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-lg text-text-secondary hover:text-primary hover:bg-primary-light transition-colors"
                                                title="Salin email"
                                            >
                                                <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                <svg x-show="copied" x-cloak class="w-4 h-4 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-text-secondary">
                                    @if($whatsapp)
                                        <div class="flex items-center gap-2" x-data="{ copied: false }">
                                            <span>{{ $whatsapp }}</span>
                                            <button
                                                type="button"
                                                x-on:click="navigator.clipboard.writeText({{ Js::from($whatsapp) }}); copied = true; setTimeout(() => copied = false, 1500)"
                                                class="flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-lg text-text-secondary hover:text-primary hover:bg-primary-light transition-colors"
                                                title="Salin nomor HP"
                                            >
                                                <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                <svg x-show="copied" x-cloak class="w-4 h-4 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="text-center py-16 bg-surface border border-border rounded-xl">
            <svg class="w-16 h-16 text-text-secondary/40 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-text-secondary">Belum ada data antrian.</p>
        </div>
    @endforelse
</div>
