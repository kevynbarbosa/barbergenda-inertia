<template>
    <ContainerDefault class="p-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold">Controle de Acesso</h1>
            <p class="mt-2 text-muted-foreground">Gerencie permissões por módulo do sistema</p>
        </div>

        <!-- Layout Mobile -->
        <div v-if="isMobile" class="lg:hidden">
            <p class="text-muted-foreground">Layout mobile em desenvolvimento</p>
        </div>

        <!-- Layout Desktop -->
        <div v-else class="hidden h-[80vh] gap-6 lg:grid lg:grid-cols-5">
            <div class="col-span-2">
                <ModuleList
                    :modules="modules"
                    :selected-module="selectedModule"
                    :on-module-select="handleModuleSelect"
                    :get-enabled-count="getEnabledCount"
                />
            </div>
            <div class="col-span-3">
                <PermissionsList :module="selectedModule" :on-permission-toggle="handlePermissionToggle" />
            </div>
        </div>
    </ContainerDefault>
</template>

<script setup lang="ts">
import ContainerDefault from '@/components/layouts/ContainerDefault.vue';
import { modules as moduleData } from '@/components/rbac/data';
import ModuleList from '@/components/rbac/ModuleList.vue';
import PermissionsList from '@/components/rbac/PermissionsList.vue';
import type { Module } from '@/components/rbac/types';
import { useMobile } from '@/composables/useMobile';
import { reactive, ref } from 'vue';

const modules = reactive(moduleData);
const selectedModule = ref<Module>(modules[0]);

const { isMobile } = useMobile();

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
</script>
