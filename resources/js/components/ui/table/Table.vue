<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { Primitive, type PrimitiveProps } from 'reka-ui'
import { type TableVariants, tableVariants } from '.'

interface Props extends PrimitiveProps {
  variant?: TableVariants['variant']
  class?: HTMLAttributes['class']
}

const props = withDefaults(defineProps<Props>(), {
  as: 'table',
})
</script>

<template>
  <div class="relative w-full overflow-auto">
    <Primitive
      :as="as"
      :as-child="asChild"
      :class="cn(tableVariants({ variant }), props.class)"
    >
      <slot />
    </Primitive>
  </div>
</template>