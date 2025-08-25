<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            "name" => "Home_Index",
            "guard_name" => "web"
        ]);
        DB::table('permissions')->insert([
            "name" => "Analitica_Index",
            "guard_name" => "web"
        ]);

        /** Usuario */
        DB::table('permissions')->insert([ // Visualizar (Autenticación) en el menu lateral
            "name" => "Usuario_Index",
            "guard_name" => "web"
        ]);
        DB::table('permissions')->insert([
            "name" => "Usuario_Buscar",
            "guard_name" => "web"
        ]);
        DB::table('permissions')->insert([
            "name" => "Usuario_Crear",
            "guard_name" => "web"
        ]);
        DB::table('permissions')->insert([
            "name" => "Usuario_Editar",
            "guard_name" => "web"
        ]);
        DB::table('permissions')->insert([
            "name" => "Usuario_Restaurar",
            "guard_name" => "web"
        ]);
        DB::table('permissions')->insert([
            "name" => "Usuario_Eliminar",
            "guard_name" => "web"
        ]);
        DB::table('permissions')->insert([
            "name" => "Usuario_Eliminar_Permanente",
            "guard_name" => "web"
        ]);

        /** posts */

        DB::table('permissions')->insert([
            "name" => "Post_Index",
            "guard_name" => "web"
        ]);
        DB::table('permissions')->insert([
            "name" => "Post_Buscar",
            "guard_name" => "web"
        ]);
        DB::table('permissions')->insert([
            "name" => "Post_Crear",
            "guard_name" => "web"
        ]);
        DB::table('permissions')->insert([
            "name" => "Post_Editar",
            "guard_name" => "web"
        ]);
        DB::table('permissions')->insert([
            "name" => "Post_Restaurar",
            "guard_name" => "web"
        ]);
        DB::table('permissions')->insert([
            "name" => "Post_Eliminar",
            "guard_name" => "web"
        ]);
        DB::table('permissions')->insert([
            "name" => "Post_Eliminar_Permanente",
            "guard_name" => "web"
        ]);

        /** Perfil */
        DB::table('permissions')->insert([ // Visualizar (Autenticación) en el menu lateral
            "name" => "Perfil_Index",
            "guard_name" => "web"
        ]);
        DB::table('permissions')->insert([
            "name" => "Perfil_Editar",
            "guard_name" => "web"
        ]);
    }
}
