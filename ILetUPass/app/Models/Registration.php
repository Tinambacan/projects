<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;
    protected $table = 'tblregistration';
    protected $primaryKey = 'registration_ID';
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'role',
        'login_ID',
        'isActive'
    ];
    public function login()
    {
        return $this->belongsTo(Login::class, 'login_ID');
    }
}

