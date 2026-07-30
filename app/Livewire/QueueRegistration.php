<?php

namespace App\Livewire;

use App\Models\Queue;
use App\Models\Service;
use App\Services\QueueService;
use Livewire\Component;
use Livewire\Attributes\On;

class QueueRegistration extends Component
{
    public array $services = [];
    public ?int $selectedServiceId = null;
    public ?array $lastQueue = null;
    public int $waitingAhead = 0;

    private QueueService $queueService;

    public function boot(QueueService $queueService): void
    {
        $this->queueService = $queueService;
    }

    public function mount(): void
    {
        $this->services = $this->queueService->getAvailableServices();
    }

    public function takeQueue(int $serviceId): void
    {
        $queue = $this->queueService->takeQueue($serviceId);

        $this->lastQueue = $queue->toArray();
        $this->selectedServiceId = $serviceId;

        $this->waitingAhead = Queue::where('service_id', $serviceId)
            ->where('status', 'waiting')
            ->where('id', '<', $queue->id)
            ->count();

        $this->dispatch('queue-taken', queueNumber: $queue->queue_number);
    }

    #[On('queue.reset')]
    public function refreshServices(): void
    {
        $this->services = $this->queueService->getAvailableServices();
    }

    public function render()
    {
        return view('livewire.queue-registration')
            ->layout('layouts.app');
    }
}
