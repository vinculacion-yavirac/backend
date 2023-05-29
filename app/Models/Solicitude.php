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
        'created_at',
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

    public function Fundation(){
        return $this->belongsToMany(Foundation::class,'foudation_studen_briefcases');
    }

}
