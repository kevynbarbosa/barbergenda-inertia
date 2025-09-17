<template>
    <ContainerDefault>
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold">Permissões do Usuário</h1>
                <p class="text-muted-foreground" v-if="user">
                    Visualizando todas as permissões de <strong>{{ user.name }}</strong> ({{ user.email }})
                </p>
            </div>
            <Button as-child variant="outline">
                <Link :href="usersIndex.url()">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    Voltar
                </Link>
            </Button>
        </div>

        <!-- Main Content Card -->
        <Card>
            <CardContent class="p-6">
                <div v-if="user" class="space-y-6">
                    <!-- Roles do usuário -->
                    <div v-if="user.roles && user.roles.length > 0">
                        <h4 class="text-sm font-medium mb-3">Roles Atribuídas</h4>
                        <div class="flex flex-wrap gap-2">
                            <Badge v-for="role in user.roles" :key="role.id" variant="outline">
                                {{ role.display_name }}
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
                        <p>Este usuário não possui permissões diretas.</p>
                        <p class="text-sm">As permissões podem ser herdadas através das roles.</p>
                    </div>
                </div>

            </CardContent>
        </Card>
    </ContainerDefault>
</template>

<script setup lang="ts">
import ContainerDefault from '@/components/ContainerDefault.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { index as usersIndex } from '@/routes/users';
import { Link } from '@inertiajs/vue3';
import { ArrowLeft, Shield } from 'lucide-vue-next';
import { computed } from 'vue';
import type { User } from '@/types/user';

const props = defineProps<{
    user: User;
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