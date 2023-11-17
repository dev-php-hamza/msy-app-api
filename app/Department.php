<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable =[
        'number',
        'name',
        'country_id'
    ];

    public function subDepartments()
    {
        return $this->belongsToMany(SubDepartment::class, 'department_subdepartments')->withTimestamps();
    }
}
