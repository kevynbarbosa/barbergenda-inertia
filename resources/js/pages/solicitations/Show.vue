<template>
    <ContainerDefault>
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold">Detalhes da Solicitação #{{ solicitation.id }}</h1>
                <p class="text-muted-foreground">
                    Visualize todas as informações da solicitação
                </p>
            </div>
            <Button as-child variant="outline">
                <Link :href="solicitationsIndex.url()">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    Voltar
                </Link>
            </Button>
        </div>

        <!-- Details Card -->
        <Card>
            <CardHeader>
                <div class="flex items-center justify-between">
                    <div>
                        <CardTitle>Informações da Solicitação</CardTitle>
                        <CardDescription>
                            Dados completos da solicitação registrada no sistema
                        </CardDescription>
                    </div>
                    <Badge :variant="getStatusVariant(solicitation.status)" class="text-sm">
                        {{ getStatusLabel(solicitation.status) }}
                    </Badge>
                </div>
            </CardHeader>
            <CardContent class="space-y-6">
                <!-- Informações Básicas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <Label class="text-sm font-medium text-muted-foreground">ID da Solicitação</Label>
                        <p class="text-lg font-mono">#{{ solicitation.id }}</p>
                    </div>

                    <div class="space-y-2">
                        <Label class="text-sm font-medium text-muted-foreground">Status</Label>
                        <div>
                            <Badge :variant="getStatusVariant(solicitation.status)">
                                {{ getStatusLabel(solicitation.status) }}
                            </Badge>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label class="text-sm font-medium text-muted-foreground">Nome/Razão Social</Label>
                        <p class="text-lg">{{ solicitation.person?.name || '-' }}</p>
                    </div>

                    <div class="space-y-2" v-if="solicitation.person?.document">
                        <Label class="text-sm font-medium text-muted-foreground">{{ getDocumentLabel(solicitation.person.document) }}</Label>
                        <p class="text-lg font-mono">{{ formatDocument(solicitation.person.document) }}</p>
                    </div>

                    <div class="space-y-2">
                        <Label class="text-sm font-medium text-muted-foreground">Data de Criação</Label>
                        <p class="text-lg">{{ formatDate(solicitation.created_at) }}</p>
                    </div>

                    <div class="space-y-2">
                        <Label class="text-sm font-medium text-muted-foreground">Última Atualização</Label>
                        <p class="text-lg">{{ formatDate(solicitation.updated_at) }}</p>
                    </div>

                    <div v-if="solicitation.stage" class="space-y-2">
                        <Label class="text-sm font-medium text-muted-foreground">Etapa Atual</Label>
                        <div class="space-y-1">
                            <p class="text-lg font-medium">{{ solicitation.stage.name }}</p>
                            <p class="text-sm text-muted-foreground">{{ solicitation.stage.description }}</p>
                            <p class="text-xs text-muted-foreground">
                                SLA: {{ solicitation.stage.sla }}{{ solicitation.stage.sla > 0 ? ' horas' : '' }}
                            </p>
                        </div>
                    </div>
                </div>

                <Separator />

                <!-- Histórico/Timeline (placeholder para futuras implementações) -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <Clock class="h-5 w-5 text-muted-foreground" />
                        <Label class="text-sm font-medium text-muted-foreground">Histórico da Solicitação</Label>
                    </div>
                    <div class="ml-7 space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-muted/30 rounded-lg">
                            <div class="h-2 w-2 bg-primary rounded-full"></div>
                            <div class="flex-1">
                                <p class="text-sm">
                                    <span class="font-medium">Solicitação criada</span>
                                </p>
                                <p class="text-xs text-muted-foreground">
                                    {{ formatDate(solicitation.created_at) }}
                                </p>
                            </div>
                        </div>

                        <div v-if="solicitation.status !== 'pending'" class="flex items-center gap-3 p-3 bg-muted/30 rounded-lg">
                            <div class="h-2 w-2 bg-muted-foreground rounded-full"></div>
                            <div class="flex-1">
                                <p class="text-sm">
                                    <span class="font-medium">Status alterado para {{ getStatusLabel(solicitation.status) }}</span>
                                </p>
                                <p class="text-xs text-muted-foreground">
                                    {{ formatDate(solicitation.updated_at) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <Separator />

                <!-- Stage Actions Section -->
                <SolicitationStageActions :solicitation="solicitation" />
            </CardContent>
        </Card>
    </ContainerDefault>
</template>

<script setup lang="ts">
import ContainerDefault from '@/components/ContainerDefault.vue';
import SolicitationStageActions from '@/components/solicitations/SolicitationStageActions.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { formatDate } from '@/lib/date-utils';
import { formatDocument, getDocumentLabel } from '@/lib/document-utils';
import { index as solicitationsIndex } from '@/routes/solicitations';
import { Link } from '@inertiajs/vue3';
import { ArrowLeft, Clock } from 'lucide-vue-next';

interface Solicitation {
    id: number;
    status: 'pending' | 'approved' | 'rejected' | 'in_review';
    person?: {
        id: number;
        name: string;
        document: string;
    };
    stage?: {
        id: number;
        name: string;
        description: string;
        sla: number;
    };
    created_at: string;
    updated_at: string;
}

const { solicitation } = defineProps<{
    solicitation: Solicitation;
}>();

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
</script>