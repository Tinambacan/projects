<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedFile extends Model
{
    use HasFactory;

    protected $table = 'submitted_files_tbl';
    protected $primaryKey = 'fileID';
    protected $fillable = [
        'file',
        'status',
        'classRecordID',
    ];

    public function classrecord()
    {
        return $this->belongsTo(ClassRecord::class, 'classRecordID', 'classRecordID');
    }
}
