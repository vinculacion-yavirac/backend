<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeneficiaryInstitution extends Model
{
    use HasFactory;

        protected $table = 'beneficiary_institutions';

        protected $fillable = [
            'ruc',
            'name',
            'name_gestion',
            'name_autorize_by',
            'activity_ruc',
            'email',
            'phone',
            'address',
            'number_students_start',
            'number_students_ability',
            'Direct beneficiaries',
            'Indirect beneficiaries',
            'logo',
            'state',
            'place_location',
            'postal_code',
            'parish_id',
        ];

        public function parish_id()
        {
            return $this->belongsTo(Address::class, 'parish_id');
        }

        public function archived_by()
        {
            return $this->belongsTo(User::class, 'archived_by');
        }

        public function projects()
        {
            return $this->hasMany(Project::class, 'beneficiary_institution_id');
        }

        public static function boot()
    {
        parent::boot();

        // Eliminar proyectos relacionados antes de eliminar la institución beneficiaria
        static::deleting(function ($beneficiaryInstitution) {
            $beneficiaryInstitution->projects()->delete();
        });
    }
}
