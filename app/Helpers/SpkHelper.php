<?php

namespace App\Helpers;

use App\Models\WorkOrder;

class SpkHelper
{
    
    public static function generate(string $type): string
    {
        $prefix = $type === 'repair' ? 'REP' : 'SCH';
        $year   = now()->format('Y');

        // Hitung jumlah WO tahun ini dengan prefix yg sama
        $count  = WorkOrder::whereYear('created_at', $year)
                           ->where('type', $type)
                           ->count();

        $seq    = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        return "SPK-{$prefix}-{$year}-{$seq}";
    }
}