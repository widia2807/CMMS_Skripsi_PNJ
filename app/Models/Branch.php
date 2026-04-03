<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
    'name',
    'type',
    'company_id',
    'status'
];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}