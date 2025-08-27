<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('team_members')->insert([
            'team_id' => 1,
            'full_name_member' => 'joshua',
            'phone_number_member' => '089976976543',
            'gender_member' => 'male',
            'created_at' => '2025-08-24 10:55:54',
            'updated_at' => '2025-08-24 10:55:55',
            'deleted_at' => null,
        ]);
    }
}