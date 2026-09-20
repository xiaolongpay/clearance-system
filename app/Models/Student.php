<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

         protected $fillable = [
        'student_number',
        'first_name',
        'middle_initial',
        'last_name',
        'course',
        'year_level',
        'section',
        'email',
        'password',
        'school_year_id',
        'must_change_password',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'must_change_password' => 'boolean',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function clearanceItems()
    {
        return $this->hasMany(ClearanceItem::class);
    }
}