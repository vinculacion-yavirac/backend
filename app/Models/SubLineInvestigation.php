<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubLineInvestigation extends Model
{
    use HasFactory;

    protected $table = 'sub_lines_investigations';

    protected $fillable = [
        'description',
        'research_line_id',
    ];

    public function researchLine()
    {
        return $this->belongsTo(ResearchLine::class);
    }
}
