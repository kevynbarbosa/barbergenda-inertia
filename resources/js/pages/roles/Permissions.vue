<template>
    <ContainerDefault>
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold">Permissões do Perfil</h1>
                <p class="text-muted-foreground" v-if="role">
                    Visualizando todas as permissões do perfil <strong>{{ role.display_name }}</strong>
                </p>
                <p class="text-xs text-muted-foreground" v-if="role.description">
                    {{ role.description }}
                </p>
            </div>
            <Button as-child variant="outline">
                <Link :href="rolesIndex.url()">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    Voltar
                </Link>
            </Button>
        </div>

        <!-- Main Content Card -->
        <Card>
            <CardContent class="p-6">
                <div v-if="role" class="space-y-6">
                    <!-- Informações do perfil -->
                    <div>
                        <h4 class="text-sm font-medium mb-3">Informações do Perfil</h4>
                        <div class="flex items-center gap-4 p-3 bg-muted/30 rounded-lg">
                            <div class="flex-1">
                                <span class="font-medium">{{ role.display_name }}</span>
                                <p v-if="role.description" class="text-sm text-muted-foreground">{{ role.description }}</p>
                            </div>
                            <Badge variant="secondary">
                                {{ permissions.length }} {{ permissions.length === 1 ? 'permissão' : 'permissões' }}
                            </Badge>
                        </div>
                    </div>

                    <!-- Permissões agrupadas por módulo -->
                    <div v-if="permissions.length > 0">
                        <h4 class="text-sm font-medium mb-3">Permissões por Módulo</h4>
                        <div class="space-y-4">
                            <div v-for="module in groupedPermissions" :key="module.name" class="border rounded-lg p-4">
                                <h5 class="font-medium mb-3 flex items-center gap-2">
                                    <Shield class="h-4 w-4" />
                                    {{ module.name }}
                                    <Badge variant="outline" class="text-xs">
                                        {{ module.permissions.length }} {{ module.permissions.length === 1 ? 'permissão' : 'permissões' }}
                                    </Badge>
                                </h5>
                                <div class="grid gap-2">
                                    <div v-for="permission in module.permissions" :key="permission.id"
                                         class="flex items-center justify-between p-2 bg-muted/30 rounded-md">
                                        <div>
                                            <span class="text-sm font-medium">{{ permission.display_name }}</span>
                                            <p class="text-xs text-muted-foreground">{{ permission.description }}</p>
                                        </div>
                                        <Badge variant="outline" class="text-xs">{{ permission.name }}</Badge>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Caso não tenha permissões -->
                    <div v-else class="text-center py-8 text-muted-foreground">
                        <Shield class="h-12 w-12 mx-auto mb-2 opacity-50" />
                        <p>Este perfil não possui permissões atribuídas.</p>
                        <p class="text-sm">
                            <Link :href="rolesEdit.url(role.id)" class="text-primary hover:underline">
                                Clique aqui para editar o perfil
                            </Link>
                            e atribuir permissões.
                        </p>
                    </div>
                </div>

            </CardContent>
        </Card>
    </ContainerDefault>
</template>

<script setup lang="ts">
import ContainerDefault from '@/components/layouts/ContainerDefault.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { index as rolesIndex, edit as rolesEdit } from '@/routes/roles';
import { Link } from '@inertiajs/vue3';
import { ArrowLeft, Shield } from 'lucide-vue-next';
import { computed } from 'vue';
import type { Role } from '@/types/role';

const props = defineProps<{
    role: Role;
    permissions: any[];
}>();

// Agrupar permissões por módulo
const groupedPermissions = computed(() => {
    const groups: Record<string, any[]> = {};

    props.permissions.forEach(permission => {
        const module = permission.module || 'Outros';
        if (!groups[module]) {
            groups[module] = [];
        }
        groups[module].push(permission);
    });

    return Object.entries(groups).map(([name, permissions]) => ({
        name: name.charAt(0).toUpperCase() + name.slice(1),
        permissions
    }));
});
</script>