<template>
  <!-- Indicador esquerdo -->
  <button
    v-if="showLeft"
    @click="onScrollLeft"
    class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-background/80 backdrop-blur-sm border rounded-full p-1 shadow-sm"
    aria-label="Scroll para esquerda"
  >
    <ChevronLeft class="h-4 w-4" />
  </button>

  <!-- Indicador direito -->
  <button
    v-if="showRight"
    @click="onScrollRight"
    class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-background/80 backdrop-blur-sm border rounded-full p-1 shadow-sm"
    aria-label="Scroll para direita"
  >
    <ChevronRight class="h-4 w-4" />
  </button>

  <!-- Gradientes nas bordas para indicar scroll -->
  <div
    v-if="showLeft"
    class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-background to-transparent pointer-events-none"
  />
  <div
    v-if="showRight"
    class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-background to-transparent pointer-events-none"
  />
</template>

<script setup lang="ts">
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'

interface ScrollIndicatorsProps {
  showLeft: boolean
  showRight: boolean
  onScrollLeft: () => void
  onScrollRight: () => void
}

defineProps<ScrollIndicatorsProps>()
</script>