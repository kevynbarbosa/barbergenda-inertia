<template>
    <!-- Desktop Layout -->
    <div v-if="!isMobile" class="grid gap-6 lg:grid-cols-5">
        <!-- ModuleList para desktop -->
        <div class="lg:col-span-2">
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
        </div>
        <!-- PermissionsList para desktop -->
        <div class="lg:col-span-3">
            <PermissionsList
                :module="selectedModule"
                @permission-toggle="handlePermissionToggle"
            />
        </div>
    </div>

    <!-- Mobile Layout (Tabs) -->
    <div v-else class="space-y-4">
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
                <PermissionsList
                    :module="module"
                    @permission-toggle="handlePermissionToggle"
                />
            </TabsContent>
        </Tabs>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { useMobile } from '@/composables/useMobile';
import ModuleItem from './ModuleItem.vue';
import PermissionsList from './PermissionsList.vue';
import ScrollIndicators from './ScrollIndicators.vue';
import type { Module } from './types';

interface ModuleListProps {
    modules: Module[];
}

const props = defineProps<ModuleListProps>();

const emit = defineEmits<{
    permissionToggle: [permissionId: string];
}>();

const { isMobile } = useMobile();

// Estado interno do módulo selecionado
const selectedModule = ref<Module>(props.modules[0] || {} as Module);

// Função para calcular permissões habilitadas
const getEnabledCount = (module: Module): number => {
    return module.permissions.filter((permission) => permission.enabled).length;
};

// Desktop handlers
const handleModuleSelect = (module: Module) => {
    selectedModule.value = module;
};

// Mobile tabs logic
const scrollContainerRef = ref<HTMLDivElement | null>(null);
const currentModuleId = ref(selectedModule.value.id);
const showLeftIndicator = ref(false);
const showRightIndicator = ref(false);

// Watcher para sincronizar mudanças de aba no mobile
watch(currentModuleId, (newId) => {
    const module = props.modules.find(m => m.id === newId);
    if (module) {
        selectedModule.value = module;
    }
});

// Permission toggle handler
const handlePermissionToggle = (permissionId: string) => {
    emit('permissionToggle', permissionId);
};

// Função para verificar indicadores de scroll
const checkScrollIndicators = () => {
    const container = scrollContainerRef.value;
    if (!container) return;

    const { scrollLeft, scrollWidth, clientWidth } = container;
    showLeftIndicator.value = scrollLeft > 0;
    showRightIndicator.value = scrollLeft < scrollWidth - clientWidth - 1;
};

// Funções de scroll
const handleScrollLeft = () => {
    const container = scrollContainerRef.value;
    if (container) {
        container.scrollBy({ left: -200, behavior: 'smooth' });
    }
};

const handleScrollRight = () => {
    const container = scrollContainerRef.value;
    if (container) {
        container.scrollBy({ left: 200, behavior: 'smooth' });
    }
};

// Lifecycle para mobile tabs
onMounted(() => {
    if (isMobile.value) {
        checkScrollIndicators();
        window.addEventListener('resize', checkScrollIndicators);
    }
});

onUnmounted(() => {
    if (isMobile.value) {
        window.removeEventListener('resize', checkScrollIndicators);
    }
});
</script>
