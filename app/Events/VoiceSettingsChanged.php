<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class VoiceSettingsChanged implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public function __construct(
        public bool $announcementVoice,
        public bool $videoSound,
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('queue');
    }

    public function broadcastAs(): string
    {
        return 'voice.settings';
    }
}
