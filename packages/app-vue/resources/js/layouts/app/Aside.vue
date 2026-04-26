<template>
  <aside
    class="flex h-screen w-64 flex-col border-r border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950"
    aria-label="Sidebar"
  >
    <!-- Logo -->
    <AsideLogoSettings v-if="isSettings" />
    <AsideLogo v-else />

    <!-- Nav -->
    <div class="flex flex-1 flex-col overflow-y-auto px-2 py-3">
      <AsideNavSettings v-if="isSettings" />
      <AsideNav v-else />
      <AsideNavBottom v-if="!isSettings" />
    </div>

    <!-- User account -->
    <div class="border-t border-gray-200 p-2 dark:border-gray-800">
      <AsideAccount />
    </div>
  </aside>
</template>

<script setup lang="ts">
  import { computed } from 'vue'
  import { useRoute } from 'vue-router'
  import AsideLogo from './AsideLogo.vue'
  import AsideNav from './AsideNav.vue'
  import AsideNavBottom from './AsideNavBottom.vue'
  import AsideAccount from './AsideAccount.vue'
  import AsideLogoSettings from './AsideLogoSettings.vue'
  import AsideNavSettings from './AsideNavSettings.vue'

  const route = useRoute()

  const isSettings = computed(() => {
    return route.meta.layout === 'settings'
  })
</script>
