<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClearanceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'department_id',
        'school_year_id',
        'status',
        'remarks',
        'signed_by',
        'signed_at',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function signedByUser()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    /** Every upload, including the file data. Use it for counting and deleting. */
    public function uploads()
    {
        return $this->hasMany(ClearanceUpload::class);
    }

    /** The same uploads without the heavy file data, for showing lists on pages. */
    public function attachments()
    {
        return $this->hasMany(ClearanceUpload::class)->select([
            'id', 'clearance_item_id', 'kind', 'original_name', 'mime', 'size', 'url', 'viewed_at', 'created_at',
        ]);
    }
}
