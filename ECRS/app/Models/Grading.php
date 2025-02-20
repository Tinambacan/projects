<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grading extends Model
{
    use HasFactory;
    protected $table = 'grading_tbl';
    protected $primaryKey = 'gradingID';
    public $timestamps = true;
    
    protected $fillable = [
        'assessmentType',
        'term',
        'percentage',
        'isExamination',
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
