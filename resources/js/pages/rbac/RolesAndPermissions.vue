<template>
  <div class="container mx-auto p-4 max-w-7xl">
    <div class="mb-8">
      <h1 class="text-3xl font-bold">Controle de Acesso</h1>
      <p class="text-muted-foreground mt-2">
        Gerencie permissões por módulo do sistema
      </p>
    </div>

    <!-- Layout Mobile -->
    <div v-if="isMobile" class="lg:hidden">
      <MobileTabs
        v-if="selectedModule"
        :modules="modules"
        :selected-module="selectedModule"
        :on-module-select="handleModuleSelect"
        :on-permission-toggle="handlePermissionToggle"
        :get-enabled-count="getEnabledCount"
      />
    </div>

    <!-- Layout Desktop -->
    <div v-else class="hidden lg:grid lg:grid-cols-5 gap-6 h-[80vh]">
      <div class="col-span-2">
        <ModuleList
          :modules="modules"
          :selected-module="selectedModule"
          :on-module-select="handleModuleSelect"
          :get-enabled-count="getEnabledCount"
        />
      </div>
      <div class="col-span-3">
        <PermissionsList
          :module="selectedModule"
          :on-permission-toggle="handlePermissionToggle"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useMobile } from '@/composables/useMobile'
import type { Module } from '@/components/rbac/types'
import { modules as moduleData } from '@/components/rbac/data'
import ModuleList from '@/components/rbac/ModuleList.vue'
import PermissionsList from '@/components/rbac/PermissionsList.vue'
import MobileTabs from '@/components/rbac/MobileTabs.vue'

// Estado reativo
const modules = reactive(moduleData)
const selectedModule = ref<Module>(modules[0])

// Composables
const { isMobile } = useMobile()

// Funções auxiliares
const getEnabledCount = (module: Module): number => {
  return module.permissions.filter((permission) => permission.enabled).length
}

const handleModuleSelect = (moduleOrId: Module | string) => {
  if (typeof moduleOrId === 'string') {
    const module = modules.find((m) => m.id === moduleOrId)
    if (module) {
      selectedModule.value = module
    }
  } else {
    selectedModule.value = moduleOrId
  }
}

const handlePermissionToggle = (permissionId: string) => {
  const module = modules.find((m) =>
    m.permissions.some((p) => p.id === permissionId)
  )
  
  if (module) {
    const permission = module.permissions.find((p) => p.id === permissionId)
    if (permission) {
      permission.enabled = !permission.enabled
    }
  }
}
</script>