<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Registration extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'registration_tbl';
    protected $primaryKey = 'registrationID';
    protected $fillable = [
        'Lname',
        'Fname',
        'Mname',
        'Sname',
        'role',
        'branch',
        'salutation',
        'schoolIDNo',
        'isActive',
        'isSentCredentials',
        'signature',
        'adminID',
        'loginID',
    ];

    public function login()
    {
        return $this->belongsTo(Login::class, 'loginID', 'loginID');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'branch', 'branch');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch', 'branchID'); // Adjust 'branchID' to match your Branch table’s primary key
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'schoolIDNo', 'studentNo'); // Adjust as needed
    }
}
