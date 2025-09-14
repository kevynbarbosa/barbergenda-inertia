<script setup lang="ts">
import { ChevronLeft } from 'lucide-vue-next'
import PaginationListItem from './PaginationListItem.vue'

interface Props {
  href?: string
  disabled?: boolean
}

const props = defineProps<Props>()
</script>

<template>
  <PaginationListItem :href="href" :disabled="disabled">
    <ChevronLeft class="h-4 w-4" />
    <span class="sr-only">Anterior</span>
  </PaginationListItem>
</template>