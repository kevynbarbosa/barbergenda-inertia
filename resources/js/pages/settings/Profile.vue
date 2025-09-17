<script setup lang="ts">
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import { send } from '@/routes/verification';
import { Form, Head, Link, usePage } from '@inertiajs/vue3';

import ContainerDefault from '@/components/ContainerDefault.vue';
import DeleteUser from '@/components/DeleteUser.vue';
import InputError from '@/components/InputError.vue';
import SettingsNav from '@/components/SettingsNav.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const page = usePage();
const user = page.props.auth.user;
</script>

<template>
    <Head title="Profile settings" />

    <ContainerDefault>
        <!-- Header -->
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-bold">Configurações</h1>
                <p class="text-muted-foreground">Gerencie suas configurações de perfil e conta</p>
            </div>

            <SettingsNav>
                <div class="space-y-6">
                    <!-- Profile Information Card -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Informações do Perfil</CardTitle>
                            <CardDescription> Atualize seu nome e endereço de email </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Form v-bind="ProfileController.update.form()" class="space-y-6" v-slot="{ errors, processing, recentlySuccessful }">
                                <div class="grid gap-2">
                                    <Label for="name">Nome</Label>
                                    <Input
                                        id="name"
                                        class="mt-1 block w-full"
                                        name="name"
                                        :default-value="user.name"
                                        required
                                        autocomplete="name"
                                        placeholder="Nome completo"
                                    />
                                    <InputError class="mt-2" :message="errors.name" />
                                </div>

                                <div class="grid gap-2">
                                    <Label for="email">Endereço de email</Label>
                                    <Input
                                        id="email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        name="email"
                                        :default-value="user.email"
                                        required
                                        autocomplete="username"
                                        placeholder="Endereço de email"
                                    />
                                    <InputError class="mt-2" :message="errors.email" />
                                </div>

                                <div v-if="mustVerifyEmail && !user.email_verified_at">
                                    <p class="-mt-4 text-sm text-muted-foreground">
                                        Seu endereço de email não foi verificado.
                                        <Link
                                            :href="send()"
                                            as="button"
                                            class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                                        >
                                            Clique aqui para reenviar o email de verificação.
                                        </Link>
                                    </p>

                                    <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                                        Um novo link de verificação foi enviado para seu endereço de email.
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    <Button :disabled="processing">Salvar</Button>

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

                    <Separator />

                    <!-- Delete Account Card -->
                    <Card>
                        <CardContent>
                            <DeleteUser />
                        </CardContent>
                    </Card>
                </div>
            </SettingsNav>
        </div>
    </ContainerDefault>
</template>
