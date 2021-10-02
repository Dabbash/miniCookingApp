<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            'name' => 'Papai Sarkar',
            'email' => 'admin@test.com',
            'password' => Hash::make('123456'),
        ];

        User::insert($users);
    }
}
