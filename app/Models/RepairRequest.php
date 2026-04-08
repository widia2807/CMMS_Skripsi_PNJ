<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
    'user_id',
    'branch_id',
    'title',
    'description',
    'category',
    'sub_category',
    'photo',
    'status'
];

    // relasi ke user (PIC)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}