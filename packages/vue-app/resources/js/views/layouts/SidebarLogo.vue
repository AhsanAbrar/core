<template>
  <div v-if="logo" class="flex h-12 items-center border-b border-gray-700 px-5">
    <img :src="`/${logo}`" style="max-height: 2rem">
  </div>

  <RouterLink v-else to="/" class="flex h-12 items-center border-b border-gray-700 px-5">
    <Square2StackIcon class="h-6 w-6 text-blue-500" />
    <span class="text-lg font-semibold text-white ltr:ml-2.5 rtl:mr-2.5">
      {{ name }}
    </span>
  </RouterLink>
</template>

<script setup lang="ts">
  import { appData } from 'appdata'
  import { Square2StackIcon } from '@heroicons/vue/24/solid'

  const name = appData.app_name
  const logo = appData.header_logo
</script>
