<template>
  <header class="fixed z-30 h-12 w-full bg-white shadow-sm">
    <div class="flex h-full items-center">
      <RouterLink to="/" class="flex h-12 w-64 items-center px-8">
        <Square2StackIcon class="h-6 w-6 text-blue-500" />
        <span class="text-lg font-semibold text-gray-800 ltr:ml-2.5 rtl:mr-2.5">
          {{ appData.app_name }}
        </span>
      </RouterLink>

      <div class="flex items-center ltr:pl-8 rtl:pr-8">
        <Bars3BottomRightIcon
          :class="[store.showSidebar ? 'ltr:block rtl:hidden' : 'ltr:hidden rtl:block']"
          class="h-5 w-5 cursor-pointer text-gray-500 hover:text-gray-700 ltr:mr-8 rtl:ml-8"
          @click="sidebarToggle"
        />
        <Bars3BottomLeftIcon
          :class="[store.showSidebar ? 'ltr:hidden rtl:block' : 'ltr:block rtl:hidden']"
          class="h-5 w-5 cursor-pointer text-gray-500 hover:text-gray-700 ltr:mr-8 rtl:ml-8"
          @click="sidebarToggle"
        />
      </div>

      <div class="flex items-center px-8 ltr:ml-auto rtl:mr-auto">
        <ProfileDropdown class="ltr:ml-5 rtl:mr-5" />
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
  import { appData } from 'appdata'
  import { useStore } from 'Store/main'
  import { Square2StackIcon } from '@heroicons/vue/24/solid'
  import { Bars3BottomLeftIcon, Bars3BottomRightIcon } from '@heroicons/vue/24/outline'
  import ProfileDropdown from './ProfileDropdown.vue'

  const store = useStore()

  function sidebarToggle() {
    store.showSidebar = !store.showSidebar
  }
</script>
