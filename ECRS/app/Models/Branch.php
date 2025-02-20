<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branch_tbl';
    protected $primaryKey = 'branchID';
    protected $fillable = [
        'branchDescription',
    ];

    public function classRecords()
    {
        return $this->hasMany(ClassRecord::class, 'branch', 'branchID');
    }

    public function admin()
    {
        return $this->hasMany(Admin::class, 'branch', 'branchID'); 
    }
}

