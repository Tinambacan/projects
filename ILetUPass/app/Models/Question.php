<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'tblquestion';

    protected $primaryKey = 'question_ID';
    protected $fillable = [
        'question_desc',
        'question_exp',
        'level',
        'subject_ID',
    ];

    public function answers() {
        return $this->hasMany(Answer::class, 'question_ID', 'question_ID');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_ID', 'subject_ID');
    }

}
