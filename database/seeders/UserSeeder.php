<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Senha de todos: "password"
        User::create(['name' => 'Dono do Evento', 'email' => 'dono@falaq.test', 'password' => 'password']);   // id 1
        User::create(['name' => 'Autor',          'email' => 'autor@falaq.test', 'password' => 'password']);  // id 2
        User::create(['name' => 'Terceiro',       'email' => 'terceiro@falaq.test', 'password' => 'password']); // id 3
    }
}
