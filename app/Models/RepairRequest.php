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
        'approved_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}