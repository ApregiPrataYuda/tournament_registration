<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'fullname' => 'apregi',
                'email' => 'apregi@gmail.com',
                'gender' => 'Male',
                'email_verified_at' => null,
                'password' => Hash::make('s:!$!2ETfbVA17J8P*PPQJqNqNqHVH.ETtYUtn2oeJgnov/QDWcC35Y2'),
                'role' => 1,
                'phone_number' => '089907654321',
                'remember_token' => null,
                'deleted_at' => null,
                'created_at' => '2025-08-24 02:00:28',
                'updated_at' => '2025-08-24 02:00:29',
            ],
            [
                'fullname' => 'marks',
                'email' => 'marks@gmail.com',
                'username' => 'marks',
                'email_verified_at' => null,
                'password' => Hash::make('s:!$!2ETfbVA17J8P*PPQJqNqNqHVH.ETtYUtn2oeJgnov/QDWcC35Y2'),
                'role' => 2,
                'phone_number' => '089907654321',
                'remember_token' => null,
                'deleted_at' => null,
                'created_at' => '2025-08-24 02:00:28',
                'updated_at' => '2025-08-24 02:00:29',
            ],
        ]);
    }
}