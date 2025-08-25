<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'Administrador';
        $user->email = 'admin@email.com';
        $user->password = Hash::make('admin123');
        $user->birthdate = '1999/04/23';
        $user->job_title = 'Título profesional';
        $user->address = 'Av. Libertador 1234, Buenos Aires, Argentina';
        $user->phone = '+123456789';
        $user->role = 'Super Admin';
        $user->account_state = 'Active';
        $user->save();
    }
}
