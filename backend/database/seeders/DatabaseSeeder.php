<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Administrator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Administrator::create([
            'username' => 'admin1',
            'password' => Hash::make('hellouniverse1!')
        ]);
        Administrator::create([
            'username' => 'admin2',
            'password' => Hash::make('hellouniverse2!')
        ]);

        User::create([
            'username' => 'dev1',
            'password' => Hash::make('hellobyte1!')
        ]);
        User::create([
            'username' => 'dev2',
            'password' => Hash::make('hellobyte2!')
        ]);

        User::create([
            'username' => 'player1',
            'password' => Hash::make('helloworld1!')
        ]);
        User::create([
            'username' => 'player2',
            'password' => Hash::make('helloworld2!')
        ]);
    }
}
