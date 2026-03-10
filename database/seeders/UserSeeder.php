<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Garante que o usuário programador master exista no banco, mesmo que o legado não o traga
        \App\Models\User::firstOrCreate(
            ['email' => 'bgc1988@gmail.com'],
            [
                'name' => 'Desenvolvedor Master',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'group_type' => 'ADMIN',
                'status' => true,
            ]
        );

        // Pega todos os usuários do banco (importados possivelmente do dump legado + recém criados)
        $users = \App\Models\User::all();

        foreach ($users as $user) {
            $email = strtolower($user->email);
            $groupType = 'CLIENTE';

            if ($user->id === 1 || $email === 'bgc1988@gmail.com') {
                $groupType = 'ADMIN';
            } elseif (str_ends_with($email, '@castroli.com.br')) {
                $groupType = 'CASTRO';
            }

            // Atualiza o tipo de grupo
            $user->update([
                'group_type' => $groupType,
                'status' => true,
            ]);

            // Se for o programador, dá role de Programador (CASTRO)
            if ($email === 'bgc1988@gmail.com') {
                $user->assignRole('Programador (CASTRO)');
                // Você pode querer forçar uma senha se for necessário login imediato
                $user->update(['password' => \Illuminate\Support\Facades\Hash::make('password')]);
            }
        }
    }
}
