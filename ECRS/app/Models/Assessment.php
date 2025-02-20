<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $table = 'assessment_tbl';
    protected $primaryKey = 'assessmentID';
    public $timestamps = true;
    
    protected $fillable = [
        'assessmentType',
        'assessmentName',
        'totalItem',
        'passingItem',
        'assessmentDate',
        'term',
        'isPublished',
        'classRecordID',
    ];

    public function classrecord()
    {
        return $this->belongsTo(ClassRecord::class, 'classRecordID', 'classRecordID');
    }
}
