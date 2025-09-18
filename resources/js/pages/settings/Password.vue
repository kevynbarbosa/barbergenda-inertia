<script setup lang="ts">
import PasswordController from '@/actions/App/Http/Controllers/Settings/PasswordController';
import ContainerDefault from '@/components/layouts/ContainerDefault.vue';
import InputError from '@/components/common/InputError.vue';
import SettingsNav from '@/components/common/SettingsNav.vue';
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);
</script>

<template>
    <Head title="Password settings" />

    <ContainerDefault>
        <!-- Header -->
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-bold">Configurações</h1>
                <p class="text-muted-foreground">Gerencie suas configurações de perfil e conta</p>
            </div>

            <SettingsNav>
                <!-- Update Password Card -->
                <Card>
                <CardHeader>
                    <CardTitle>Atualizar Senha</CardTitle>
                    <CardDescription>
                        Certifique-se de que sua conta esteja usando uma senha longa e aleatória para permanecer segura
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Form
                        v-bind="PasswordController.update.form()"
                        :options="{
                            preserveScroll: true,
                        }"
                        reset-on-success
                        :reset-on-error="['password', 'password_confirmation', 'current_password']"
                        class="space-y-6"
                        v-slot="{ errors, processing, recentlySuccessful }"
                    >
                        <div class="grid gap-2">
                            <Label for="current_password">Senha atual</Label>
                            <Input
                                id="current_password"
                                ref="currentPasswordInput"
                                name="current_password"
                                type="password"
                                class="mt-1 block w-full"
                                autocomplete="current-password"
                                placeholder="Senha atual"
                            />
                            <InputError :message="errors.current_password" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="password">Nova senha</Label>
                            <Input
                                id="password"
                                ref="passwordInput"
                                name="password"
                                type="password"
                                class="mt-1 block w-full"
                                autocomplete="new-password"
                                placeholder="Nova senha"
                            />
                            <InputError :message="errors.password" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="password_confirmation">Confirmar senha</Label>
                            <Input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                autocomplete="new-password"
                                placeholder="Confirmar senha"
                            />
                            <InputError :message="errors.password_confirmation" />
                        </div>

                        <div class="flex items-center gap-4">
                            <Button :disabled="processing">Salvar senha</Button>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-show="recentlySuccessful" class="text-sm text-neutral-600">Salvo.</p>
                            </Transition>
                        </div>
                    </Form>
                </CardContent>
            </Card>
            </SettingsNav>
        </div>
    </ContainerDefault>
</template>
