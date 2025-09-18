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
                <SolicitationsTable :solicitations="solicitations.data" />
                <!-- Paginação -->
                <DataTablePagination :data="solicitations" />
            </CardContent>
        </Card>
    </ContainerDefault>
</template>

<script setup lang="ts">
import ContainerDefault from '@/components/ContainerDefault.vue';
import DataTablePagination from '@/components/DataTablePagination.vue';
import SolicitationsTable from '@/components/SolicitationsTable.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { create as solicitationsCreate } from '@/routes/solicitations';
import { Link, router } from '@inertiajs/vue3';
import { Plus, Search, X } from 'lucide-vue-next';
import { ref } from 'vue';
import type { PaginatedSolicitations, SolicitationsFilters } from '@/types/solicitations';

const props = defineProps<{
    solicitations: PaginatedSolicitations;
    filters: SolicitationsFilters;
}>();

const searchTerm = ref(props.filters.search || '');

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
