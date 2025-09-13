<template>
    <Card class="flex h-full flex-col lg:sticky lg:top-4 lg:max-h-[70vh]">
        <CardHeader class="flex-shrink-0">
            <CardTitle>Módulos do Sistema</CardTitle>
        </CardHeader>
        <CardContent class="flex-1 overflow-hidden p-0">
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
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import ModuleItem from './ModuleItem.vue';
import type { Module } from './types';

interface ModuleListProps {
    modules: Module[];
    selectedModule: Module;
    onModuleSelect: (module: Module) => void;
    getEnabledCount: (module: Module) => number;
}

defineProps<ModuleListProps>();
</script>
