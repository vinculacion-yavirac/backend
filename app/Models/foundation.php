<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'created_by',
        
    ];

    public function created_by(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function solicitud(){
        return $this->belongsToMany(Solicitude::class,'foudation_studen_briefcases');
    }
}
