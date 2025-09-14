import { ref, onMounted, type Ref } from 'vue'

export function useScrollIndicators(isMobile: Ref<boolean>) {
  const showLeftIndicator = ref(false)
  const showRightIndicator = ref(false)
  const scrollContainerRef = ref<HTMLDivElement | null>(null)

  const checkScrollIndicators = () => {
    const container = scrollContainerRef.value
    if (!container) return

    const { scrollLeft, scrollWidth, clientWidth } = container
    showLeftIndicator.value = scrollLeft > 0
    showRightIndicator.value = scrollLeft < scrollWidth - clientWidth - 1
  }

  const scrollTabs = (direction: 'left' | 'right') => {
    const container = scrollContainerRef.value
    if (!container) return

    const scrollAmount = 200
    const newScrollLeft =
      direction === 'left' ? container.scrollLeft - scrollAmount : container.scrollLeft + scrollAmount

    container.scrollTo({ left: newScrollLeft, behavior: 'smooth' })
  }

  onMounted(() => {
    if (isMobile.value) {
      checkScrollIndicators()
    }
  })

  return {
    showLeftIndicator,
    showRightIndicator,
    scrollContainerRef,
    checkScrollIndicators,
    scrollTabs,
  }
}