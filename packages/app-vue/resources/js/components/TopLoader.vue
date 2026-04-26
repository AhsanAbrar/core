<template>
  <div
    v-show="topLoader.active"
    class="fixed h-0.5 w-full overflow-hidden bg-transparent"
  >
    <div
      class="absolute inset-y-0 left-0 rounded bg-gray-900/70 transition-[width,opacity] duration-200 ease-out"
      :style="{ width: `${topLoader.progress}%` }"
    />
  </div>
</template>

<script setup lang="ts">
  import { useTopLoaderStore } from '@stores/top-loader'

  const topLoader = useTopLoaderStore()
</script>

<style scoped>
  @keyframes topbar {
    0% {
      transform: translateX(-120%);
    }
    100% {
      transform: translateX(420%);
    }
  }
</style>
