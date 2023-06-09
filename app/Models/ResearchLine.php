<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'career_id',
    ];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }
}
