<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'company_id',       // ← wajib ada
        'name',
        'specification',
        'condition',
        'quantity',
        'serial_number',
        'brand',
        'acquisition_year',
        'value',
        'photo',
        'branch_id',
        'room_id',
        'category_id',
        'sub_category_id',
        'user_id',
        'pic_id',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(AssetSubCategory::class, 'sub_category_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}