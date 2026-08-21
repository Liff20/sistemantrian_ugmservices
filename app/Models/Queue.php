<?php

namespace App\Models;

use App\Events\QueueCalled;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Queue extends Model
{
    protected $fillable = [
        'queue_number',
        'service_id',
        'counter_id',
        'status',
        'email',
        'whatsapp',
        'called_at',
        'served_at',
        'completed_at',
        'call_count',
    ];

    protected function casts(): array
    {
        return [
            'called_at' => 'datetime',
            'served_at' => 'datetime',
            'completed_at' => 'datetime',
            'call_count' => 'integer',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function counter(): BelongsTo
    {
        return $this->belongsTo(Counter::class);
    }

    public static function createForService(Service $service, array $contact = []): self
    {
        $number = $service->generateQueueNumber();

        return static::create([
            'queue_number' => $number,
            'service_id' => $service->id,
            'status' => 'waiting',
            'email' => $contact['email'] ?? null,
            'whatsapp' => $contact['whatsapp'] ?? null,
        ]);
    }

    public function call(Counter $counter): void
    {
        $this->update([
            'status' => 'called',
            'counter_id' => $counter->id,
            'called_at' => now(),
            'call_count' => $this->call_count + 1,
        ]);

        QueueLog::log($this->id, $counter->id, 'called');

        broadcast(new QueueCalled(
            queueNumber: $this->queue_number,
            counterName: $counter->name,
            serviceName: $this->service->name,
            counterId: $counter->id,
        ));
    }

    public function serve(): void
    {
        $this->update([
            'status' => 'serving',
            'served_at' => now(),
        ]);

        QueueLog::log($this->id, $this->counter_id, 'serving');
    }

    public function complete(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        QueueLog::log($this->id, $this->counter_id, 'completed');
    }

    public function skip(): void
    {
        $this->update(['status' => 'skipped']);

        QueueLog::log($this->id, $this->counter_id, 'skipped');
    }
}
