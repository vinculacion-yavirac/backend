<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsible extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'charge_id',
    ];

    public function user_id()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function charge_id()
    {
        return $this->belongsTo(Catalog::class, 'charge_id');
    }
}
