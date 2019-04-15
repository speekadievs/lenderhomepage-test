<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name'     => 'Admin',
                'email'    => 'admin@lenderhomepage.com',
                'password' => 'lenderhomepage2019',
                'is_admin' => 1
            ],
            [
                'name'     => 'User',
                'email'    => 'user@lenderhomepage.com',
                'password' => 'lenderhomepage2019',
                'is_admin' => 0
            ]
        ];

        foreach ($users as $user) {
            User::create([
                'name'     => $user['name'],
                'email'    => $user['email'],
                'password' => bcrypt($user['password']),
                'is_admin' => $user['is_admin']
            ]);
        }
    }
}
