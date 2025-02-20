<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;

    protected $table = 'course_tbl';
    protected $primaryKey = 'courseID';
    protected $fillable = [
        'courseCode',
        'courseTitle',
        'programID'
    ];

    public function program()
    {
        return $this->belongsTo(Programs::class, 'programID', 'programID');
    }
}
