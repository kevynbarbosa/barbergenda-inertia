<template>
    <Card>
        <CardHeader>
            <CardTitle>Aprovação Final</CardTitle>
            <CardDescription>
                Realize a aprovação final da solicitação
            </CardDescription>
        </CardHeader>
        <CardContent>
            <Form
                :action="approvalAction.url()"
                method="post"
                class="space-y-6"
                v-slot="{ errors, processing, recentlySuccessful }"
            >
                <!-- Hidden field for solicitation ID -->
                <input type="hidden" name="solicitation_id" :value="solicitation.id" />

                <!-- Action Selection (apenas aprovar ou rejeitar na etapa final) -->
                <div class="space-y-3">
                    <Label class="text-base font-medium">Decisão Final</Label>
                    <ToggleGroup
                        type="single"
                        name="action"
                        class="justify-start"
                        default-value=""
                    >
                        <ToggleGroupItem value="approve" class="data-[state=on]:bg-green-100 data-[state=on]:text-green-700 dark:data-[state=on]:bg-green-900 dark:data-[state=on]:text-green-300">
                            <CheckCircle class="w-4 h-4 mr-2" />
                            Aprovar
                        </ToggleGroupItem>
                        <ToggleGroupItem value="reject" class="data-[state=on]:bg-red-100 data-[state=on]:text-red-700 dark:data-[state=on]:bg-red-900 dark:data-[state=on]:text-red-300">
                            <XCircle class="w-4 h-4 mr-2" />
                            Rejeitar
                        </ToggleGroupItem>
                    </ToggleGroup>
                    <InputError :message="errors.action" />
                </div>

                <!-- Observations -->
                <div class="space-y-2">
                    <Label for="observations">Observações da Aprovação</Label>
                    <Textarea
                        id="observations"
                        name="observations"
                        placeholder="Descreva os motivos da decisão final, recomendações ou observações importantes..."
                        rows="4"
                        required
                    />
                    <InputError :message="errors.observations" />
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-4">
                    <Button type="submit" :disabled="processing">
                        {{ processing ? 'Processando...' : 'Finalizar' }} Solicitação
                    </Button>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-show="recentlySuccessful" class="text-sm text-muted-foreground">
                            Decisão salva com sucesso!
                        </p>
                    </Transition>
                </div>
            </Form>
        </CardContent>
    </Card>
</template>

<script setup lang="ts">
import InputError from '@/components/common/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { ToggleGroup, ToggleGroupItem } from '@/components/ui/toggle-group';
import { Form } from '@inertiajs/vue3';
import { CheckCircle, XCircle } from 'lucide-vue-next';

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
const approvalAction = {
    url: () => `/solicitations/${props.solicitation.id}/approval`
};
</script>