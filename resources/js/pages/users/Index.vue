<template>
    <div class="container mx-auto max-w-7xl p-4">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Usuários</h1>
                <p class="text-gray-600 dark:text-gray-400">Gerencie os usuários do sistema</p>
            </div>
            <Link
                :href="usersCreate.url()"
                class="rounded-lg bg-blue-600 px-4 py-2 font-medium text-white transition-colors hover:bg-blue-700"
            >
                + Novo Usuário
            </Link>
        </div>

        <div class="rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Lista de Usuários</h2>
            </div>
            <div class="p-6">
                <div class="grid gap-4">
                    <div
                        v-for="user in users"
                        :key="user.id"
                        class="rounded-lg border border-gray-200 p-4 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="mb-2 flex items-center gap-3">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ user.name }}
                                    </h3>
                                    <span v-if="user.email_verified_at" class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                        Verificado
                                    </span>
                                    <span v-else class="rounded-full bg-yellow-100 px-2 py-1 text-xs text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200">
                                        Não verificado
                                    </span>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400">
                                    {{ user.email }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Criado em {{ formatDate(user.created_at) }}
                                </p>
                            </div>
                            <div class="ml-4 flex gap-2">
                                <Link
                                    :href="usersShow.url(user.id)"
                                    class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
                                >
                                    Ver
                                </Link>
                                <Link
                                    :href="usersEdit.url(user.id)"
                                    class="text-sm font-medium text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300"
                                >
                                    Editar
                                </Link>
                                <button
                                    @click="deleteUser(user.id)"
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
import { create as usersCreate, destroy as usersDestroy, edit as usersEdit, show as usersShow } from '@/routes/users';
import { Link, router } from '@inertiajs/vue3';

interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

defineProps<{
    users: User[];
}>();

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};

const deleteUser = (userId: number) => {
    if (confirm('Tem certeza que deseja deletar este usuário?')) {
        router.delete(usersDestroy.url(userId));
    }
};
</script>