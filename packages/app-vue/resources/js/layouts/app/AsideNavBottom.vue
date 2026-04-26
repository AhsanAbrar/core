<template>
  <nav class="mt-auto space-y-1">
    <AsideNavItem to="/settings" label="Settings">
      <Cog6ToothIcon class="size-4" aria-hidden="true" />
    </AsideNavItem>
  </nav>
</template>

<script setup lang="ts">
  import AsideNavItem from './AsideNavItem.vue'

  import { Cog6ToothIcon } from '@heroicons/vue/24/outline'
</script>
