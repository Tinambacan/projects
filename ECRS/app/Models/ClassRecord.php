<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ClassRecord extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'class_record_tbl';
    protected $primaryKey = 'classRecordID';
    protected $fillable = [
        'schoolYear',
        'semester',
        'yearLevel',
        'classImg',
        'template',
        'recordType',
        'branch',
        'isArchived',
        'isSubmitted',
        'status',
        'programID',
        'courseID',
        'loginID',
    ];

    public function program()
    {
        return $this->belongsTo(Programs::class, 'programID', 'programID');
    }

    public function course()
    {
        return $this->belongsTo(Courses::class, 'courseID', 'courseID');
    }

    public function login()
    {
        return $this->belongsTo(Login::class, 'loginID', 'loginID');
    }

    public function grading()
    {
        return $this->hasMany(Grading::class, 'classRecordID', 'classRecordID');
    }

    public function gradingDistribution()
    {
        return $this->hasMany(GradingDistribution::class, 'classRecordID', 'classRecordID');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'classRecordID', 'classRecordID');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'classRecordID', 'classRecordID');
    }

    public function branchDetail()
    {
        return $this->belongsTo(Branch::class, 'branch', 'branchID');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'classRecordID', 'studentID');
    }

    
}
