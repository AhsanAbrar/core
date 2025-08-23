import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useStore = defineStore('main', () => {
  const isMobileSidebar = ref<boolean>(false)
  const showSidebar = ref<boolean>(true)

  return {
    isMobileSidebar,
    showSidebar,
  }
})
