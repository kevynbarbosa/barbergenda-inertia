<template>
    <div class="flex items-start justify-between rounded-lg border p-2 transition-colors hover:bg-muted/30">
        <div class="mr-4 flex-1">
            <div class="flex items-center gap-2">
                <h4 class="text-sm font-medium">{{ permission.display_name || permission.name }}</h4>
                <span class="rounded-md bg-primary/10 px-2 py-0.5 text-xs text-primary">{{ permission.module }}</span>
            </div>
            <p class="mt-1 text-sm text-muted-foreground">{{ permission.description }}</p>
        </div>
        <Switch :modelValue="permission.enabled" @update:modelValue="onToggleLocal" :aria-label="`Toggle ${permission.name}`" />
    </div>
</template>

<script setup lang="ts">
import { Switch } from '@/components/ui/switch';
import type { Permission } from './types';

interface PermissionItemProps {
    permission: Permission;
}

const props = defineProps<PermissionItemProps>();

const emit = defineEmits<{
    toggle: [permissionId: string];
}>();

const onToggleLocal = () => {
    emit('toggle', props.permission.id);
};
</script>
