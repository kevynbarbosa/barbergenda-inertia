<template>
    <Card>
        <CardHeader>
            <CardTitle>Análise da Solicitação</CardTitle>
            <CardDescription> Realize a análise dos documentos e informações da solicitação </CardDescription>
        </CardHeader>
        <CardContent>
            <Form :action="analysisAction.url()" method="post" class="space-y-6" v-slot="{ errors, processing, recentlySuccessful }">
                <!-- Hidden field for solicitation ID -->
                <input type="hidden" name="solicitation_id" :value="solicitation.id" />

                <!-- Action Selection -->
                <div class="space-y-3">
                    <Label class="text-base font-medium">Ação</Label>
                    <ToggleGroup type="single" name="action" class="justify-start border" default-value="">
                        <ToggleGroupItem
                            value="approve"
                            class="data-[state=on]:bg-green-100 data-[state=on]:text-green-700 dark:data-[state=on]:bg-green-900 dark:data-[state=on]:text-green-300"
                        >
                            <CheckCircle class="w-4 h-4 mr-2" />
                            Aprovar
                        </ToggleGroupItem>
                        <ToggleGroupItem
                            value="reject"
                            class="data-[state=on]:bg-red-100 data-[state=on]:text-red-700 dark:data-[state=on]:bg-red-900 dark:data-[state=on]:text-red-300"
                        >
                            <XCircle class="w-4 h-4 mr-2" />
                            Rejeitar
                        </ToggleGroupItem>
                        <ToggleGroupItem
                            value="return"
                            class="data-[state=on]:bg-yellow-100 data-[state=on]:text-yellow-700 dark:data-[state=on]:bg-yellow-900 dark:data-[state=on]:text-yellow-300"
                        >
                            <RotateCcw class="w-4 h-4 mr-2" />
                            Retornar
                        </ToggleGroupItem>
                    </ToggleGroup>
                    <InputError :message="errors.action" />
                </div>

                <!-- Observations -->
                <div class="space-y-2">
                    <Label for="observations">Observações</Label>
                    <Textarea
                        id="observations"
                        name="observations"
                        placeholder="Descreva os motivos da decisão ou observações sobre a análise..."
                        rows="4"
                        required
                    />
                    <InputError :message="errors.observations" />
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-4">
                    <Button type="submit" :disabled="processing"> {{ processing ? 'Processando...' : 'Salvar' }} Análise </Button>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-show="recentlySuccessful" class="text-sm text-muted-foreground">Análise salva com sucesso!</p>
                    </Transition>
                </div>
            </Form>
        </CardContent>
    </Card>
</template>

<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { ToggleGroup, ToggleGroupItem } from '@/components/ui/toggle-group';
import { Form } from '@inertiajs/vue3';
import { CheckCircle, XCircle, RotateCcw } from 'lucide-vue-next';

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

// TODO: Substituir por rota real do backend
const analysisAction = {
    url: () => `/solicitations/${props.solicitation.id}/analysis`,
};
</script>
