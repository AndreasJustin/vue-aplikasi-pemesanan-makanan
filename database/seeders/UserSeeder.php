<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        User::insert([
            [
                'name' => 'manager',
                'email' => 'manager@email.com',
                'password' => bcrypt('password'),
                'role_id' => 4, // Assuming 'manager' role has ID 4
            ],
            [
                'name' => 'chef',
                'email' => 'chef@email.com',
                'password' => bcrypt('password'),
                'role_id' => 2, // Assuming 'chef' role has ID 2
            ],
        ]);
    }
}
