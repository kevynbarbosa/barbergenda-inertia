<template>
    <div class="container mx-auto max-w-2xl p-4">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Detalhes do Usuário</h1>
                <p class="text-gray-600 dark:text-gray-400">Informações completas do usuário</p>
            </div>
            <div class="flex gap-2">
                <Link
                    :href="usersEdit.url(user.id)"
                    class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700"
                >
                    Editar
                </Link>
                <Link
                    :href="usersIndex.url()"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Voltar
                </Link>
            </div>
        </div>

        <div class="rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Informações Pessoais</h2>
            </div>
            <div class="p-6">
                <div class="grid gap-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 text-2xl font-bold text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ user.name.charAt(0).toUpperCase() }}
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ user.name }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ user.email }}
                            </p>
                        </div>
                        <div class="ml-auto">
                            <span
                                v-if="user.email_verified_at"
                                class="rounded-full bg-green-100 px-3 py-1 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-200"
                            >
                                E-mail Verificado
                            </span>
                            <span
                                v-else
                                class="rounded-full bg-yellow-100 px-3 py-1 text-sm text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200"
                            >
                                E-mail Não Verificado
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Data de Criação
                            </label>
                            <p class="mt-1 text-gray-900 dark:text-white">
                                {{ formatDate(user.created_at) }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Última Atualização
                            </label>
                            <p class="mt-1 text-gray-900 dark:text-white">
                                {{ formatDate(user.updated_at) }}
                            </p>
                        </div>
                        <div v-if="user.email_verified_at">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                E-mail Verificado em
                            </label>
                            <p class="mt-1 text-gray-900 dark:text-white">
                                {{ formatDate(user.email_verified_at) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { edit as usersEdit, index as usersIndex } from '@/routes/users';
import { Link } from '@inertiajs/vue3';

interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

defineProps<{
    user: User;
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