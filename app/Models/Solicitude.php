<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Solicitude extends Model
{
    use HasFactory;

    // Define la tabla asociada al modelo
    protected $table = 'solicitudes';

    // Define las columnas que se pueden asignar masivamente
    protected $fillable = [
        'approval_date',
        'solicitudes_status_id',
        'type_request_id',
        'who_made_request_id',
        'created_by',
        'archived',
        'archived_at',
        'archived_by',
        'project_id'
    ];

    // Relación con el modelo Catalog para el estado de las solicitudes
    public function solicitudes_status_id()
    {
        return $this->belongsTo(Catalogue::class, 'solicitudes_status_id');
    }

    // Relación con el modelo Catalog para el tipo de solicitud
    public function type_request_id()
    {
        return $this->belongsTo(Catalogue::class, 'type_request_id');
    }

    // Relación con el modelo ProjectParticipant para el solicitante de la solicitud
    public function project_id()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relación con el modelo User para el archivador de la solicitud
    public function archived_by()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

}
