<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledSubCategory extends Model
{
    protected $fillable = [
        'company_id',
        'category_id',
        'name',
        'description',
        'is_active',
    ];

    // Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke ScheduledMaintenance
    public function scheduledMaintenances()
    {
        return $this->hasMany(ScheduledMaintenance::class);
    }
}