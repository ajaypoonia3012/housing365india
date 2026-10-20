<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Console Tasks
|--------------------------------------------------------------------------
|
| Weekly automated real estate blog generation powered by Claude Sonnet
| runs every Tuesday at 09:00 AM Indian Standard Time (Asia/Kolkata).
| Requires server cron: * * * * * php artisan schedule:run >> /dev/null 2>&1
|
*/
Schedule::command('blog:generate')
    ->tuesdays()
    ->at('09:00')
    ->timezone('Asia/Kolkata')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/blog-generation.log'));

