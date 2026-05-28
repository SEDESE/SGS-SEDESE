<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@sedese.mg.gov.br',
            'password' => Hash::make('Admin@1234'),
            'role'     => Role::Administrador,
            'ativo'    => true,
        ]);
    }
}