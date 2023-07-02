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
            'management_nature',
            'economic_activity',
            'logo',
            'state',
            'place_location',
            'phone',
            'email',
            'postal_code',
            'addresses_id',
            'parish_main_id',
            'parish_branch_id'
        ];

        public function addresses_id()
        {
            return $this->belongsTo(Address::class, 'addresses_id');
        }

        public function parish_main_id()
        {
            return $this->belongsTo(Parish::class, 'parish_main_id');
        }

        public function parish_branch_id()
        {
            return $this->belongsTo(Parish::class, 'parish_branch_id');
        }
}
