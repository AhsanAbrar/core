<template>
  <TopLoader />

  <div class="flex h-screen overflow-hidden bg-gray-100 dark:bg-gray-950">
    <Aside />

    <main class="flex-1 overflow-y-auto">
      <RouterView />
    </main>
  </div>
</template>

<script setup lang="ts">
  import TopLoader from '@components/TopLoader.vue'
  import Aside from './Aside.vue'
</script>
