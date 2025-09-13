<template>
  <div class="flex items-start justify-between p-4 border rounded-lg hover:bg-muted/30 transition-colors">
    <div class="flex-1 mr-4">
      <h4 class="font-medium text-sm">{{ permission.name }}</h4>
      <p class="text-sm text-muted-foreground mt-1">{{ permission.description }}</p>
    </div>
    <Switch
      :checked="permission.enabled"
      @update:checked="onToggleLocal"
      :aria-label="`Toggle ${permission.name}`"
    />
  </div>
</template>

<script setup lang="ts">
import { Switch } from '@/components/ui/switch'
import type { Permission } from './types'

interface PermissionItemProps {
  permission: Permission
  onToggle: (permissionId: string) => void
}

const props = defineProps<PermissionItemProps>()

const onToggleLocal = () => {
  props.onToggle(props.permission.id)
}
</script>