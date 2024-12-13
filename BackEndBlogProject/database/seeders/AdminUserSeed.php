<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if(!User::where("name", "Super Admin")->where('email', "admin@email.com")->exists()){
            DB::table('users')->insert([
                'name' => "Super Admin",
                'email' => "admin@email.com",
                'password' => Hash::make('Gustavo123@'),
                'access_id' => 1,
            ]);
        }

    }
}
