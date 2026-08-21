<?php

namespace App\Livewire;

use App\Models\Queue;
use App\Models\Service;
use App\Services\QueueService;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class QueueRegistration extends Component
{
    public array $services = [];
    public ?int $selectedServiceId = null;
    public ?array $lastQueue = null;
    public int $waitingAhead = 0;

    public ?int $pendingServiceId = null;

    #[Validate('required', message: 'Alamat email wajib diisi.')]
    #[Validate('email', message: 'Format alamat email tidak valid.')]
    public string $email = '';

    #[Validate('required', message: 'Nomor WhatsApp wajib diisi.')]
    #[Validate('regex:/^[0-9]{9,15}$/', message: 'Nomor WhatsApp harus berupa angka 9–15 digit (tanpa spasi atau tanda lain).')]
    public string $whatsapp = '';

    private QueueService $queueService;

    public function boot(QueueService $queueService): void
    {
        $this->queueService = $queueService;
    }

    public function mount(): void
    {
        $this->services = $this->queueService->getAvailableServices();
    }

    public function showContactForm(int $serviceId): void
    {
        $this->pendingServiceId = $serviceId;
        $this->email = '';
        $this->whatsapp = '';
    }

    public function cancelContactForm(): void
    {
        $this->pendingServiceId = null;
        $this->email = '';
        $this->whatsapp = '';
    }

    public function takeQueue(int $serviceId): void
    {
        $this->validate();

        $queue = $this->queueService->takeQueue($serviceId, [
            'email' => trim($this->email),
            'whatsapp' => trim($this->whatsapp),
        ]);

        $this->lastQueue = $queue->toArray();
        $this->selectedServiceId = $serviceId;
        $this->pendingServiceId = null;
        $this->email = '';
        $this->whatsapp = '';

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
