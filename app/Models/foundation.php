<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foundation extends Model
{
    use HasFactory;
     
    protected $fillable = [
        'name',
        'status',
        'authorized_person',
        'number_ruc',
        'economic_activity',
        'company_email',
        'company_number',
        'received_students',
        'direct_benefit',
        'indirect_benefits',
        
    ];

}
