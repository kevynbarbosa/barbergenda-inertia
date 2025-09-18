<script setup lang="ts">
import AppContent from '@/components/layouts/AppContent.vue';
import AppShell from '@/components/layouts/AppShell.vue';
import AppSidebar from '@/components/layouts/AppSidebar.vue';
import AppSidebarHeader from '@/components/layouts/AppSidebarHeader.vue';
import { backgroundConfig } from '@/config/background';
import { useSvgBackground } from '@/composables/useSvgBackground';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const { svgBackgroundUrl } = useSvgBackground(backgroundConfig.sidebar);
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden" :style="{ backgroundImage: svgBackgroundUrl, backgroundAttachment: 'fixed', backgroundSize: 'cover' }">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>
    </AppShell>
</template>
