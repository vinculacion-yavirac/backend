<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'entry_time', 'exit_time', 'observation'];

    public function attendance()
    {
        return $this->belongsTo(User::class);
    }
}
