<template>
    <div class="container mx-auto max-w-7xl p-4">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Roles</h1>
                <p class="text-gray-600 dark:text-gray-400">Gerencie as roles do sistema</p>
            </div>
            <Link
                :href="rolesCreate.url()"
                class="rounded-lg bg-blue-600 px-4 py-2 font-medium text-white transition-colors hover:bg-blue-700"
            >
                + Nova Role
            </Link>
        </div>

        <div class="rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Lista de Roles</h2>
            </div>
            <div class="p-6">
                <div class="grid gap-4">
                    <div
                        v-for="role in roles"
                        :key="role.id"
                        class="rounded-lg border border-gray-200 p-4 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="mb-2 flex items-center gap-3">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ role.display_name || role.name }}
                                    </h3>
                                    <span class="rounded bg-gray-100 px-2 py-1 font-mono text-sm text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                        {{ role.name }}
                                    </span>
                                </div>
                                <p v-if="role.description" class="text-gray-600 dark:text-gray-400">
                                    {{ role.description }}
                                </p>
                            </div>
                            <div class="ml-4 flex gap-2">
                                <Link
                                    :href="rolesEdit.url(role.id)"
                                    class="text-sm font-medium text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300"
                                >
                                    Editar
                                </Link>
                                <button
                                    @click="deleteRole(role.id)"
                                    class="text-sm font-medium text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                >
                                    Deletar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { create as rolesCreate, destroy as rolesDestroy, edit as rolesEdit } from '@/routes/roles';
import { Link, router } from '@inertiajs/vue3';
import type { Permission } from '@/types/permission';
import type { Role } from '@/types/role';

defineProps<{
    roles: Role[];
}>();

const deleteRole = (roleId: number) => {
    if (confirm('Tem certeza que deseja deletar esta role?')) {
        router.delete(rolesDestroy.url(roleId));
    }
};
</script>
