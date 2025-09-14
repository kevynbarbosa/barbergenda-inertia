<template>
    <div class="container mx-auto max-w-7xl p-4">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="mb-2 text-3xl font-bold">Usuários</h1>
                <p>Gerencie os usuários do sistema</p>
            </div>
            <Button as-child>
                <Link :href="usersCreate.url()"> + Novo Usuário </Link>
            </Button>
        </div>

        <div class="rounded-lg shadow">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-semibol text-xl">Lista de Usuários</h2>
                        <p class="mt-1 text-sm">Mostrando {{ users.from }} a {{ users.to }} de {{ users.total }} usuários</p>
                    </div>
                    <div class="w-full max-w-sm">
                        <div class="relative">
                            <Input v-model="searchTerm" @input="handleSearch" type="text" placeholder="Buscar por nome ou email..." />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <Search v-if="!searchTerm" class="h-4 w-4 text-gray-400" />
                                <Button v-else @click="clearSearch" variant="ghost" size="icon" class="h-4 w-4">
                                    <X class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                <span
                                    v-if="user.email_verified_at"
                                    class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs text-green-800 dark:bg-green-900/30 dark:text-green-200"
                                >
                                    Verificado
                                </span>
                                <span
                                    v-else
                                    class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200"
                                >
                                    Não verificado
                                </span>
                            </TableCell>
                            <TableCell>
                                <div v-if="user.roles && user.roles.length > 0" class="flex flex-wrap gap-1">
                                    <span
                                        v-for="role in user.roles"
                                        :key="role.id"
                                        class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-200"
                                    >
                                        {{ role.display_name }}
                                    </span>
                                </div>
                                <span v-else class="text-xs text-gray-400 dark:text-gray-500">-</span>
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
        </div>
    </div>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import DataTablePagination from '@/components/DataTablePagination.vue';
import { create as usersCreate, destroy as usersDestroy, edit as usersEdit, show as usersShow } from '@/routes/users';
import { Link, router } from '@inertiajs/vue3';
import { Edit, Eye, MoreHorizontal, Search, Trash2, X } from 'lucide-vue-next';
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

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
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
