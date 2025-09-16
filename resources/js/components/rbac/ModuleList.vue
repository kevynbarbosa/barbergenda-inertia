<template>
    <Card class="flex h-full flex-col lg:sticky lg:top-4 lg:max-h-[70vh]">
        <CardHeader class="flex-shrink-0">
            <CardTitle>Módulos do Sistema</CardTitle>
        </CardHeader>
        <CardContent class="flex-1 overflow-hidden p-0">
            <div class="h-full overflow-y-auto">
                <div class="space-y-1 py-2">
                    <ModuleItem
                        v-for="module in modules"
                        :key="module.id"
                        :module="module"
                        :is-selected="selectedModule.id === module.id"
                        :enabled-count="getEnabledCount(module)"
                        @select="handleModuleSelect"
                    />
                </div>
            </div>
        </CardContent>
    </Card>
</template>

<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import ModuleItem from './ModuleItem.vue';
import type { Module } from './types';

interface ModuleListProps {
    modules: Module[];
    selectedModule: Module;
    getEnabledCount: (module: Module) => number;
}

defineProps<ModuleListProps>();

const emit = defineEmits<{
    moduleSelect: [module: Module];
}>();

const handleModuleSelect = (module: Module) => {
    emit('moduleSelect', module);
};
</script>
