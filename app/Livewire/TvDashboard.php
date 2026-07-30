<?php

namespace App\Livewire;

use App\Services\QueueService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\Attributes\On;

class TvDashboard extends Component
{
    public Collection $currentCalls;
    public Collection $waitingQueues;
    public array $waitingCounts = [];

    private QueueService $queueService;

    public function boot(QueueService $queueService): void
    {
        $this->queueService = $queueService;
    }

    public function mount(): void
    {
        $this->currentCalls = new Collection();
        $this->waitingQueues = new Collection();
        $this->refreshDisplay();
    }

    public function refreshDisplay(): void
    {
        $data = $this->queueService->getTvDisplayData();
        $this->currentCalls = $data['currentCalls'];
        $this->waitingQueues = $data['waitingQueues'];
        $this->waitingCounts = $data['waitingCounts'];
    }

    #[On('echo:queue,.queue.called')]
    public function onQueueCalled(): void
    {
        $this->refreshDisplay();
    }

    #[On('queue.reset')]
    public function onQueueReset(): void
    {
        $this->refreshDisplay();
    }

    public function render()
    {
        return view('livewire.tv-dashboard')
            ->layout('layouts.tv');
    }
}
