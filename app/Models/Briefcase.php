<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Briefcase extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'description',
        'created_by',
        'archived',
        'archived_at',
        'archived_by',
    ];

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function archived_by()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Documents::class, 'documents');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'briefcases');
    }

}
