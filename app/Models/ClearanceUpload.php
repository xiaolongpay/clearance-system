<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClearanceUpload extends Model
{
    protected $fillable = [
        'clearance_item_id',
        'kind',
        'original_name',
        'mime',
        'size',
        'data',
        'url',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function clearanceItem()
    {
        return $this->belongsTo(ClearanceItem::class);
    }

    /** What to show in lists: the file name, or the link address. */
    public function label(): string
    {
        return $this->kind === 'link' ? ($this->url ?? 'Link') : ($this->original_name ?? 'File');
    }
}
