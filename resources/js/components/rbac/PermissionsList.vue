<template>
    <Card class="flex h-full flex-col lg:h-[70vh]">
        <CardHeader class="flex-shrink-0">
            <CardTitle>Permissões - {{ module.name }}</CardTitle>
            <p class="text-sm text-muted-foreground">{{ module.description }}</p>
        </CardHeader>
        <CardContent class="flex-1 overflow-y-auto">
            <div class="space-y-4">
                <PermissionItem
                    v-for="permission in module.permissions"
                    :key="permission.id"
                    :permission="permission"
                    :on-toggle="onPermissionToggle"
                />
            </div>
        </CardContent>
    </Card>
</template>

<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import PermissionItem from './PermissionItem.vue';
import type { Module } from './types';

interface PermissionsListProps {
    module: Module;
    onPermissionToggle: (permissionId: string) => void;
}

defineProps<PermissionsListProps>();
</script>
