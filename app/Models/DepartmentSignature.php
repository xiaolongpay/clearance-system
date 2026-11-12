<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentSignature extends Model
{
    protected $fillable = [
        'department_id',
        'mime',
        'data',
    ];
}
