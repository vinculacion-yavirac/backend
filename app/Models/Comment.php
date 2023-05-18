<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'briefcases',
        'created_at',
        'updated_at',
        'created_by',
    ];

    public function created_by()
    {
        return $this->belongsTo(User::class);
    }
}
