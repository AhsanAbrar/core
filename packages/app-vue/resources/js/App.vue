<template>
  <RouterView />

  <Modals />
  <ConfirmDialog />
</template>

<script setup lang="ts">
  import Modals from '@components/Modals.vue'
  import ConfirmDialog from '@components/ConfirmDialog.vue'
</script>
