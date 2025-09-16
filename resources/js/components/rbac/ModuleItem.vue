<template>
  <button
    type="button"
    @click="() => handleSelect()"
    :class="`w-full text-left p-4 hover:bg-muted/50 transition-colors border-l-4 rounded-r-md ${
      isSelected ? 'border-l-primary bg-muted/50' : 'border-l-transparent'
    }`"
  >
    <div class="flex items-center justify-between">
      <div>
        <h3 class="font-medium">{{ module.name }}</h3>
        <p class="text-sm text-muted-foreground mt-1">{{ module.description }}</p>
      </div>
      <Badge variant="secondary" class="ml-2">
        {{ enabledCount }}/{{ module.permissions.length }}
      </Badge>
    </div>
  </button>
</template>

<script setup lang="ts">
import { Badge } from '@/components/ui/badge'
import type { Module } from './types'

interface ModuleItemProps {
  module: Module
  isSelected: boolean
  enabledCount: number
}

const props = defineProps<ModuleItemProps>()

const emit = defineEmits<{
  select: [module: Module];
}>()

const handleSelect = () => {
  emit('select', props.module)
}
</script>