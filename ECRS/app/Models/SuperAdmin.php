<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SuperAdmin extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'super_admin_tbl';
    protected $primaryKey = 'superAdminID';
    protected $fillable = [
        'Lname',
        'Fname',
        'Mname',
        'Sname',
        'isActive',
        'salutation',
        'signature',
        'loginID',
    ];

    public function login()
    {
        return $this->belongsTo(Login::class, 'loginID', 'loginID');
    }
 
}
