<template>
  <Header />
  <Sidebar />

  <main
    class="h-full pt-12 transition-all duration-300"
    :class="[store.showSidebar && 'ltr:pl-64 rtl:pr-64']"
  >
    <RouterView />
  </main>

  <Modals />
  <Confirm />
  <ErrorModal />
  <FlashMessage />
</template>

<script setup lang="ts">
  import { useStore } from 'Store/main'
  import { Confirm, ErrorModal, FlashMessage, Modals } from 'thetheme'
  import Header from './Header.vue'
  import Sidebar from './Sidebar.vue'

  const store = useStore()
</script>
