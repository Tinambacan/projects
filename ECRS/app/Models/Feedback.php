<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback_tbl';
    protected $primaryKey = 'feedbackID';
    public $timestamps = true;

    protected $fillable = [
        'subject',
        'body',
        'studentID',
        'loginID',
        'classRecordID',
    ];

    public function classRecord()
    {
        return $this->belongsTo(ClassRecord::class, 'classRecordID');
    }

    public function student()
    {
        return $this->belongsTo(Login::class, 'loginID', 'loginID');
    }

    public function login()
    {
        return $this->belongsTo(Login::class, 'loginID', 'loginID');
    }
}
