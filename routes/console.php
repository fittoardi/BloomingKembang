<?php

use App\Jobs\ExpireUnpaidOrders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new ExpireUnpaidOrders)->everyFifteenMinutes();

// Cadangan database & file harian (aktif otomatis jika spatie/laravel-backup terpasang)
if (class_exists('Spatie\Backup\Commands\BackupCommand')) {
    Schedule::command('backup:clean')->daily()->at('01:00');
    Schedule::command('backup:run')->daily()->at('01:30');
}
