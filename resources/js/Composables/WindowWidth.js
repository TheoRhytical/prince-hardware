import { computed, onUnmounted, onMounted, ref } from 'vue';

export const useBreakpoints = () => {
	const windowWidth = ref(window.innerWidth)

  const onWidthChange = () => windowWidth.value = window.innerWidth
  onMounted(() => window.addEventListener('resize', onWidthChange))
  onUnmounted(() => window.removeEventListener('resize', onWidthChange))
  
  const type = computed(() => {
    if (windowWidth.value < 550) return 'xs'
    if (windowWidth.value >= 550 && windowWidth.value < 1400) return 'md'
    else return 'lg' // Fires when windowWidth.value >= 1200
  })

  const width = computed(() => windowWidth.value)

  return { width, type }

}