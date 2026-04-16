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
}
