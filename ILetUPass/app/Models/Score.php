<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $table = 'tblscore';
    protected $primaryKey = 'score_ID';

    protected $fillable = [
        'score',
        'level',
        'login_ID',
        'subject_ID',
    ];

    public function registration()
    {
        return $this->hasOne(Registration::class, 'login_ID', 'login_ID');
    }

    public function subject(){
        return $this->belongsTo(Subject::class, 'subject_ID', 'subject_ID');
    }

}
