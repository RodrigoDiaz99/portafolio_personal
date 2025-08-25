<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            "name" => "Super Admin",
            "guard_name" => "web"
        ]);
        DB::table('roles')->insert([
            "name" => "Admin",
            "guard_name" => "web"
        ]);
        DB::table('roles')->insert([
            "name" => "Editor",
            "guard_name" => "web"
        ]);
        DB::table('roles')->insert([
            "name" => "Lector",
            "guard_name" => "web"
        ]);

    }
}

