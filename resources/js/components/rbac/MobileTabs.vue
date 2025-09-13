<template>
  <div class="space-y-4">
    <Tabs v-model="currentModuleId" :default-value="selectedModule.id">
      <div class="relative">
        <ScrollIndicators
          :show-left="showLeftIndicator"
          :show-right="showRightIndicator"
          :on-scroll-left="handleScrollLeft"
          :on-scroll-right="handleScrollRight"
        />

        <!-- Container de scroll das abas -->
        <div
          ref="scrollContainerRef"
          class="overflow-x-auto px-8 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]"
          @scroll="checkScrollIndicators"
        >
          <TabsList class="flex w-max h-auto p-1 mb-4 min-w-full">
            <TabsTrigger
              v-for="module in modules"
              :key="module.id"
              :value="module.id"
              class="flex-shrink-0 px-3 py-2 text-xs whitespace-nowrap"
            >
              <div class="flex flex-col items-center gap-1">
                <span class="font-medium">{{ module.name }}</span>
                <Badge variant="secondary" class="text-xs px-1.5 py-0.5">
                  {{ getEnabledCount(module) }}/{{ module.permissions.length }}
                </Badge>
              </div>
            </TabsTrigger>
          </TabsList>
        </div>
      </div>

      <TabsContent
        v-for="module in modules"
        :key="module.id"
        :value="module.id"
        class="mt-0"
      >
        <Card>
          <CardHeader class="pb-4">
            <CardTitle class="text-lg">Permissões - {{ module.name }}</CardTitle>
            <p class="text-sm text-muted-foreground">{{ module.description }}</p>
          </CardHeader>
          <CardContent>
            <div class="space-y-3">
              <div
                v-for="permission in module.permissions"
                :key="permission.id"
                class="flex items-start justify-between p-3 border rounded-lg hover:bg-muted/30 transition-colors"
              >
                <div class="flex-1 mr-3">
                  <h4 class="font-medium text-sm">{{ permission.name }}</h4>
                  <p class="text-xs text-muted-foreground mt-1 leading-relaxed">{{ permission.description }}</p>
                </div>
                <Switch
                  :checked="permission.enabled"
                  @update:checked="() => onPermissionToggle(permission.id)"
                  :aria-label="`Toggle ${permission.name}`"
                />
              </div>
            </div>
          </CardContent>
        </Card>
      </TabsContent>
    </Tabs>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue'
import { Switch } from '@/components/ui/switch'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import type { Module } from './types'
import ScrollIndicators from './ScrollIndicators.vue'

interface MobileTabsProps {
  modules: Module[]
  selectedModule: Module
  onModuleSelect: (moduleId: string) => void
  onPermissionToggle: (permissionId: string) => void
  getEnabledCount: (module: Module) => number
}

const props = defineProps<MobileTabsProps>()
const scrollContainerRef = ref<HTMLDivElement | null>(null)

// Estado interno para controle da aba
const currentModuleId = ref(props.selectedModule.id)

// Estado interno dos indicadores de scroll
const showLeftIndicator = ref(false)
const showRightIndicator = ref(false)

// Watcher para sincronizar com a mudança externa do selectedModule
watch(() => props.selectedModule.id, (newId) => {
  currentModuleId.value = newId
})

// Watcher para notificar mudanças de aba
watch(currentModuleId, (newId) => {
  props.onModuleSelect(newId)
})

// Função para verificar indicadores de scroll
const checkScrollIndicators = () => {
  const container = scrollContainerRef.value
  if (!container) return

  const { scrollLeft, scrollWidth, clientWidth } = container
  showLeftIndicator.value = scrollLeft > 0
  showRightIndicator.value = scrollLeft < scrollWidth - clientWidth - 1
}

// Funções locais para scroll
const handleScrollLeft = () => {
  const container = scrollContainerRef.value
  if (!container) return
  container.scrollBy({ left: -200, behavior: 'smooth' })
  setTimeout(checkScrollIndicators, 100)
}

const handleScrollRight = () => {
  const container = scrollContainerRef.value
  if (!container) return
  container.scrollBy({ left: 200, behavior: 'smooth' })
  setTimeout(checkScrollIndicators, 100)
}

onMounted(() => {
  setTimeout(() => {
    checkScrollIndicators()
  }, 100)
  
  const container = scrollContainerRef.value
  if (container) {
    container.addEventListener('scroll', checkScrollIndicators)
  }
})

onUnmounted(() => {
  const container = scrollContainerRef.value
  if (container) {
    container.removeEventListener('scroll', checkScrollIndicators)
  }
})
</script>