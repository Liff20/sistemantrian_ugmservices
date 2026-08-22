<?php

use App\Livewire\AdminDashboard;
use App\Livewire\AdminRekap;
use App\Livewire\CounterOperator;
use App\Livewire\QueueRegistration;
use App\Livewire\TvDashboard;
use App\Models\Counter;
use App\Models\Queue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', QueueRegistration::class)->name('queue.registration');

Route::get('/operator/{counter}', CounterOperator::class)
    ->name('queue.operator');

Route::get('/tv', TvDashboard::class)->name('queue.tv');

Route::get('/admin', AdminDashboard::class)->name('queue.admin');

Route::get('/admin/rekap', AdminRekap::class)->name('queue.admin.rekap');

Route::get('/admin/download/{date}', function (string $date) {
    $queues = Queue::whereDate('created_at', $date)
        ->with('service', 'counter')
        ->orderBy('created_at')
        ->get();

    $filename = "rekap-antrian-{$date}.csv";

    return response()->streamDownload(function () use ($queues, $date) {
        $handle = fopen('php://output', 'w');

        // BOM for Excel
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        // === Rekap Antrian ===
        $formattedDate = Carbon::parse($date)->locale('id')->isoFormat('dddd, DD MMMM YYYY');
        fputcsv($handle, ['REKAP ANTRIAN - ' . $formattedDate]);
        fputcsv($handle, []);
        fputcsv($handle, ['Jam', 'Nomor Antrian', 'Layanan', 'Email', 'No HP']);

        foreach ($queues as $q) {
            fputcsv($handle, [
                $q->created_at?->format('H:i:s') ?? '-',
                $q->queue_number,
                $q->service?->name ?? '-',
                $q->email ?? '-',
                $q->whatsapp ?? '-',
            ]);
        }

        fputcsv($handle, []);
        fputcsv($handle, ['Total Antrian:', $queues->count()]);

        fclose($handle);
    }, $filename, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ]);
})->name('queue.download');
