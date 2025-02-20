<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    use HasFactory;

    protected $connection = 'auditlogs';

    protected $table = 'audit_trail';

    protected $fillable = [
        'record_id',
        'user',
        'action',
        'table_name',
        'old_value',
        'new_value',
        'description',
        'action_time',
    ];

    public $timestamps = false;

}
