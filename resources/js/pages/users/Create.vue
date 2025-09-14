<template>
    <div class="container mx-auto max-w-2xl p-4">
        <div class="mb-6">
            <h1 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Criar Usuário</h1>
            <p class="text-gray-600 dark:text-gray-400">Adicione um novo usuário ao sistema</p>
        </div>

        <div class="rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Informações do Usuário</h2>
            </div>
            <form @submit.prevent="submit" class="p-6">
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nome *
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :class="{ 'border-red-500': form.errors.name }"
                            required
                        />
                        <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            E-mail *
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :class="{ 'border-red-500': form.errors.email }"
                            required
                        />
                        <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Senha *
                        </label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :class="{ 'border-red-500': form.errors.password }"
                            required
                        />
                        <div v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Confirmar Senha *
                        </label>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            required
                        />
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <Link
                        :href="usersIndex.url()"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                    >
                        Cancelar
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Criando...' : 'Criar Usuário' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { index as usersIndex, store as usersStore } from '@/routes/users';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(usersStore.url());
};
</script>