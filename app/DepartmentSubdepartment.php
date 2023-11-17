<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartmentSubdepartment extends Model
{
    protected $fillable = [
        'department_id',
        'sub_department_id'
    ];
}
