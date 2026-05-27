<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $done = \App\Models\ScheduledMaintenance::where('status', 'done')->get();

    foreach ($done as $item) {
        $next = match($item->period) {
            'weekly'    => Carbon::parse($item->scheduled_date)->addWeek(),
            'monthly'   => Carbon::parse($item->scheduled_date)->addMonth(),
            'quarterly' => Carbon::parse($item->scheduled_date)->addMonths(3),
            'yearly'    => Carbon::parse($item->scheduled_date)->addYear(),
            default     => null,
        };

        if (!$next) continue;

        $exists = \App\Models\ScheduledMaintenance::where('worker_id', $item->worker_id)
            ->where('category_id', $item->category_id)
            ->where('scheduled_date', $next->toDateString())
            ->exists();

        if (!$exists) {
            \App\Models\ScheduledMaintenance::create([
                'title'          => $item->title,
                'category_id'    => $item->category_id,
                'period'         => $item->period,
                'scheduled_date' => $next->toDateString(),
                'worker_id'      => $item->worker_id,
                'note'           => $item->note,
                'created_by'     => $item->created_by,
                'company_id'     => $item->company_id,
                'status'         => 'pending',
            ]);
        }
    }
})->daily()->name('auto-create-maintenance');