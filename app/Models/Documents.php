<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Documents extends Model
{
    use HasFactory;

    protected $fillable = [

        'name',
        'template',
        'state',
        'order',
        'responsible_id',
        'created_by',
        'archived',
        'archived_at',
        'archived_by',
    ];

    public function responsible_id()
    {
        return $this->belongsTo(Role::class, 'responsible_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
    public function briefcases()
    {
        return $this->belongsToMany(Briefcase::class, 'files','briefcase_id','document_id')
            ->using(File::class)
            ->withPivot(['name', 'type', 'content', 'observation', 'state', 'size']);
    }
    */

    public function briefcases()
    {
        return $this->belongsToMany(Briefcase::class, 'files', 'document_id', 'briefcase_id')
            ->using(File::class)
            ->withPivot(['name', 'type', 'content', 'observation', 'state', 'size']);
    }

    

    public function files()
    {
        return $this->hasMany(File::class, 'document_id');
    }
}