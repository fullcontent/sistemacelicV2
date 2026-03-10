<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            // Financeiro
            'financeiro_gerar_relatorio',
            'financeiro_gerar_reembolso',
            'financeiro_emitir_nf',
            'financeiro_editar_faturamento',
            'financeiro_visualizar_faturamento',
            // Serviços
            'servicos_criar',
            'servicos_editar',
            'servicos_alterar_situacao',
            'servicos_editar_historico',
            'servicos_visualizar_historico_privado',
            'servicos_excluir',
            // Ordens de Compra
            'oc_criar',
            'oc_editar',
            'oc_lancar_pagamento',
            'oc_visualizar_nf',
            'oc_excluir',
            // Administração
            'admin_criar_usuario',
            'admin_editar_usuario',
            'admin_atribuir_roles',
            'admin_editar_permissoes',
            'admin_visualizar_logs',
            // Programador especial
            'dev_recuperar_conta',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        // create roles and assign created permissions

        // ======================= CASTRO =======================

        // Nivel 1
        $roleDev = \Spatie\Permission\Models\Role::create(['name' => 'Programador (CASTRO)', 'hierarchy_level' => 1]);
        $roleDev->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        // Nivel 2
        $roleAdminCastro = \Spatie\Permission\Models\Role::create(['name' => 'Administrador (CASTRO)', 'hierarchy_level' => 2]);
        $roleAdminCastro->givePermissionTo([
            'admin_criar_usuario',
            'admin_editar_usuario',
            'admin_atribuir_roles',
            'admin_editar_permissoes',
            'admin_visualizar_logs',
            'dev_recuperar_conta', // According to rules: Admin CASTRO can recover dev account
        ]);

        // Nivel 3
        $roleGestorCastro = \Spatie\Permission\Models\Role::create(['name' => 'Gestor (CASTRO)', 'hierarchy_level' => 3]);
        $roleGestorCastro->givePermissionTo([
            'financeiro_visualizar_faturamento',
            'servicos_visualizar_historico_privado',
            'oc_visualizar_nf'
        ]);

        // Nivel 4
        $roleCoordenadorCastro = \Spatie\Permission\Models\Role::create(['name' => 'Coordenador (CASTRO)', 'hierarchy_level' => 4]);

        // Nivel 5
        $roleOpCastro = \Spatie\Permission\Models\Role::create(['name' => 'Operacional (CASTRO)', 'hierarchy_level' => 5]);
        $roleFinCastro = \Spatie\Permission\Models\Role::create(['name' => 'Financeiro (CASTRO)', 'hierarchy_level' => 5]);

        // ======================= CLIENTE =======================

        $roleAdminCliente = \Spatie\Permission\Models\Role::create(['name' => 'Administrador (CLIENTE)', 'hierarchy_level' => 2]);
        $roleGestorCliente = \Spatie\Permission\Models\Role::create(['name' => 'Gestor (CLIENTE)', 'hierarchy_level' => 3]);
        $roleCoordenadorCliente = \Spatie\Permission\Models\Role::create(['name' => 'Coordenador (CLIENTE)', 'hierarchy_level' => 4]);
        $roleOpCliente = \Spatie\Permission\Models\Role::create(['name' => 'Operacional (CLIENTE)', 'hierarchy_level' => 5]);
        $roleFinCliente = \Spatie\Permission\Models\Role::create(['name' => 'Financeiro (CLIENTE)', 'hierarchy_level' => 5]);
    }
}
