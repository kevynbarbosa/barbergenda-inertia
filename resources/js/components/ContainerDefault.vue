<template>
    <div data-slot="container" :class="cn('container mx-auto max-w-7xl space-y-6 p-2 md:p-6', props.class)">
        <slot />
    </div>
</template>

<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>
