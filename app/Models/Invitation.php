<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $table = 'invitations';

    protected $primaryKey = 'idInvitation';

    protected $fillable = [
        'idSender',
        'idReceptor',
        'status',
        'idGame',
    ];

    public function game(){
        return $this->hasOne(Game::class, 'idGame', 'idGame');
    }

    public function sender(){
        return $this->hasOne(User::class, 'idUser', 'idSender');
    }
}
