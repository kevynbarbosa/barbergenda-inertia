<script setup lang="ts">
import { MoreHorizontal } from 'lucide-vue-next'
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

interface Props {
  class?: HTMLAttributes['class']
}

const props = defineProps<Props>()
</script>

<template>
  <span :class="cn('flex h-9 w-9 items-center justify-center', props.class)">
    <MoreHorizontal class="h-4 w-4" />
    <span class="sr-only">Mais páginas</span>
  </span>
</template>