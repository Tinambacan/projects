<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssessment extends Model
{
    use HasFactory;

    protected $table = 'student_assessment_tbl';
    protected $primaryKey = 'studentAssessmentID';
    public $timestamps = true;

    protected $fillable = [
        'studentID',
        'assessmentID',
        'classRecordID',
        'score',
        'remarks',
        'isRequestedToView',
        'isRawScoreViewable'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'studentID', 'studentID');
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessmentID', 'assessmentID');
    }

    public function classrecord()
    {
        return $this->belongsTo(ClassRecord::class, 'classRecordID', 'classRecordID');
    }
}
