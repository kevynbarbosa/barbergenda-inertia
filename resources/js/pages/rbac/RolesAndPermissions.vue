<template>
    <ContainerDefault class="p-4">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Roles e Permissões</h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Esta página foi reorganizada. Use o menu lateral para:
                </p>
                <ul class="mt-2 list-disc list-inside text-sm text-gray-600 dark:text-gray-400">
                    <li><strong>Roles:</strong> para gerenciar roles do sistema</li>
                    <li><strong>Usuários:</strong> para gerenciar usuários e suas roles</li>
                </ul>
            </div>
            <div class="flex gap-2">
                <Button as-child>
                    <Link :href="rolesIndex.url()">
                        Ver Roles
                    </Link>
                </Button>
                <Button variant="outline" as-child>
                    <Link :href="usersIndex.url()">
                        Ver Usuários
                    </Link>
                </Button>
            </div>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>Reorganização da Interface</CardTitle>
                <CardDescription>
                    A gestão de roles e usuários foi reorganizada para uma melhor experiência
                </CardDescription>
            </CardHeader>
            <CardContent>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-lg border p-4">
                        <h3 class="font-medium">Gestão de Roles</h3>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Crie, edite e gerencie roles com suas respectivas permissões
                        </p>
                        <Button class="mt-3" as-child>
                            <Link :href="rolesIndex.url()">
                                Acessar Roles
                            </Link>
                        </Button>
                    </div>
                    <div class="rounded-lg border p-4">
                        <h3 class="font-medium">Gestão de Usuários</h3>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Gerencie usuários e atribua roles a eles
                        </p>
                        <Button class="mt-3" variant="outline" as-child>
                            <Link :href="usersIndex.url()">
                                Acessar Usuários
                            </Link>
                        </Button>
                    </div>
                </div>
            </CardContent>
        </Card>
    </ContainerDefault>
</template>

<script setup lang="ts">
import ContainerDefault from '@/components/ContainerDefault.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { index as rolesIndex } from '@/routes/roles';
import { index as usersIndex } from '@/routes/users';
import { Link } from '@inertiajs/vue3';
</script>
