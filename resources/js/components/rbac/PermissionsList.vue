<template>
  <Card class="h-full flex flex-col lg:max-h-[80vh]">
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
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import type { Module } from './types'
import PermissionItem from './PermissionItem.vue'

interface PermissionsListProps {
  module: Module
  onPermissionToggle: (permissionId: string) => void
}

defineProps<PermissionsListProps>()
</script>