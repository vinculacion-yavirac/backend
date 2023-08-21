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
        'name_institute',
        'cicle',
        'address',
        'Modality',
        'field',
        'term_execution',
        'start_date',
        'end_date',
        'date_presentation',
        'frequency_activity',
        'activity_vinculation',
        'intervention_sectors',
        'linking_activity',
        'schedule',
        'schedule_crono',
        'financing',
        'sectors_intervention',
        'strategic_axes',
        'objetive',
        'description',
        'situational_analysis',
        'foundation',
        'justification',
        'direct_beneficiaries',
        'indirect_beneficiaries',
        'evaluation_monitoring_strategy',
        'bibliographies',
        'conclusions',
        'recommendation',
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
        return $this->belongsTo(BeneficiaryInstitution::class,'beneficiary_institution_id');
    }

    public function career_id()
    {
        return $this->belongsTo(Career::class,'career_id');
    }

    public function subLineInvestigation()
    {
        return $this->belongsTo(SubLineInvestigation::class);
    }

    public function authorized_by()
    {
        return $this->belongsTo(Responsible::class, 'authorized_by');
    }

    public function made_by()
    {
        return $this->belongsTo(Responsible::class, 'made_by');
    }

    public function approved_by()
    {
        return $this->belongsTo(Responsible::class, 'approved_by');
    }

    public function state()
    {
        return $this->belongsTo(Catalogue::class, 'state_id');
    }

    public function stateTwo_id()
    {
        return $this->belongsTo(Catalogue::class, 'stateTwo_id');
    }

    public function frequency()
    {
        return $this->belongsTo(Catalogue::class, 'frequency_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function archived_by()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }
}
