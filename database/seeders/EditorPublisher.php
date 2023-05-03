<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EditorPublisher extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 30; $i++) {
            DB::table('user_publisher')->insert([
                'user_id' => rand(1, 10),
                'publisher_id' => rand(1, 10),
            ]);
        }
    }
}
