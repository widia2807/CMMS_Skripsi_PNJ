<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $fillable = [
        'wo_number', 'type',
        'repair_request_id', 'scheduled_maintenance_id',
        'title', 'description', 'note', 'status',
        'company_id', 'category_id', 'sub_category_id',
        'branch_id', 'worker_id', 'created_by',
        'schedule_date', 'urgency', 'period',
        'worker_confirmed_at', 'completed_at',
        'completion_note', 'completion_photo',
    ];

    protected $casts = [
        'schedule_date'        => 'date',
        'worker_confirmed_at'  => 'datetime',
        'completed_at'         => 'datetime',
    ];

    public function repairRequest()
    {
        return $this->belongsTo(RepairRequest::class);
    }

    public function scheduledMaintenance()
    {
        return $this->belongsTo(ScheduledMaintenance::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}