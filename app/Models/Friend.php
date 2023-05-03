<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;

    protected $table = 'friend';

    //public $timestamps = true;
    protected $fillable = [
        'idUser1',
        'idUser2',
        'estado',
    ];

}
