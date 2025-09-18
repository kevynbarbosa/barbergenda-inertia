<template>
    <ContainerDefault>
        <!-- Main Table Card -->
        <Card>
            <CardHeader>
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <CardTitle class="text-3xl">Base Ativa</CardTitle>
                            <CardDescription>
                                Mostrando {{ people.from || 0 }} a {{ people.to || 0 }} de {{ people.total || 0 }} pessoas
                            </CardDescription>
                        </div>
                    </div>
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
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
                        <div class="flex gap-2">
                            <Button
                                @click="setStatusFilter('')"
                                :variant="statusFilter === '' ? 'default' : 'outline'"
                                size="sm"
                            >
                                Todos
                            </Button>
                            <Button
                                @click="setStatusFilter('ativo')"
                                :variant="statusFilter === 'ativo' ? 'default' : 'outline'"
                                size="sm"
                            >
                                Ativo
                            </Button>
                            <Button
                                @click="setStatusFilter('expirado')"
                                :variant="statusFilter === 'expirado' ? 'default' : 'outline'"
                                size="sm"
                            >
                                Expirado
                            </Button>
                            <Button
                                @click="setStatusFilter('blacklist')"
                                :variant="statusFilter === 'blacklist' ? 'default' : 'outline'"
                                size="sm"
                            >
                                Blacklist
                            </Button>
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
                                <TableHead>Data de Criação</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="person in people.data" :key="person.id">
                                <TableCell class="font-medium">#{{ person.id }}</TableCell>
                                <TableCell>{{ person.name }}</TableCell>
                                <TableCell class="font-mono">{{ formatDocument(person.document) }}</TableCell>
                                <TableCell>
                                    <Badge :variant="getStatusVariant(person.status)">
                                        {{ getStatusLabel(person.status) }}
                                    </Badge>
                                </TableCell>
                                <TableCell>{{ formatDate(person.created_at) }}</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
                <!-- Paginação -->
                <DataTablePagination :data="people" />
            </CardContent>
        </Card>
    </ContainerDefault>
</template>

<script setup lang="ts">
import ContainerDefault from '@/components/layouts/ContainerDefault.vue';
import DataTablePagination from '@/components/common/DataTablePagination.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { formatDate } from '@/lib/date-utils';
import { formatDocument } from '@/lib/document-utils';
import { router } from '@inertiajs/vue3';
import { Search, X } from 'lucide-vue-next';
import { ref } from 'vue';

interface PaginatedPeople {
    current_page: number;
    data: Array<{
        id: number;
        name: string;
        document: string;
        status: 'ativo' | 'expirado' | 'blacklist';
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
    people: PaginatedPeople;
    filters: {
        search: string | null;
        status: string | null;
    };
}>();

const searchTerm = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');

const getStatusVariant = (status: string) => {
    const variants = {
        ativo: 'verified',
        expirado: 'secondary',
        blacklist: 'destructive'
    };
    return variants[status as keyof typeof variants] || 'secondary';
};

const getStatusLabel = (status: string) => {
    const labels = {
        ativo: 'Ativo',
        expirado: 'Expirado',
        blacklist: 'Blacklist'
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
        '/base-ativa',
        {
            search: searchTerm.value || undefined,
            status: statusFilter.value || undefined,
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

const setStatusFilter = (status: string) => {
    statusFilter.value = status;
    performSearch();
};

const clearSearch = () => {
    searchTerm.value = '';
    router.get(
        '/base-ativa',
        {
            status: statusFilter.value || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};
</script>