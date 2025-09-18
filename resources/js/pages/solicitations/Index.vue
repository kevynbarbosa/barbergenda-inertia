<template>
    <ContainerDefault>
        <!-- Main Table Card -->
        <Card>
            <CardHeader>
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <CardTitle class="text-3xl">Lista de Solicitações</CardTitle>
                            <CardDescription>
                                Mostrando {{ solicitations.from || 0 }} a {{ solicitations.to || 0 }} de {{ solicitations.total || 0 }} solicitações
                            </CardDescription>
                        </div>
                        <Button as-child class="w-fit">
                            <Link :href="solicitationsCreate.url()">
                                <Plus class="mr-2 h-4 w-4" />
                                Nova Solicitação
                            </Link>
                        </Button>
                    </div>
                    <div class="flex justify-end">
                        <div class="w-full max-w-sm">
                            <div class="relative">
                                <Input
                                    v-model="searchTerm"
                                    @input="handleSearch"
                                    type="text"
                                    placeholder="Buscar por nome ou documento..."
                                    class="pr-10"
                                />
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
                                <TableHead>ID</TableHead>
                                <TableHead>Nome</TableHead>
                                <TableHead>Documento</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Etapa</TableHead>
                                <TableHead>Data de Criação</TableHead>
                                <TableHead class="text-right">Ações</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="solicitation in solicitations.data" :key="solicitation.id">
                                <TableCell class="font-medium">#{{ solicitation.id }}</TableCell>
                                <TableCell>{{ solicitation.name }}</TableCell>
                                <TableCell class="font-mono">{{ formatDocument(solicitation.document) }}</TableCell>
                                <TableCell>
                                    <Badge :variant="getStatusVariant(solicitation.status)">
                                        {{ getStatusLabel(solicitation.status) }}
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    <span v-if="solicitation.stage" class="text-sm text-muted-foreground">
                                        {{ solicitation.stage.name }}
                                    </span>
                                    <span v-else class="text-sm text-muted-foreground">-</span>
                                </TableCell>
                                <TableCell>{{ formatDate(solicitation.created_at) }}</TableCell>
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
                                                <Link :href="solicitationsShow.url(solicitation.id)" class="flex cursor-default items-center">
                                                    <Eye class="mr-2 h-4 w-4" />
                                                    Visualizar
                                                </Link>
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
                <!-- Paginação -->
                <DataTablePagination :data="solicitations" />
            </CardContent>
        </Card>
    </ContainerDefault>
</template>

<script setup lang="ts">
import ContainerDefault from '@/components/ContainerDefault.vue';
import DataTablePagination from '@/components/DataTablePagination.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { formatDate } from '@/lib/date-utils';
import { formatDocument } from '@/lib/document-utils';
import { create as solicitationsCreate, show as solicitationsShow } from '@/routes/solicitations';
import { Link, router } from '@inertiajs/vue3';
import { Eye, MoreHorizontal, Plus, Search, X } from 'lucide-vue-next';
import { ref } from 'vue';

interface PaginatedSolicitations {
    current_page: number;
    data: Array<{
        id: number;
        name: string;
        document: string;
        status: 'pending' | 'approved' | 'rejected' | 'in_review';
        stage?: {
            id: number;
            name: string;
            description: string;
            sla: number;
        };
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
    solicitations: PaginatedSolicitations;
    filters: {
        search: string | null;
    };
}>();

const searchTerm = ref(props.filters.search || '');


const getStatusVariant = (status: string) => {
    const variants = {
        pending: 'secondary',
        approved: 'verified',
        rejected: 'destructive',
        in_review: 'outline'
    };
    return variants[status as keyof typeof variants] || 'secondary';
};

const getStatusLabel = (status: string) => {
    const labels = {
        pending: 'Pendente',
        approved: 'Aprovado',
        rejected: 'Rejeitado',
        in_review: 'Em Análise'
    };
    return labels[status as keyof typeof labels] || status;
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
        '/solicitations',
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
        '/solicitations',
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};
</script>