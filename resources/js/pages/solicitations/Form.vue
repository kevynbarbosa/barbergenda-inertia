<template>
    <ContainerDefault>
        <!-- Form Card -->
        <div class="flex justify-center">
            <Card class="w-full max-w-lg">
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle class="text-3xl">Nova Solicitação</CardTitle>
                            <CardDescription> Informe o tipo de documento e o número correspondente </CardDescription>
                        </div>
                        <Button as-child variant="outline" size="sm">
                            <Link :href="solicitationsIndex.url()">
                                <ArrowLeft class="mr-2 h-4 w-4" />
                                Voltar
                            </Link>
                        </Button>
                    </div>
                </CardHeader>
                <CardContent>
                    <Form :action="solicitationsStore.url()" method="post" class="space-y-6" v-slot="{ errors, processing, recentlySuccessful }">
                        <!-- Tipo de Documento -->
                        <div class="space-y-3">
                            <Label class="text-base font-medium">Tipo de Documento</Label>
                            <RadioGroup default-value="cpf" name="document_type" class="flex space-x-6">
                                <div class="flex items-center space-x-2">
                                    <RadioGroupItem value="cpf" id="cpf" />
                                    <Label for="cpf" class="cursor-pointer">CPF</Label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <RadioGroupItem value="cnpj" id="cnpj" />
                                    <Label for="cnpj" class="cursor-pointer">CNPJ</Label>
                                </div>
                            </RadioGroup>
                            <InputError :message="errors.document_type" />
                        </div>

                        <!-- Documento -->
                        <div class="space-y-2">
                            <Label for="document">Número do Documento</Label>
                            <Input id="document" name="document" type="text" placeholder="Digite o número do documento" required />
                            <InputError :message="errors.document" />
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-center gap-4">
                            <Button type="submit" :disabled="processing"> {{ processing ? 'Salvando...' : 'Criar' }} Solicitação </Button>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-show="recentlySuccessful" class="text-sm text-muted-foreground">Salvo com sucesso!</p>
                            </Transition>
                        </div>
                    </Form>
                </CardContent>
            </Card>
        </div>
    </ContainerDefault>
</template>

<script setup lang="ts">
import ContainerDefault from '@/components/ContainerDefault.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { index as solicitationsIndex, store as solicitationsStore } from '@/routes/solicitations';
import { Form, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

// Não há props necessárias para criação
</script>
