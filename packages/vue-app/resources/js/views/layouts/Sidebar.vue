<template>
  <aside
    id="sidebar"
    :class="[!store.showSidebar && 'ltr:-translate-x-full rtl:translate-x-full']"
    class="fixed inset-y-0 z-40 grid w-64 transform grid-rows-[auto_1fr] bg-gray-800 transition-transform duration-300"
  >
    <SidebarLogo />

    <div class="custom-sidebar-scrollbar">
      <SidebarNav />
    </div>
  </aside>
</template>

<script setup lang="ts">
  import SidebarLogo from './SidebarLogo.vue'
  import SidebarNav from './SidebarNav.vue'
  import { useStore } from 'Store/main'

  const store = useStore()
</script>
