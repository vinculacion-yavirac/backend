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
        'briefcase',
        'uploaded_by',
    ];

    public $timestamps = false;

    public function briefcase()
    {
        return $this->belongsTo(Briefcases::class, 'briefcase');
    }

    public function uploaded_by()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
