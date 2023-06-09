<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'certificate_url',
        'certificate_type_id',
        'certificate_status_id',
        'project_participants_id',
    ];

    public function certificateType()
    {
        return $this->belongsTo(Catalog::class, 'certificate_type_id');
    }

    public function certificateStatus()
    {
        return $this->belongsTo(Catalog::class, 'certificate_status_id');
    }

    public function projectParticipants()
    {
        return $this->belongsTo(Catalog::class, 'project_participants_id');
    }
}
