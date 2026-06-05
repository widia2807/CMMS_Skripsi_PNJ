<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'asset_id',
        'user_id',
        'request_branch_id',
        'source_branch_id',
        'destination_branch_id',  // ← baru
        'destination_room_id',    // ← baru
        'qty',                    // ← baru
        'reason',                 // ← baru
        'notes',
        'status',
        'start_date',
        'end_date',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requestBranch()
    {
        return $this->belongsTo(Branch::class, 'request_branch_id');
    }

    public function sourceBranch()
    {
        return $this->belongsTo(Branch::class, 'source_branch_id');
    }

    public function destinationBranch()
    {
        return $this->belongsTo(Branch::class, 'destination_branch_id');
    }

    public function destinationRoom()
    {
        return $this->belongsTo(Room::class, 'destination_room_id');
    }
}