<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitude extends Model
{
    use HasFactory;
    protected $fillable = [
        'type_of_request',
        'status',
        'created_by',
        'archived',
        'archived_at',
        'archived_by',
    ];
}
