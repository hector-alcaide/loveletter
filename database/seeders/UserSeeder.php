<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = [
            [
                'idUsuario'     => 1,
                'alias'         => 'jake',
                'email'         => 'j',
                'password'      => bcrypt("123"),
            ],
            [
                'idUsuario'     => 2,
                'alias'         => 'hector',
                'email'         => 'h',
                'password'      => bcrypt("123"),
            ],
            [
                'idUsuario'     => 3,
                'alias'         => 'ferran',
                'email'         => 'f',
                'password'      => bcrypt("123"),
            ],
            [
                'idUsuario'     => 4,
                'alias'         => 'david',
                'email'         => 'd',
                'password'      => bcrypt("123"),
            ],
        ];
        User::insert($users);
    }
}
