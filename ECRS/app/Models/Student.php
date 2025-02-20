<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Student extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'student_tbl';
    protected $primaryKey = 'studentID';
    protected $fillable = [
        'studentNo',
        'studentLname',
        'studentFname',
        'studentMname',
        'email',
        'mobileNo',
        'remarks',
        'classRecordID',
    ];

    public function classrecord()
    {
        return $this->belongsTo(ClassRecord::class, 'classRecordID', 'classRecordID');
    }

    public function registration()
    {
        return $this->hasOne(Registration::class, 'schoolIDNo', 'studentNo'); 
    }
}
