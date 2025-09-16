<template>
    <div class="container mx-auto max-w-4xl p-6">
        <Card>
            <CardHeader>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <CardTitle class="text-3xl">
                            {{ isEditing ? 'Editar Role' : 'Criar Role' }}
                        </CardTitle>
                        <CardDescription>
                            {{ isEditing ? 'Atualize as informações da role' : 'Configure as informações básicas e permissões da role' }}
                        </CardDescription>
                    </div>
                </div>
            </CardHeader>
            <CardContent>
                <form @submit.prevent="submit" class="space-y-8">
                    <!-- Informações Básicas -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium">Informações Básicas</h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <Label for="name">Nome Técnico *</Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    placeholder="ex: admin, manager, user"
                                    :class="{ 'border-destructive': form.errors.name }"
                                />
                                <p class="mt-1 text-sm text-muted-foreground">Use apenas letras minúsculas, números e underscores</p>
                                <div v-if="form.errors.name" class="mt-1 text-sm text-destructive">
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <div>
                                <Label for="display_name">Nome de Exibição *</Label>
                                <Input
                                    id="display_name"
                                    v-model="form.display_name"
                                    type="text"
                                    placeholder="ex: Administrador, Gerente, Usuário"
                                    :class="{ 'border-destructive': form.errors.display_name }"
                                />
                                <div v-if="form.errors.display_name" class="mt-1 text-sm text-destructive">
                                    {{ form.errors.display_name }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <Label for="description">Descrição</Label>
                            <Textarea
                                id="description"
                                v-model="form.description"
                                placeholder="Descreva as responsabilidades e permissões desta role"
                                :class="{ 'border-destructive': form.errors.description }"
                            />
                            <div v-if="form.errors.description" class="mt-1 text-sm text-destructive">
                                {{ form.errors.description }}
                            </div>
                        </div>
                    </div>

                    <!-- Permissões por Módulo -->
                    <div>
                        <!-- Layout Mobile -->
                        <div v-if="isMobile" class="lg:hidden">
                            <MobileTabs
                                v-if="selectedModule"
                                :modules="modules"
                                :selected-module="selectedModule"
                                :get-enabled-count="getEnabledCount"
                                @module-select="handleModuleSelect"
                                @permission-toggle="handlePermissionToggle"
                            />
                        </div>

                        <!-- Layout Desktop -->
                        <div v-else class="hidden gap-6 p-6 lg:grid lg:grid-cols-5">
                            <div class="col-span-2">
                                <ModuleList
                                    :modules="modules"
                                    :selected-module="selectedModule"
                                    :get-enabled-count="getEnabledCount"
                                    @module-select="handleModuleSelect"
                                />
                            </div>
                            <div class="col-span-3">
                                <PermissionsList :module="selectedModule" @permission-toggle="handlePermissionToggle" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6">
                        <Button variant="outline" as-child>
                            <Link :href="rolesIndex.url()">Cancelar</Link>
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ form.processing ? (isEditing ? 'Salvando...' : 'Criando...') : isEditing ? 'Salvar Alterações' : 'Criar Role' }}
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>

<script setup lang="ts">
import MobileTabs from '@/components/rbac/MobileTabs.vue';
import ModuleList from '@/components/rbac/ModuleList.vue';
import PermissionsList from '@/components/rbac/PermissionsList.vue';
import type { Module } from '@/components/rbac/types';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { useMobile } from '@/composables/useMobile';
import { index as rolesIndex, store as rolesStore, update as rolesUpdate } from '@/routes/roles';
import type { Permission } from '@/types/permission';
import type { Role } from '@/types/role';
import { Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps<{
    role?: Role;
    permissions?: Permission[];
}>();

const isEditing = computed(() => !!props.role);
const { isMobile } = useMobile();

// Converter permissões para o formato esperado pelos componentes RBAC
const createModules = () => {
    if (!props.permissions) return [];

    const permissionsByModule = props.permissions.reduce(
        (acc, permission) => {
            const module = permission.module || 'outros';
            if (!acc[module]) {
                acc[module] = [];
            }
            acc[module].push(permission);
            return acc;
        },
        {} as Record<string, Permission[]>,
    );

    return Object.entries(permissionsByModule).map(([moduleName, permissions]) => ({
        id: moduleName,
        name: moduleName.charAt(0).toUpperCase() + moduleName.slice(1),
        description: `Permissões do módulo ${moduleName}`,
        permissions: permissions.map((permission) => ({
            id: permission.name,
            name: permission.name,
            display_name: permission.display_name,
            module: permission.module,
            description: permission.description || '',
            enabled: props.role?.permissions?.some((p) => p.name === permission.name) || false,
            created_at: permission.created_at,
            updated_at: permission.updated_at,
        })),
    }));
};

const modules = ref(createModules());

const selectedModule = ref<Module | null>(modules.value[0] || null);

const form = useForm({
    name: props.role?.name || '',
    display_name: props.role?.display_name || '',
    description: props.role?.description || '',
    permissions: props.role?.permissions?.map((p) => p.name) || ([] as string[]),
});

const getEnabledCount = (module: Module): number => {
    return module.permissions.filter((permission) => permission.enabled).length;
};

const handleModuleSelect = (moduleOrId: Module | string) => {
    if (typeof moduleOrId === 'string') {
        const module = modules.value.find((m) => m.id === moduleOrId);
        if (module) {
            selectedModule.value = module;
        }
    } else {
        selectedModule.value = moduleOrId;
    }
};

const handlePermissionToggle = (permissionId: string) => {
    // Encontrar e atualizar a permissão no módulo
    const module = modules.value.find((m) => m.permissions.some((p) => p.id === permissionId));
    if (module) {
        const permission = module.permissions.find((p) => p.id === permissionId);
        if (permission) {
            permission.enabled = !permission.enabled;

            // Atualizar o form
            if (permission.enabled) {
                if (!form.permissions.includes(permissionId)) {
                    form.permissions.push(permissionId);
                }
            } else {
                const index = form.permissions.indexOf(permissionId);
                if (index > -1) {
                    form.permissions.splice(index, 1);
                }
            }
        }
    }
};

const submit = () => {
    // Coletar todas as permissões habilitadas
    const enabledPermissions: string[] = [];
    modules.value.forEach((module) => {
        module.permissions.forEach((permission) => {
            if (permission.enabled) {
                enabledPermissions.push(permission.id);
            }
        });
    });

    form.permissions = enabledPermissions;

    if (isEditing.value) {
        form.put(rolesUpdate.url(props.role!.id));
    } else {
        form.post(rolesStore.url());
    }
};
</script>
