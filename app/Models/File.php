<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class File extends Pivot
{
    use HasFactory;
    protected $table = 'files';
    public $incrementing = true;
    public $timestamps = false;

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
    

    public function briefcases()
    {
        return $this->belongsTo(Briefcase::class, 'briefcase_id');
    }

    public function documents()
    {
        return $this->belongsTo(Documents::class, 'document_id');
    }
    
}