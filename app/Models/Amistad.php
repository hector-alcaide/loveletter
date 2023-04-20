<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amistad extends Model
{
    use HasFactory;

    protected $table = 'amistad';

    //public $timestamps = true;
    protected $fillable = [
        'idUsuario1',
        'idUsuario2',
        'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario1')->orWhere('idUsuario2', $this->idUsuario);
    }

}
