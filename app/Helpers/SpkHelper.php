<?php

namespace App\Helpers;

use App\Models\MaintenanceRequest;
use App\Models\ScheduledMaintenance;

class SpkHelper
{
    public static function generate(string $type): string
    {
        $year  = now()->format('Y');
        $month = now()->format('m');

        if ($type === 'repair') {
            $count = MaintenanceRequest::whereNotNull('spk_number')
                ->whereYear('spk_sent_at', $year)
                ->count() + 1;
            $prefix = 'SPK/REP';
        } else {
            $count = ScheduledMaintenance::whereNotNull('spk_number')
                ->whereYear('spk_sent_at', $year)
                ->count() + 1;
            $prefix = 'SPK/SCH';
        }

        return sprintf('%s/%s/%s/%04d', $prefix, $year, $month, $count);
    }
}