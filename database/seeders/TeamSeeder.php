<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('teams')->insert([
            'team_name' => 'team_ones',
            'captain_id' => 2,
            'captain_gender' => 'male',
            'created_at' => '2025-08-24 10:55:19',
            'updated_at' => '2025-08-24 10:55:20',
            'deleted_at' => null,
        ]);
    }
}