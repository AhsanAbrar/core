<template>
  <RouterView v-if="store.isBlankPage" />

  <Layout v-else />
</template>

<script setup lang="ts">
  import { useAppStore } from 'ahsandevs'
  import Layout from 'View/layouts/Layout.vue'

  const store = useAppStore()
</script>
