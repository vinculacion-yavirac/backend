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
    ];

    public function responsible_id()
    {
        return $this->belongsTo(Role::class, 'responsible_id');
    }

    public function briefcases()
    {
        return $this->belongsToMany(Briefcase::class, 'files')->withPivot(['name', 'type', 'content', 'observation', 'state', 'size']);
    }

    public static function firstOrCreate(array $attributes, array $values = [])
    {
        return static::query()->firstOrNew($attributes, $values)->fill($values);
    }
}
