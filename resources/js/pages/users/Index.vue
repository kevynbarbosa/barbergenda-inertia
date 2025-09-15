<template>
    <div class="container mx-auto max-w-7xl space-y-6 p-6">
        <!-- Header Card -->
        <Card>
            <CardHeader>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <CardTitle class="text-2xl">Usuários</CardTitle>
                        <CardDescription>Gerencie os usuários do sistema</CardDescription>
                    </div>
                    <Button as-child class="w-fit">
                        <Link :href="usersCreate.url()">
                            <Plus class="mr-2 h-4 w-4" />
                            Novo Usuário
                        </Link>
                    </Button>
                </div>
            </CardHeader>
        </Card>

        <!-- Main Table Card -->
        <Card>
            <CardHeader>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <CardTitle>Lista de Usuários</CardTitle>
                        <CardDescription>
                            Mostrando {{ users.from || 0 }} a {{ users.to || 0 }} de {{ users.total || 0 }} usuários
                        </CardDescription>
                    </div>
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
                                                <Link :href="usersShow.url(user.id)" class="flex cursor-default items-center">
                                                    <Eye class="mr-2 h-4 w-4" />
                                                    Ver
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem as-child>
                                                <Link :href="usersEdit.url(user.id)" class="flex cursor-default items-center">
                                                    <Edit class="mr-2 h-4 w-4" />
                                                    Editar
                                                </Link>
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
    </div>
</template>

<script setup lang="ts">
import DataTablePagination from '@/components/DataTablePagination.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { formatDate } from '@/lib/date-utils';
import { create as usersCreate, destroy as usersDestroy, edit as usersEdit, show as usersShow } from '@/routes/users';
import { Link, router } from '@inertiajs/vue3';
import { Edit, Eye, MoreHorizontal, Plus, Search, Trash2, X } from 'lucide-vue-next';
import { ref } from 'vue';

interface Role {
    id: number;
    name: string;
    display_name: string;
    description?: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    roles: Role[];
}

interface PaginatedUsers {
    current_page: number;
    data: User[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

const props = defineProps<{
    users: PaginatedUsers;
    filters: {
        search: string | null;
    };
}>();

const searchTerm = ref(props.filters.search || '');

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
