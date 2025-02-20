<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Programs extends Model
{
    use HasFactory;

    protected $table = 'program_tbl';
    protected $primaryKey = 'programID';
    protected $fillable = [
        'programCode',
        'programTitle',
        'branch',
    ];

    public function courses()
    {
        return $this->hasMany(Courses::class, 'programID', 'programID');
    }


    public function scopeByBranch($query)
    {
        $branch = session('branch');

        if ($branch !== null) {
            return $query->where('branch', $branch);
        }

        return $query;
    }
    
}
