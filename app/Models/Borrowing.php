<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Borrowing extends Model
{
    protected $fillable = [
        'asset_id',
        'request_branch_id',
        'source_branch_id',
        'status',
        'start_date',
        'end_date',
        'notes'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function requestBranch()
    {
        return $this->belongsTo(Branch::class, 'request_branch_id');
    }

    public function sourceBranch()
    {
        return $this->belongsTo(Branch::class, 'source_branch_id');
    }
}