<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueLog extends Model
{
    protected $fillable = [
        'queue_id',
        'counter_id',
        'action',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function queue(): BelongsTo
    {
        return $this->belongsTo(Queue::class);
    }

    public function counter(): BelongsTo
    {
        return $this->belongsTo(Counter::class);
    }

    public static function log(int $queueId, ?int $counterId, string $action, ?array $metadata = null): self
    {
        return static::create([
            'queue_id' => $queueId,
            'counter_id' => $counterId,
            'action' => $action,
            'metadata' => $metadata,
        ]);
    }
}
