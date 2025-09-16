<template>
    <div class="container mx-auto max-w-7xl space-y-6 p-6">
        <!-- Main Table Card -->
        <Card>
            <CardHeader>
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <CardTitle class="text-3xl">Lista de Usuários</CardTitle>
                            <CardDescription>
                                Mostrando {{ users.from || 0 }} a {{ users.to || 0 }} de {{ users.total || 0 }} usuários
                            </CardDescription>
                        </div>
                        <Button as-child class="w-fit">
                            <Link :href="usersCreate.url()">
                                <Plus class="mr-2 h-4 w-4" />
                                Novo Usuário
                            </Link>
                        </Button>
                    </div>
                    <div class="flex justify-end">
                        <div class="w-full max-w-sm">
                            <div class="relative">
                                <Input v-model="searchTerm" @input="handleSearch" type="text" placeholder="Buscar por nome ou email..." class="pr-10" />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <Search v-if="!searchTerm" class="h-4 w-4 text-muted-foreground" />
                                    <Button v-else @click="clearSearch" variant="ghost" size="sm" class="h-6 w-6 p-0">
                                        <X class="h-3 w-3" />
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </CardHeader>
            <CardContent class="p-0">
                <div class="overflow-hidden">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Nome</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Roles</TableHead>
                                <TableHead>Data de Criação</TableHead>
                                <TableHead class="text-right">Ações</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="user in users.data" :key="user.id">
                                <TableCell class="font-medium">{{ user.name }}</TableCell>
                                <TableCell>{{ user.email }}</TableCell>
                                <TableCell>
                                    <Badge v-if="user.email_verified_at" variant="verified">Verificado</Badge>
                                    <Badge v-else variant="unverified">Não verificado</Badge>
                                </TableCell>
                                <TableCell>
                                    <div v-if="user.roles && user.roles.length > 0" class="flex flex-wrap gap-1">
                                        <Badge v-for="role in user.roles" :key="role.id" :variant="getRoleBadgeVariant(role.name)">
                                            {{ role.display_name }}
                                        </Badge>
                                    </div>
                                    <span v-else class="text-xs text-muted-foreground">-</span>
                                </TableCell>
                                <TableCell>{{ formatDate(user.created_at) }}</TableCell>
                                <TableCell class="text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="outline" size="icon">
                                                <MoreHorizontal class="h-4 w-4" />
                                                <span class="sr-only">Abrir menu</span>
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem as-child>
                                                <Link :href="usersEdit.url(user.id)" class="flex cursor-default items-center">
                                                    <Edit class="mr-2 h-4 w-4" />
                                                    Editar
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="viewUserPermissions(user)" class="flex cursor-default items-center">
                                                <Shield class="mr-2 h-4 w-4" />
                                                Ver Permissões
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem @click="deleteUser(user.id)" class="text-red-600 focus:text-red-600 dark:text-red-400">
                                                <Trash2 class="mr-2 h-4 w-4" />
                                                Deletar
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
                <!-- Paginação -->
                <DataTablePagination :data="users" />
            </CardContent>
        </Card>

        <!-- Modal de Permissões -->
        <Dialog v-model:open="showPermissionsModal">
            <DialogContent class="max-w-4xl max-h-[80vh] overflow-y-auto">
                <DialogHeader>
                    <DialogTitle>Permissões do Usuário</DialogTitle>
                    <DialogDescription v-if="selectedUser">
                        Visualizando todas as permissões de <strong>{{ selectedUser.name }}</strong> ({{ selectedUser.email }})
                    </DialogDescription>
                </DialogHeader>

                <div v-if="selectedUser" class="space-y-6">
                    <!-- Roles do usuário -->
                    <div v-if="selectedUser.roles && selectedUser.roles.length > 0">
                        <h4 class="text-sm font-medium mb-3">Roles Atribuídas</h4>
                        <div class="flex flex-wrap gap-2">
                            <Badge v-for="role in selectedUser.roles" :key="role.id" :variant="getRoleBadgeVariant(role.name)">
                                {{ role.display_name }}
                            </Badge>
                        </div>
                    </div>

                    <!-- Permissões agrupadas por módulo -->
                    <div v-if="userPermissions.length > 0">
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
            </DialogContent>
        </Dialog>
    </div>
</template>

<script setup lang="ts">
import DataTablePagination from '@/components/DataTablePagination.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { formatDate } from '@/lib/date-utils';
import { create as usersCreate, destroy as usersDestroy, edit as usersEdit } from '@/routes/users';
import { Link, router } from '@inertiajs/vue3';
import { Edit, MoreHorizontal, Plus, Search, Shield, Trash2, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import type { PaginatedUsers, User } from '@/types/user';

const props = defineProps<{
    users: PaginatedUsers;
    filters: {
        search: string | null;
    };
}>();

const searchTerm = ref(props.filters.search || '');

// Estado do modal de permissões
const showPermissionsModal = ref(false);
const selectedUser = ref<User | null>(null);
const userPermissions = ref<any[]>([]);

// Agrupar permissões por módulo
const groupedPermissions = computed(() => {
    const groups: Record<string, any[]> = {};

    userPermissions.value.forEach(permission => {
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

const getRoleBadgeVariant = (roleName: string): 'admin' | 'moderator' | 'user' | 'role' => {
    switch (roleName.toLowerCase()) {
        case 'admin':
        case 'administrator':
            return 'admin';
        case 'moderator':
        case 'mod':
            return 'moderator';
        case 'user':
        case 'member':
            return 'user';
        default:
            return 'role';
    }
};

const viewUserPermissions = async (user: User) => {
    selectedUser.value = user;
    userPermissions.value = [];
    showPermissionsModal.value = true;

    // Buscar permissões do usuário via API
    try {
        const response = await fetch(`/api/users/${user.id}/permissions`);
        if (response.ok) {
            const data = await response.json();
            userPermissions.value = data;
        }
    } catch (error) {
        console.error('Erro ao buscar permissões:', error);
    }
};

const deleteUser = (userId: number) => {
    if (confirm('Tem certeza que deseja deletar este usuário?')) {
        router.delete(usersDestroy.url(userId));
    }
};

// Função debounce simples
const debounce = (func: (...args: any[]) => void, wait: number) => {
    let timeout: ReturnType<typeof setTimeout>;
    return (...args: any[]) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
};

const performSearch = debounce(() => {
    router.get(
        '/users',
        {
            search: searchTerm.value || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
}, 300);

const handleSearch = () => {
    performSearch();
};

const clearSearch = () => {
    searchTerm.value = '';
    router.get(
        '/users',
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};
</script>
