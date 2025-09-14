<script setup lang="ts">
import { ChevronRight } from 'lucide-vue-next'
import PaginationListItem from './PaginationListItem.vue'

interface Props {
  href?: string
  disabled?: boolean
}

const props = defineProps<Props>()
</script>

<template>
  <PaginationListItem :href="href" :disabled="disabled">
    <ChevronRight class="h-4 w-4" />
    <span class="sr-only">Próxima</span>
  </PaginationListItem>
</template>