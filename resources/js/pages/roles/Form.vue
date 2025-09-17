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
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <Label for="display_name">Nome do Perfil *</Label>
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
                        <ModuleList
                            :modules="modules"
                            @permission-toggle="handlePermissionToggle"
                        />
                    </div>

                    <div class="flex justify-end gap-3">
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
import ModuleList from '@/components/rbac/ModuleList.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
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

const form = useForm({
    display_name: props.role?.display_name || '',
    description: props.role?.description || '',
    permissions: props.role?.permissions?.map((p) => p.name) || ([] as string[]),
});


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
