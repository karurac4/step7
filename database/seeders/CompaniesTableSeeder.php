<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 5; $i++) {
            DB::table('companies')->insert([
                'company_name' => 'Company ' . $i,
                'street_adress' => 'Street Address ' . $i,
                'representative_name' => 'Representative Name ' . $i,
                
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
