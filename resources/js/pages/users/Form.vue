<template>
    <div class="container mx-auto max-w-2xl p-6">
        <Card>
            <CardHeader>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <CardTitle class="text-3xl">
                            {{ isEditing ? 'Editar Usuário' : 'Criar Usuário' }}
                        </CardTitle>
                        <CardDescription>
                            {{ isEditing ? 'Atualize as informações do usuário' : 'Adicione um novo usuário ao sistema' }}
                        </CardDescription>
                    </div>
                </div>
            </CardHeader>
            <CardContent>
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <Label for="name">Nome *</Label>
                            <Input id="name" v-model="form.name" type="text" required :class="{ 'border-destructive': form.errors.name }" />
                            <div v-if="form.errors.name" class="mt-1 text-sm text-destructive">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <div>
                            <Label for="email">E-mail *</Label>
                            <Input id="email" v-model="form.email" type="email" required :class="{ 'border-destructive': form.errors.email }" />
                            <div v-if="form.errors.email" class="mt-1 text-sm text-destructive">
                                {{ form.errors.email }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <Label for="password">
                                {{ isEditing ? 'Nova Senha (deixe em branco para manter a atual)' : 'Senha *' }}
                            </Label>
                            <Input
                                id="password"
                                v-model="form.password"
                                type="password"
                                :required="!isEditing"
                                :class="{ 'border-destructive': form.errors.password }"
                            />
                            <div v-if="form.errors.password" class="mt-1 text-sm text-destructive">
                                {{ form.errors.password }}
                            </div>
                        </div>

                        <div v-if="form.password || !isEditing">
                            <Label for="password_confirmation">
                                {{ isEditing ? 'Confirmar Nova Senha *' : 'Confirmar Senha *' }}
                            </Label>
                            <Input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                :required="!!form.password || !isEditing"
                            />
                        </div>
                    </div>

                    <div>
                        <Label class="mb-3 block">Roles</Label>
                        <div class="rounded-md border p-4">
                            <div class="space-y-3">
                                <div v-for="role in roles" :key="role.id" class="flex items-start space-x-3">
                                    <Checkbox
                                        :id="`role-${role.id}`"
                                        :modelValue="form.roles.includes(role.id)"
                                        @update:modelValue="(checked) => handleRoleToggle(role.id, checked)"
                                    />
                                    <div class="grid gap-1.5 leading-none">
                                        <Label :for="`role-${role.id}`" class="cursor-pointer text-sm leading-none font-medium">
                                            {{ role.display_name }}
                                        </Label>
                                        <p v-if="role.description" class="text-xs text-muted-foreground">
                                            {{ role.description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="form.errors.roles" class="mt-1 text-sm text-destructive">
                            {{ form.errors.roles }}
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6">
                        <Button variant="outline" as-child>
                            <Link :href="usersIndex.url()"> Cancelar </Link>
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ form.processing ? (isEditing ? 'Salvando...' : 'Criando...') : isEditing ? 'Salvar Alterações' : 'Criar Usuário' }}
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index as usersIndex, store as usersStore, update as usersUpdate } from '@/routes/users';
import type { Role, User } from '@/types/user';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    user?: User;
    roles: Role[];
}>();

const isEditing = computed(() => !!props.user);

const form = useForm({
    name: props.user?.name || '',
    email: props.user?.email || '',
    password: '',
    password_confirmation: '',
    roles: props.user?.roles.map((role) => role.id) || ([] as number[]),
});

const handleRoleToggle = (roleId: number, checked: boolean) => {
    if (checked) {
        if (!form.roles.includes(roleId)) {
            form.roles.push(roleId);
        }
    } else {
        const index = form.roles.indexOf(roleId);
        if (index > -1) {
            form.roles.splice(index, 1);
        }
    }
};

const submit = () => {
    if (isEditing.value) {
        form.put(usersUpdate.url(props.user!.id));
    } else {
        form.post(usersStore.url());
    }
};
</script>
