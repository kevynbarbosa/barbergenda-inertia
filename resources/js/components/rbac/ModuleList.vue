<template>
  <Card class="h-full lg:sticky lg:top-4 flex flex-col lg:max-h-[80vh]">
    <CardHeader class="flex-shrink-0">
      <CardTitle>Módulos do Sistema</CardTitle>
    </CardHeader>
    <CardContent class="p-0 flex-1 overflow-hidden">
      <div class="h-full overflow-y-auto px-4">
        <div class="space-y-1 py-2">
          <ModuleItem
            v-for="module in modules"
            :key="module.id"
            :module="module"
            :is-selected="selectedModule.id === module.id"
            :on-select="onModuleSelect"
            :enabled-count="getEnabledCount(module)"
          />
        </div>
      </div>
    </CardContent>
  </Card>
</template>

<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import type { Module } from './types'
import ModuleItem from './ModuleItem.vue'

interface ModuleListProps {
  modules: Module[]
  selectedModule: Module
  onModuleSelect: (module: Module) => void
  getEnabledCount: (module: Module) => number
}

defineProps<ModuleListProps>()
</script>