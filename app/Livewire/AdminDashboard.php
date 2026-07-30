<?php

namespace App\Livewire;

use App\Models\Queue;
use App\Models\QueueLog;
use App\Models\Service;
use Livewire\Component;

class AdminDashboard extends Component
{
    public bool $showConfirmReset = false;
    public string $confirmText = '';
    public string $resetStatus = '';

    public function mount(): void
    {
        $this->resetStatus = '';
    }

    public function confirmReset(): void
    {
        $this->showConfirmReset = true;
        $this->confirmText = '';
    }

    public function cancelReset(): void
    {
        $this->showConfirmReset = false;
        $this->confirmText = '';
        $this->resetStatus = '';
    }

    public function resetAll(): void
    {
        if (strtolower(trim($this->confirmText)) !== 'reset') {
            $this->resetStatus = 'error';
            return;
        }

        // Reset all services' daily counters
        Service::query()->update(['current_number' => 0]);

        // Delete all queue logs
        QueueLog::query()->delete();

        // Delete all queues
        Queue::query()->delete();

        $this->showConfirmReset = false;
        $this->confirmText = '';
        $this->resetStatus = 'success';

        // Dispatch event to notify other components
        $this->dispatch('queue.reset');
    }

    public function getStats(): array
    {
        return [
            'total_queues' => Queue::count(),
            'waiting_queues' => Queue::where('status', 'waiting')->count(),
            'called_queues' => Queue::where('status', 'called')->count(),
            'serving_queues' => Queue::where('status', 'serving')->count(),
            'completed_queues' => Queue::where('status', 'completed')->count(),
            'skipped_queues' => Queue::where('status', 'skipped')->count(),
            'total_logs' => QueueLog::count(),
            'services' => Service::withCount('queues')->get(),
        ];
    }

    public function render()
    {
        return view('livewire.admin-dashboard', [
            'stats' => $this->getStats(),
        ])->layout('layouts.app', ['title' => 'Admin']);
    }
}
