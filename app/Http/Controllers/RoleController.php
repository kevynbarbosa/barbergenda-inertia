<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    public function index(): Response
    {
        $roles = Role::all();

        return Inertia::render('rbac/RolesAndPermissions', [
            'roles' => $roles,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('roles/Create');
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::create($request->only(['name', 'display_name', 'description']));

        // Sync permissions if provided
        if ($request->has('permissions') && is_array($request->permissions)) {
            $permissionIds = $this->getPermissionIds($request->permissions);
            $role->permissions()->sync($permissionIds);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role criada com sucesso.');
    }


    public function edit(Role $role): Response
    {
        $role->load('permissions');

        // Convert permissions to the format expected by the frontend
        $roleData = $role->toArray();
        $roleData['permissions'] = $role->permissions->pluck('name')->toArray();

        return Inertia::render('roles/Edit', [
            'role' => $roleData,
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $role->update($request->only(['name', 'display_name', 'description']));

        // Sync permissions if provided
        if ($request->has('permissions') && is_array($request->permissions)) {
            $permissionIds = $this->getPermissionIds($request->permissions);
            $role->permissions()->sync($permissionIds);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role atualizada com sucesso.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Não é possível deletar uma role que possui usuários atribuídos.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deletada com sucesso.');
    }

    private function getPermissionIds(array $permissionNames): array
    {
        return Permission::whereIn('name', $permissionNames)->pluck('id')->toArray();
    }
}
