<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradingDistribution extends Model
{
    use HasFactory;
    protected $table = 'grading_distribution_tbl';
    protected $primaryKey = 'gradingDistributionID';
    public $timestamps = true;
    
    protected $fillable = [
        'gradingDistributionType',
        'gradingDistributionPercentage',
        'term',
        'isPublished',
        'classRecordID',
    ];

    protected $casts = [
        'term' => 'integer',
        'percentage' => 'decimal:2',
    ];

    public function classRecord()
    {
        return $this->belongsTo(ClassRecord::class, 'classRecordID');
    }
}
