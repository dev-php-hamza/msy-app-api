<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImageLookUp extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_image_look_up';

    protected $fillable =[
        'country_id',
        'limit',
        'offset'
    ];

    public function country()
    {
      return $this->belongsTo('App\Country');
    }
}
