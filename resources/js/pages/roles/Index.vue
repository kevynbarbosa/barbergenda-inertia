<template>
    <ContainerDefault>
        <!-- Main Table Card -->
        <Card>
            <CardHeader>
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <CardTitle class="text-3xl">Lista de Perfis</CardTitle>
                            <CardDescription>
                                Mostrando {{ roles.from || 0 }} a {{ roles.to || 0 }} de {{ roles.total || 0 }} perfis
                            </CardDescription>
                        </div>
                        <Button as-child class="w-fit">
                            <Link :href="rolesCreate.url()">
                                <Plus class="mr-2 h-4 w-4" />
                                Nova Role
                            </Link>
                        </Button>
                    </div>
                    <div class="flex justify-end">
                        <div class="w-full max-w-sm">
                            <div class="relative">
                                <Input v-model="searchTerm" @input="handleSearch" type="text" placeholder="Buscar por nome..." class="pr-10" />
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
                                <TableHead>Nome Técnico</TableHead>
                                <TableHead>Descrição</TableHead>
                                <TableHead>Usuários</TableHead>
                                <TableHead>Data de Criação</TableHead>
                                <TableHead class="text-right">Ações</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="role in roles.data" :key="role.id">
                                <TableCell class="font-medium">{{ role.display_name || role.name }}</TableCell>
                                <TableCell>
                                    <code class="rounded bg-muted px-1.5 py-0.5 text-sm">{{ role.name }}</code>
                                </TableCell>
                                <TableCell>
                                    <span v-if="role.description" class="text-sm">{{ role.description }}</span>
                                    <span v-else class="text-xs text-muted-foreground">-</span>
                                </TableCell>
                                <TableCell>
                                    <Badge variant="secondary">{{ role.users_count || 0 }} usuários</Badge>
                                </TableCell>
                                <TableCell>{{ formatDate(role.created_at) }}</TableCell>
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
                                                <Link :href="rolesEdit.url(role.id)" class="flex cursor-default items-center">
                                                    <Edit class="mr-2 h-4 w-4" />
                                                    Editar
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem @click="deleteRole(role.id)" class="text-red-600 focus:text-red-600 dark:text-red-400">
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
                <DataTablePagination :data="roles" />
            </CardContent>
        </Card>
    </ContainerDefault>
</template>

<script setup lang="ts">
import ContainerDefault from '@/components/ContainerDefault.vue';
import DataTablePagination from '@/components/DataTablePagination.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { formatDate } from '@/lib/date-utils';
import { create as rolesCreate, destroy as rolesDestroy, edit as rolesEdit } from '@/routes/roles';
import { Link, router } from '@inertiajs/vue3';
import { Edit, MoreHorizontal, Plus, Search, Trash2, X } from 'lucide-vue-next';
import { ref } from 'vue';

interface PaginatedRoles {
    current_page: number;
    data: Array<{
        id: number;
        name: string;
        display_name: string;
        description?: string;
        users_count?: number;
        created_at: string;
        updated_at: string;
    }>;
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
    roles: PaginatedRoles;
    filters: {
        search: string | null;
    };
}>();

const searchTerm = ref(props.filters.search || '');

const deleteRole = (roleId: number) => {
    if (confirm('Tem certeza que deseja deletar esta role?')) {
        router.delete(rolesDestroy.url(roleId));
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
        '/roles',
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
        '/roles',
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};
</script>