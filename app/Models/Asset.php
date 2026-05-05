<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'name',
        'specification',
        'category_id',
        'sub_category_id',
        'serial_number',
        'condition',
        'location',
        'room',
        'brand',
        'branch_id',
        'value',
        'acquisition_year',
        'user_id',
        'pic_id',
        'photo',
    ];

    public function category()
{
    return $this->belongsTo(AssetCategory::class, 'category_id');
}

public function subCategory()
{
    return $this->belongsTo(AssetSubCategory::class, 'sub_category_id');
}
public function branch()
{
    return $this->belongsTo(Branch::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}
}
