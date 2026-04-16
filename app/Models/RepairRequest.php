<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairRequest extends Model
{
    protected $table = 'repair_requests';

    protected $fillable = [
        'user_id',
        'branch_id',
        'title',
        'description',
        'category',
        'sub_category',
        'photo',
        'status',
        'reject_reason',
        'approved_at',
        'technician_id',   
        'urgency',         
        'schedule_date',   
        'inspection_notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function technician()
{
    return $this->belongsTo(User::class, 'technician_id');
}
}