<script setup lang="ts">
import { backgroundConfig } from '@/config/background';
import { useSvgBackground } from '@/composables/useSvgBackground';

const { svgBackgroundUrl } = useSvgBackground(backgroundConfig.page);
</script>

<template>
    <div :style="{ height: '600px', backgroundImage: svgBackgroundUrl, backgroundAttachment: 'fixed', backgroundSize: 'cover' }"></div>
</template>
