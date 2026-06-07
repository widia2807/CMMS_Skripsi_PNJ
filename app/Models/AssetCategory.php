<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetCategory extends Model
{
    protected $table = 'asset_categories';
    protected $fillable = ['name', 'company_id'];


    public function subCategories()
    {
        return $this->hasMany(AssetSubCategory::class, 'asset_category_id');
    }
}
