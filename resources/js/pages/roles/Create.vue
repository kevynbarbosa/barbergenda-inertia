<template>
    <div class="container mx-auto max-w-7xl p-4">
        <div class="mb-6">
            <h1 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Criar Nova Role</h1>
            <p class="text-gray-600 dark:text-gray-400">Configure as informações básicas e permissões da role</p>
        </div>

        <form @submit.prevent="submit" class="space-y-8">
            <!-- Informações Básicas -->
            <div class="rounded-lg bg-white shadow dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Informações Básicas</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nome (identificador) *
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :class="{ 'border-red-500': form.errors.name }"
                                placeholder="ex: admin, manager, user"
                                required
                            />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Use apenas letras minúsculas, números e underscores
                            </p>
                            <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <div>
                            <label for="display_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nome de Exibição *
                            </label>
                            <input
                                id="display_name"
                                v-model="form.display_name"
                                type="text"
                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :class="{ 'border-red-500': form.errors.display_name }"
                                placeholder="ex: Administrador, Gerente, Usuário"
                                required
                            />
                            <div v-if="form.errors.display_name" class="mt-1 text-sm text-red-600">
                                {{ form.errors.display_name }}
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Descrição
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="3"
                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :class="{ 'border-red-500': form.errors.description }"
                                placeholder="Descreva as responsabilidades e permissões desta role"
                            />
                            <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                                {{ form.errors.description }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissões por Módulo -->
            <div class="rounded-lg bg-white shadow dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Permissões por Módulo</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Configure as permissões específicas para cada módulo do sistema
                    </p>
                </div>

                <!-- Layout Mobile -->
                <div v-if="isMobile" class="lg:hidden">
                    <MobileTabs
                        v-if="selectedModule"
                        :modules="modules"
                        :selected-module="selectedModule"
                        :on-module-select="handleModuleSelect"
                        :on-permission-toggle="handlePermissionToggle"
                        :get-enabled-count="getEnabledCount"
                    />
                </div>

                <!-- Layout Desktop -->
                <div v-else class="hidden h-[60vh] gap-6 p-6 lg:grid lg:grid-cols-5">
                    <div class="col-span-2">
                        <ModuleList
                            :modules="modules"
                            :selected-module="selectedModule"
                            :on-module-select="handleModuleSelect"
                            :get-enabled-count="getEnabledCount"
                        />
                    </div>
                    <div class="col-span-3">
                        <PermissionsList
                            :module="selectedModule"
                            :on-permission-toggle="handlePermissionToggle"
                        />
                    </div>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="flex justify-end gap-3 rounded-lg bg-white p-6 shadow dark:bg-gray-800">
                <Link
                    :href="rolesIndex.url()"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Cancelar
                </Link>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50"
                >
                    {{ form.processing ? 'Criando...' : 'Criar Role' }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { modules as moduleData } from '@/components/rbac/data';
import MobileTabs from '@/components/rbac/MobileTabs.vue';
import ModuleList from '@/components/rbac/ModuleList.vue';
import PermissionsList from '@/components/rbac/PermissionsList.vue';
import type { Module } from '@/components/rbac/types';
import { useMobile } from '@/composables/useMobile';
import { index as rolesIndex, store as rolesStore } from '@/routes/roles';
import { Link, useForm } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';

const modules = reactive(JSON.parse(JSON.stringify(moduleData))); // Deep clone to avoid mutating original
const selectedModule = ref<Module>(modules[0]);

const { isMobile } = useMobile();

const form = useForm({
    name: '',
    display_name: '',
    description: '',
    permissions: [] as string[],
});

const getEnabledCount = (module: Module): number => {
    return module.permissions.filter((permission) => permission.enabled).length;
};

const handleModuleSelect = (moduleOrId: Module | string) => {
    if (typeof moduleOrId === 'string') {
        const module = modules.find((m) => m.id === moduleOrId);
        if (module) {
            selectedModule.value = module;
        }
    } else {
        selectedModule.value = moduleOrId;
    }
};

const handlePermissionToggle = (permissionId: string) => {
    const module = modules.find((m) => m.permissions.some((p) => p.id === permissionId));

    if (module) {
        const permission = module.permissions.find((p) => p.id === permissionId);
        if (permission) {
            permission.enabled = !permission.enabled;
        }
    }
};

const submit = () => {
    // Coletar todas as permissões habilitadas
    const enabledPermissions: string[] = [];
    modules.forEach(module => {
        module.permissions.forEach(permission => {
            if (permission.enabled) {
                enabledPermissions.push(permission.id);
            }
        });
    });

    form.permissions = enabledPermissions;
    form.post(rolesStore.url());
};
</script>