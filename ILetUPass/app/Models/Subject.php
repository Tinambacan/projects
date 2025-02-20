<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Subject extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'tblsubject';
    
    protected $primaryKey = 'subject_ID';
    protected $fillable = ['subject_name', 'subject_desc', 'subject_image'];

    public static function getTableColumns()
    {
        return Schema::getColumnListing('tblsubject');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'subject_ID', 'subject_ID');
    }
}
