<?php

namespace App\Livewire;

use App\Models\Queue;
use Livewire\Component;

class AdminRekap extends Component
{
    public function render()
    {
        $queues = Queue::with('service')
            ->orderBy('created_at')
            ->get()
            ->groupBy(fn (Queue $q) => $q->created_at?->format('Y-m-d') ?? '0000-00-00')
            ->sortKeysDesc();

        return view('livewire.admin-rekap', [
            'groupedQueues' => $queues,
        ])->layout('layouts.app', ['title' => 'Rekap Antrian']);
    }
}
