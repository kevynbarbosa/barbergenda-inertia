<template>
    <div class="overflow-hidden">
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead class="w-12">SLA</TableHead>
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
                <TableRow v-for="solicitation in solicitations" :key="solicitation.id">
                    <TableCell class="text-center">
                        <TooltipProvider>
                            <Tooltip>
                                <TooltipTrigger as-child>
                                    <div class="flex justify-center">
                                        <div
                                            class="h-3 w-3 rounded-full"
                                            :class="{
                                                'bg-green-500': solicitation.sla_status === 'on_time',
                                                'bg-orange-500': solicitation.sla_status === 'overdue',
                                                'bg-gray-400': solicitation.sla_status === 'unknown',
                                            }"
                                        ></div>
                                    </div>
                                </TooltipTrigger>
                                <TooltipContent class="bg-background text-foreground border border-border shadow-md">
                                    <p class="text-sm">
                                        <span v-if="solicitation.sla_status === 'on_time'"> Em dia </span>
                                        <span v-else-if="solicitation.sla_status === 'overdue'"> Atrasado </span>
                                        <span v-else> SLA não definido </span>
                                    </p>
                                    <p v-if="solicitation.estimated_completion_at" class="mt-1 text-xs text-muted-foreground">
                                        Previsão: {{ formatDate(solicitation.estimated_completion_at) }}
                                    </p>
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                    </TableCell>
                    <TableCell class="font-medium">#{{ solicitation.id }}</TableCell>
                    <TableCell>{{ solicitation.person?.name || '-' }}</TableCell>
                    <TableCell class="font-mono">{{
                        solicitation.person?.document ? formatDocument(solicitation.person.document) : '-'
                    }}</TableCell>
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
</template>

<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { formatDate } from '@/lib/date-utils';
import { formatDocument } from '@/lib/document-utils';
import { show as solicitationsShow } from '@/routes/solicitations';
import { Link } from '@inertiajs/vue3';
import { Eye, MoreHorizontal } from 'lucide-vue-next';
import type { Solicitation } from '@/types/solicitations';

defineProps<{
    solicitations: Solicitation[];
}>();

const getStatusVariant = (status: string) => {
    const variants = {
        pending: 'secondary',
        approved: 'verified',
        rejected: 'destructive',
        in_review: 'outline',
    };
    return variants[status as keyof typeof variants] || 'secondary';
};

const getStatusLabel = (status: string) => {
    const labels = {
        pending: 'Pendente',
        approved: 'Aprovado',
        rejected: 'Rejeitado',
        in_review: 'Em Análise',
    };
    return labels[status as keyof typeof labels] || status;
};
</script>