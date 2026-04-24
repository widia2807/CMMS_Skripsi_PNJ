<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetSubCategory extends Model
{
  protected $fillable = ['name', 'asset_category_id'];

    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }
}
