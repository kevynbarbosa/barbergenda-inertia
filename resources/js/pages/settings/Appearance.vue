<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import ContainerDefault from '@/components/ContainerDefault.vue';
import SettingsNav from '@/components/SettingsNav.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
</script>

<template>
    <Head title="Appearance settings" />

    <ContainerDefault>
        <!-- Header -->
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-bold">Configurações</h1>
                <p class="text-muted-foreground">Gerencie suas configurações de perfil e conta</p>
            </div>

            <SettingsNav>
                <!-- Appearance Settings Card -->
                <Card>
                <CardHeader>
                    <CardTitle>Configurações de Aparência</CardTitle>
                    <CardDescription>
                        Atualize as configurações de aparência da sua conta
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <AppearanceTabs />
                </CardContent>
            </Card>
            </SettingsNav>
        </div>
    </ContainerDefault>
</template>
