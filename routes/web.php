<?php

use App\Livewire\AdminDashboard;
use App\Livewire\CounterOperator;
use App\Livewire\QueueRegistration;
use App\Livewire\TvDashboard;
use App\Models\Counter;
use App\Models\Queue;
use App\Models\QueueLog;
use Illuminate\Support\Facades\Route;

Route::get('/', QueueRegistration::class)->name('queue.registration');

Route::get('/operator/{counter}', CounterOperator::class)
    ->name('queue.operator');

Route::get('/tv', TvDashboard::class)->name('queue.tv');

Route::get('/admin', AdminDashboard::class)->name('queue.admin');

Route::get('/admin/download/{date}', function (string $date) {
    $queues = Queue::whereDate('created_at', $date)
        ->with('service', 'counter')
        ->orderBy('created_at')
        ->get();

    $logs = QueueLog::whereDate('created_at', $date)
        ->with('queue', 'counter')
        ->orderBy('created_at')
        ->get();

    $filename = "rekap-antrian-{$date}.csv";

    return response()->streamDownload(function () use ($queues, $logs, $date) {
        $handle = fopen('php://output', 'w');

        // BOM for Excel
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        // === Rekap Antrian ===
        fputcsv($handle, ['REKAP ANTRIAN - ' . $date]);
        fputcsv($handle, []);
        fputcsv($handle, ['No', 'Nomor Antrian', 'Layanan', 'Loket', 'Status', 'Waktu Ambil', 'Waktu Dipanggil', 'Waktu Dilayani', 'Waktu Selesai']);

        foreach ($queues as $i => $q) {
            fputcsv($handle, [
                $i + 1,
                $q->queue_number,
                $q->service?->name ?? '-',
                $q->counter?->name ?? '-',
                match($q->status) {
                    'waiting' => 'Menunggu',
                    'called' => 'Dipanggil',
                    'serving' => 'Dilayani',
                    'completed' => 'Selesai',
                    'skipped' => 'Dilewati',
                    default => $q->status,
                },
                $q->created_at?->format('H:i:s') ?? '-',
                $q->called_at?->format('H:i:s') ?? '-',
                $q->served_at?->format('H:i:s') ?? '-',
                $q->completed_at?->format('H:i:s') ?? '-',
            ]);
        }

        fputcsv($handle, []);
        fputcsv($handle, ['Total Antrian:', $queues->count()]);

        // === Log Aktivitas ===
        fputcsv($handle, []);
        fputcsv($handle, ['LOG AKTIVITAS - ' . $date]);
        fputcsv($handle, []);
        fputcsv($handle, ['No', 'Nomor Antrian', 'Loket', 'Aksi', 'Waktu']);

        foreach ($logs as $i => $log) {
            fputcsv($handle, [
                $i + 1,
                $log->queue?->queue_number ?? '-',
                $log->counter?->name ?? '-',
                match($log->action) {
                    'called' => 'Dipanggil',
                    'serving' => 'Mulai Dilayani',
                    'completed' => 'Selesai',
                    'skipped' => 'Dilewati',
                    default => $log->action,
                },
                $log->created_at->format('H:i:s'),
            ]);
        }

        fclose($handle);
    }, $filename, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ]);
})->name('queue.download');
