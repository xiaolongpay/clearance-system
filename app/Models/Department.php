<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'officer_name',
        'email',
        'default_password',
        'signature_path',
        'is_techlab',
        'is_registrar',
    ];

    protected $casts = [
        'is_techlab' => 'boolean',
        'is_registrar' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function clearanceItems()
    {
        return $this->hasMany(ClearanceItem::class);
    }
}