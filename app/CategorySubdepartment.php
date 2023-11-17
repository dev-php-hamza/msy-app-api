<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategorySubdepartment extends Model
{
    protected $fillable = [
        'sub_department_id',
        'category_id'
    ];
}
