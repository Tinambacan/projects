<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = 'schedule_tbl';
    protected $primaryKey = 'scheduleID';
    public $timestamps = true;
    
    protected $fillable = [
        'scheduleDay',
        'scheduleTime',
        'classRecordID',
    ];

    public function classRecord()
    {
        return $this->belongsTo(ClassRecord::class, 'classRecordID');
    }
}
