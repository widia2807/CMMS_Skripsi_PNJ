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
        'category_id',
    'sub_category_id',
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

public function branch()
{
    return $this->belongsTo(Branch::class);
}

public function categoryRelation()
{
    return $this->belongsTo(Category::class, 'category_id');
}
}