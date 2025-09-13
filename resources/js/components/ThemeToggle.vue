<template>
  <div class="flex items-center gap-2">
    <Sun :size="16" class="text-muted-foreground" />
    <Switch 
      :model-value="isDarkMode" 
      @update:model-value="handleSwitchChange" 
      aria-label="Toggle dark mode" 
    />
    <Moon :size="16" class="text-muted-foreground" />
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Switch } from '@/components/ui/switch'
import { useAppearance } from '@/composables/useAppearance'
import { Moon, Sun } from 'lucide-vue-next'

const { appearance, updateAppearance } = useAppearance()

const isDarkMode = computed(() => {
  if (appearance.value === 'system') {
    if (typeof window !== 'undefined') {
      return window.matchMedia('(prefers-color-scheme: dark)').matches
    }
    return false
  }
  return appearance.value === 'dark'
})

const handleSwitchChange = (checked: boolean) => {
  const newTheme = checked ? 'dark' : 'light'
  updateAppearance(newTheme)
}
</script>
