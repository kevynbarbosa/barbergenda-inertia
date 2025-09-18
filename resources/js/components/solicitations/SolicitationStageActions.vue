<template>
    <div v-if="shouldShowActions" class="space-y-4">
        <div class="flex items-center gap-2">
            <Settings class="h-5 w-5 text-muted-foreground" />
            <Label class="text-sm font-medium text-muted-foreground"> Ações para Etapa Atual </Label>
        </div>

        <!-- Renderiza o formulário baseado na etapa atual -->
        <component :is="currentStageComponent" :solicitation="solicitation" />
    </div>

    <!-- Mensagem quando não há ações disponíveis -->
    <div v-else-if="solicitation.stage" class="py-8 text-center">
        <div class="flex flex-col items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-muted">
                <CheckCircle class="h-6 w-6 text-primary" />
            </div>
            <div>
                <p class="text-sm font-medium text-muted-foreground">Solicitação finalizada</p>
                <p class="text-xs text-muted-foreground">Não há ações disponíveis para esta etapa</p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { CheckCircle, Settings } from 'lucide-vue-next';
import { computed } from 'vue';
import AnalysisStageForm from './stages/AnalysisStageForm.vue';
import ApprovalStageForm from './stages/ApprovalStageForm.vue';
import ReviewStageForm from './stages/ReviewStageForm.vue';

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

const props = defineProps<{
    solicitation: Solicitation;
}>();

// Mapeamento de etapas para componentes
const stageComponentMap = {
    'Análise Inicial': AnalysisStageForm,
    Análise: AnalysisStageForm,
    Revisão: ReviewStageForm,
    'Revisão Técnica': ReviewStageForm,
    Aprovação: ApprovalStageForm,
    'Aprovação Final': ApprovalStageForm,
} as const;

// Determina qual componente renderizar baseado na etapa atual
const currentStageComponent = computed(() => {
    if (!props.solicitation.stage?.name) {
        return null;
    }

    const stageName = props.solicitation.stage.name;
    return stageComponentMap[stageName as keyof typeof stageComponentMap] || null;
});

// Verifica se deve mostrar ações (não está finalizada)
const shouldShowActions = computed(() => {
    // Se não tem etapa, não mostra ações
    if (!props.solicitation.stage) {
        return false;
    }

    // Se status é finalizado (aprovado ou rejeitado), não mostra ações
    if (props.solicitation.status === 'approved' || props.solicitation.status === 'rejected') {
        return false;
    }

    // Se tem componente para a etapa atual, mostra ações
    return !!currentStageComponent.value;
});
</script>
