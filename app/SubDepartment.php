<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubDepartment extends Model
{
    protected $fillable = [
        'number',
        'name',
        'country_id'
    ];

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_subdepartments')->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_subdepartments')->withTimestamps();
    }
}
