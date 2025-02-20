<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Login extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $table = 'login_tbl';
    protected $primaryKey = 'loginID';
    protected $fillable = [
        'email',
        'password',
    ];

    public function registration()
    {
        return $this->hasOne(Registration::class, 'loginID', 'loginID');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'loginID', 'loginID');
    }

    public function superadmin()
    {
        return $this->hasOne(SuperAdmin::class, 'loginID', 'loginID');
    }
}

