<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScheduledMaintenance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'category_id',
        'note',
        'period',
        'scheduled_date',
        'created_by',
        'worker_id',
        'status',
        'worker_confirmed_at',
        'completed_at',
        'completion_note',
        'completion_photo',
    ];

    protected $casts = [
        'scheduled_date'      => 'date',
        'worker_confirmed_at' => 'datetime',
        'completed_at'        => 'datetime',
    ];

    /* ─── RELASI ─── */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    /* ─── SCOPE FILTER ─── */

    public function scopeByStatus($query, $status)
    {
        return $query->when($status, fn($q) => $q->where('status', $status));
    }

    public function scopeByWorker($query, $workerId)
    {
        return $query->when($workerId, fn($q) => $q->where('worker_id', $workerId));
    }

    public function scopeByMonth($query, $month)
    {
        // $month format: "2025-05"
        return $query->when($month, fn($q) => $q->whereRaw(
            "DATE_FORMAT(scheduled_date, '%Y-%m') = ?", [$month]
        ));
    }

    public function category()
{
    return $this->belongsTo(Category::class);
}
}