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
    public ?array $announcement = null;
    public bool $announcementVoice = true;
    public bool $videoSound = true;

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
    public function onQueueCalled(array $payload = []): void
    {
        $this->announcement = [
            'queueNumber' => $payload['queueNumber'] ?? '',
            'counterName' => $payload['counterName'] ?? '',
            'id' => now()->timestamp . '-' . ($payload['queueNumber'] ?? ''),
        ];

        $this->refreshDisplay();
    }

    #[On('echo:queue,.voice.settings')]
    public function onVoiceSettings(array $payload): void
    {
        $this->announcementVoice = (bool) ($payload['announcementVoice'] ?? true);
        $this->videoSound = (bool) ($payload['videoSound'] ?? true);
    }

    #[On('queue.reset')]
    public function onQueueReset(): void
    {
        $this->refreshDisplay();
    }

    public function render()
    {
        return view('livewire.tv-dashboard', [
            'videos' => $this->getVideos(),
        ])
            ->layout('layouts.tv');
    }

    private function getVideos(): array
    {
        $dir = public_path('videos');

        if (! is_dir($dir)) {
            return [];
        }

        $files = glob($dir . DIRECTORY_SEPARATOR . '*.{mp4,webm,ogg}', GLOB_BRACE);

        if ($files === false || count($files) === 0) {
            return [];
        }

        $videos = array_map(fn (string $file) => 'videos/' . basename($file), $files);

        sort($videos, SORT_NATURAL);

        return $videos;
    }
}
