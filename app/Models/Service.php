<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'code',
        'prefix',
        'current_number',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'current_number' => 'integer',
        ];
    }

    public function counters(): HasMany
    {
        return $this->hasMany(Counter::class);
    }

    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class);
    }

    public function generateQueueNumber(): string
    {
        $this->increment('current_number');
        $number = str_pad($this->current_number, 3, '0', STR_PAD_LEFT);
        return "{$this->prefix}{$number}";
    }

    public function resetDailyCounter(): void
    {
        $this->update(['current_number' => 0]);
    }
}
