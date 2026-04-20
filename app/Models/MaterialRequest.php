<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialRequest extends Model
{
    protected $fillable = [
        'repair_request_id',
        'item_name',
        'qty',
        'unit'
    ];

    public function repairRequest()
{
    return $this->belongsTo(\App\Models\RepairRequest::class, 'repair_request_id');
}
}
