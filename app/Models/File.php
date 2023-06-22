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
        'observation',
        'state',
        'size',
        'briefcase_id',
        'document_id',
    ];

    /*

    public function briefcase()
    {
        return $this->belongsTo(Briefcase::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    */

    public function briefcase()
    {
        return $this->belongsTo(Briefcase::class, 'briefcase_id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
