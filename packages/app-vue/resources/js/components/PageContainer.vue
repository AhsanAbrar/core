<template>
  <div :class="wrapperClasses">
    <!-- Loader -->
    <div v-if="loading" class="flex items-center justify-center py-6">
      <Loader />
    </div>

    <!-- Content -->
    <div v-else>
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
  import { computed } from 'vue'
  import Loader from '@ui/Loader.vue'

  const { loading = false, variant = 'index' } = defineProps<{
    loading?: boolean
    variant?: 'index' | 'form'
  }>()

  const wrapperClasses = computed(() => {
    if (variant === 'form') {
      return 'mx-auto max-w-3xl px-8 pt-6 pb-12'
    }

    return 'px-8 pt-6 pb-12'
  })
</script>
