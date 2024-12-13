<?php

namespace Database\Seeders;

use App\Models\AccessControl;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessControllersIntial extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(AccessControl::all()->count() === 0) {
            DB::table('access_controls')->insert([
                'access_name' => 'ADMIN',
                'access_level' => 2
            ]);
            DB::table('access_controls')->insert([
                'access_name' => 'MANAGER',
                'access_level' => 1
            ]);
            DB::table('access_controls')->insert([
                'access_name' => 'USER',
                'access_level' => 0
            ]);
        }


    }
}
