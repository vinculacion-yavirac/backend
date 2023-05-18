<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'content',
        'official_document',
        'uploaded_by',
    ];

    public $timestamps = false;

    public function official_document()
    {
        return $this->belongsTo(Official_Document::class, 'official_document');
    }

    public function uploaded_by()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
