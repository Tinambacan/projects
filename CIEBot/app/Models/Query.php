<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Query extends Model
{
    use Searchable;

    protected $table = 'tblquery';
    protected $primaryKey = 'id';
    protected $fillable = [
        'answer_query',
    ];
}
