<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amistad extends Model
{
    use HasFactory;

    protected $table = 'friendships';

    //public $timestamps = true;
    protected $fillable = [
        'idUser1',
        'idUser2',
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser1')->orWhere('idUser2', $this->idUser);
    }

}
