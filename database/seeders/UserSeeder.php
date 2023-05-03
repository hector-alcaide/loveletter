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
                'idUser'     => 1,
                'alias'         => 'Jake',
                'email'         => 'jake@mail.com',
                'password'      => bcrypt("123"),
            ],
            [
                'idUser'     => 2,
                'alias'         => 'Hector',
                'email'         => 'hector@mail.com',
                'password'      => bcrypt("123"),
            ],
            [
                'idUser'     => 3,
                'alias'         => 'Ferran',
                'email'         => 'ferran@mail.com',
                'password'      => bcrypt("123"),
            ],
            [
                'idUser'     => 4,
                'alias'         => 'David',
                'email'         => 'david@mail.com',
                'password'      => bcrypt("123"),
            ],
            [
                'idUser'     => 5,
                'alias'         => 'Luis',
                'email'         => 'luis@mail.com',
                'password'      => bcrypt("123"),
            ],
            [
                'idUser'     => 6,
                'alias'         => 'Miriam',
                'email'         => 'miriam@mail.com',
                'password'      => bcrypt("123"),
            ]
        ];
        User::insert($users);
    }
}
