<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'tblanswer';

    protected $primaryKey = 'answer_ID';
    protected $fillable = [
        'choices_desc',
        'answer',
        'question_ID',
    ];

}
