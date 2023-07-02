<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institute extends Model
{
    use HasFactory;

        protected $table = 'institute';

        protected $fillable = [
            'number_resolution',
            'name',
            'logo',
            'state',
            'place_location',
            'email',
            'phone',
            'parish_id'
        ];

        public function parish_id()
        {
            return $this->belongsTo(Parish::class, 'parish_id');
        }

}
