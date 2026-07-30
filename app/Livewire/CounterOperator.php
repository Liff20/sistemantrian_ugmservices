<?php

namespace App\Livewire;

use App\Models\Counter;
use App\Models\Queue;
use App\Services\QueueService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\Attributes\On;

class CounterOperator extends Component
{
    public Counter $counter;
    public ?Queue $currentQueue = null;
    public Collection $waitingQueues;

    private QueueService $queueService;

    public function boot(QueueService $queueService): void
    {
        $this->queueService = $queueService;
    }

    public function mount(Counter $counter): void
    {
        $this->counter = $counter;
        $this->waitingQueues = new Collection();
        $this->refreshData();
    }

    public function refreshData(): void
    {
        $this->currentQueue = $this->queueService->getCurrentCall($this->counter->id);
        $this->waitingQueues = $this->queueService->getAllWaitingQueues();
    }

    public function callNext(): void
    {
        $queue = $this->queueService->callNext($this->counter->id);
        $this->refreshData();
    }

    public function recall(): void
    {
        if ($this->currentQueue) {
            $this->currentQueue->call($this->counter);
            $this->refreshData();
        }
    }

    public function startService(): void
    {
        if ($this->currentQueue && $this->currentQueue->status === 'called') {
            $this->currentQueue->serve();
            $this->refreshData();
        }
    }

    public function completeService(): void
    {
        if ($this->currentQueue && $this->currentQueue->status === 'serving') {
            $this->currentQueue->complete();
            $this->currentQueue = null;
            $this->refreshData();
        }
    }

    public function skipQueue(int $queueId): void
    {
        $queue = Queue::find($queueId);
        if ($queue) {
            $queue->skip();
            $this->refreshData();
        }
    }

    #[On('echo:queue,.queue.called')]
    public function onQueueCalled(array $payload): void
    {
        $this->refreshData();
    }

    #[On('queue.reset')]
    public function onQueueReset(): void
    {
        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.counter-operator')
            ->layout('layouts.app');
    }
}
