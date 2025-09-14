<template>
    <div class="container mx-auto max-w-2xl p-4">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Detalhes da Role</h1>
                <p class="text-gray-600 dark:text-gray-400">Informações completas da role</p>
            </div>
            <div class="flex gap-2">
                <Link
                    :href="rolesEdit.url(role.id)"
                    class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700"
                >
                    Editar
                </Link>
                <Link
                    :href="rolesIndex.url()"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Voltar
                </Link>
            </div>
        </div>

        <div class="rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Informações da Role</h2>
            </div>
            <div class="p-6">
                <div class="grid gap-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 text-2xl font-bold text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ role.display_name.charAt(0).toUpperCase() }}
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ role.display_name }}
                            </h3>
                            <p class="font-mono text-sm text-gray-600 dark:text-gray-400">
                                {{ role.name }}
                            </p>
                        </div>
                    </div>

                    <div v-if="role.description" class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700/50">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Descrição
                        </label>
                        <p class="text-gray-900 dark:text-white">
                            {{ role.description }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Data de Criação
                            </label>
                            <p class="mt-1 text-gray-900 dark:text-white">
                                {{ formatDate(role.created_at) }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Última Atualização
                            </label>
                            <p class="mt-1 text-gray-900 dark:text-white">
                                {{ formatDate(role.updated_at) }}
                            </p>
                        </div>
                    </div>

                    <div v-if="role.users_count !== undefined" class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Usuários com esta Role
                        </label>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                {{ role.users_count }}
                            </span>
                            <span class="text-gray-600 dark:text-gray-400">
                                {{ role.users_count === 1 ? 'usuário' : 'usuários' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { edit as rolesEdit, index as rolesIndex } from '@/routes/roles';
import { Link } from '@inertiajs/vue3';

interface Role {
    id: number;
    name: string;
    display_name: string;
    description?: string;
    created_at: string;
    updated_at: string;
    users_count?: number;
}

defineProps<{
    role: Role;
}>();

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>