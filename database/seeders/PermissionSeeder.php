<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'view-users',
                'display_name' => 'Ver Usuários',
                'description' => 'Permite visualizar a lista de usuários',
            ],
            [
                'name' => 'create-users',
                'display_name' => 'Criar Usuários',
                'description' => 'Permite criar novos usuários',
            ],
            [
                'name' => 'edit-users',
                'display_name' => 'Editar Usuários',
                'description' => 'Permite editar usuários existentes',
            ],
            [
                'name' => 'delete-users',
                'display_name' => 'Deletar Usuários',
                'description' => 'Permite deletar usuários',
            ],
            [
                'name' => 'view-roles',
                'display_name' => 'Ver Roles',
                'description' => 'Permite visualizar a lista de roles',
            ],
            [
                'name' => 'create-roles',
                'display_name' => 'Criar Roles',
                'description' => 'Permite criar novas roles',
            ],
            [
                'name' => 'edit-roles',
                'display_name' => 'Editar Roles',
                'description' => 'Permite editar roles existentes',
            ],
            [
                'name' => 'delete-roles',
                'display_name' => 'Deletar Roles',
                'description' => 'Permite deletar roles',
            ],
            [
                'name' => 'view-permissions',
                'display_name' => 'Ver Permissões',
                'description' => 'Permite visualizar a lista de permissões',
            ],
            [
                'name' => 'create-permissions',
                'display_name' => 'Criar Permissões',
                'description' => 'Permite criar novas permissões',
            ],
            [
                'name' => 'edit-permissions',
                'display_name' => 'Editar Permissões',
                'description' => 'Permite editar permissões existentes',
            ],
            [
                'name' => 'delete-permissions',
                'display_name' => 'Deletar Permissões',
                'description' => 'Permite deletar permissões',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
