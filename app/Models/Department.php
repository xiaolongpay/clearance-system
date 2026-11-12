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
        'requirements',
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

    /**
     * The signature image of the office, which is kept in the database.
     */
    public function signature()
    {
        return $this->hasOne(DepartmentSignature::class);
    }

    /**
     * The signature as a data address, which the PDF form can embed without reading a file.
     */
    public function signatureDataUri(): ?string
    {
        $signature = $this->signature;

        return $signature ? 'data:' . $signature->mime . ';base64,' . $signature->data : null;
    }
}
