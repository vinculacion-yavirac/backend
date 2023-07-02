<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'code',
        'name',
        'field',
        'term_execution',
        'start_date',
        'end_date',
        'linking_activity',
        'sectors_intervention',
        'strategic_axes',
        'description',
        'situational_analysis',
        'foundation',
        'justification',
        'direct_beneficiaries',
        'indirect_beneficiaries',
        'schedule',
        'evaluation_monitoring_strategy',
        'bibliographies',
        'attached_project',
        'convention_id',
        'school_period_id',
        'beneficiary_institution_id',
        'career_id',
        'sub_line_investigation_id',
        'authorized_by',
        'made_by',
        'approved_by',
        'catalogue_id',
        'state_id',
        'stateTwo_id',
        'frequency_id',
        'created_by',
        'archived',
        'archived_at',
        'archived_by',
        'coverage',
        'modality',
        'financing',
        'institute_id'
    ];

    protected $casts = [
        'linking_activity' => 'json',
        'sectors_intervention' => 'json',
        'strategic_axes' => 'json',
        'direct_beneficiaries' => 'json',
        'indirect_beneficiaries' => 'json',
        'evaluation_monitoring_strategy' => 'json',
        'bibliographies' => 'json',
        'attached_project' => 'json',
    ];

    public function convention()
    {
        return $this->belongsTo(Convention::class);
    }

    public function schoolPeriod()
    {
        return $this->belongsTo(SchoolPeriod::class);
    }

    public function beneficiary_institution_id()
    {
        return $this->belongsTo(BeneficiaryInstitution::class, 'beneficiary_institution_id');
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

    public function career()
    {
        return $this->belongsTo(Career::class, 'career_id');
    }

    public function subLineInvestigation()
    {
        return $this->belongsTo(SubLineInvestigation::class);
    }

    public function authorizedBy()
    {
        return $this->belongsTo(Responsible::class, 'authorized_by');
    }

    public function madeBy()
    {
        return $this->belongsTo(Responsible::class, 'made_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Responsible::class, 'approved_by');
    }

    public function state()
    {
        return $this->belongsTo(Catalog::class, 'state_id');
    }

    public function stateTwo()
    {
        return $this->belongsTo(Catalog::class, 'stateTwo_id');
    }

    public function frequency()
    {
        return $this->belongsTo(Catalog::class, 'frequency_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
