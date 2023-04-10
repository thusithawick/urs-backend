<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create sample users
        $users = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'gender' => 'male',
                'date_of_birth' => '1990-01-01',
                'email' => 'johndoe@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'gender' => 'female',
                'date_of_birth' => '1992-05-15',
                'email' => 'janedoe@example.com',
                'password' => bcrypt('password'),
            ],
            // Add more users as needed
        ];

        // Insert the users into the database
        foreach ($users as $userData) {
            $user = new User();
            $user->fill($userData);
            $user->save();
        }
    }
}
