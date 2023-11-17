<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
    	'name',
		'number',
		'country_id'
    ];

    public function products()
    {
      return $this->belongsToMany('App\Product', 'category_products')->where('is_searchable', 1);
    }

    public function subDepartments()
    {
      return $this->belongsToMany('App\SubDepartment', 'category_subdepartments');
    }
}
