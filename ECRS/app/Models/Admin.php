<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Admin extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'admin_tbl';
    protected $primaryKey = 'adminID';
    protected $fillable = [
        'Lname',
        'Fname',
        'Mname',
        'Sname',
        'schoolYear',
        'schoolIDNo',
        'isActive',
        'isSentCredentials',
        'semester',
        'branch',
        'signature',
        'loginID',
    ];

    public function login()
    {
        return $this->belongsTo(Login::class, 'loginID', 'loginID');
    }

    public function registration()
    {
        return $this->hasOne(Registration::class, 'branch', 'branch');
    }

    public function branchDetail()
    {
        return $this->belongsTo(Branch::class, 'branch', 'branchID'); 
    }
}
