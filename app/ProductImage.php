<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
      'product_id','file_name',
    ];

    public function getImage($productUpc)
    {
        if (strpos($this->file_name, 'http') !== false) {
            return $this->file_name;
        }
        return url('/').'/product/images/'.$productUpc.'/'.$this->file_name;
    }

    public function product()
    {
      return $this->belongsTo(Product::class);
    }
}
