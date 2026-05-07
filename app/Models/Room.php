<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
       protected $fillable = ['branch_id', 'name'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function assets()
{
    return $this->hasMany(Asset::class);
}

}
