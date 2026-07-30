<?php

namespace App\Services;

use App\Models\Counter;
use App\Models\Queue;
use App\Models\Service;

class QueueService
{
    public function getAvailableServices(): array
    {
        return Service::where('is_active', true)
            ->withCount(['queues' => function ($query) {
                $query->where('status', 'waiting');
            }])
            ->get()
            ->toArray();
    }

    public function takeQueue(int $serviceId): Queue
    {
        $service = Service::findOrFail($serviceId);
        return Queue::createForService($service);
    }

    public function getAllWaitingQueues()
    {
        return Queue::where('status', 'waiting')
            ->with('service')
            ->orderBy('created_at')
            ->get();
    }

    public function getNextWaiting(): ?Queue
    {
        return Queue::where('status', 'waiting')
            ->with('service')
            ->orderBy('created_at')
            ->first();
    }

    public function callNext(int $counterId): ?Queue
    {
        $counter = Counter::findOrFail($counterId);
        $next = $this->getNextWaiting();

        if ($next) {
            $next->call($counter);
        }

        return $next;
    }

    public function getCurrentCall(int $counterId): ?Queue
    {
        return Queue::where('counter_id', $counterId)
            ->whereIn('status', ['called', 'serving'])
            ->latest()
            ->first();
    }

    public function getTvDisplayData(): array
    {
        $currentCalls = Queue::whereIn('status', ['called', 'serving'])
            ->with(['counter', 'service'])
            ->get()
            ->groupBy('counter_id')
            ->map(fn($queues) => $queues->last())
            ->values();

        $waitingQueues = Queue::where('status', 'waiting')
            ->with('service')
            ->orderBy('created_at')
            ->limit(30)
            ->get();

        $waitingCounts = Queue::where('status', 'waiting')
            ->selectRaw('service_id, count(*) as total')
            ->groupBy('service_id')
            ->pluck('total', 'service_id')
            ->toArray();

        return [
            'currentCalls' => $currentCalls,
            'waitingQueues' => $waitingQueues,
            'waitingCounts' => $waitingCounts,
        ];
    }
}
