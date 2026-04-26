<template>
  <div class="px-[1.375rem] py-2.5">
    <RouterLink to="/" class="flex items-center">
      <img :src="`/${appData.header_logo}`" class="mr-3 h-5" v-if="appData.header_logo" />
      <div class="text-lg font-semibold text-gray-900">{{ appData.app_name }}</div>
    </RouterLink>
  </div>
</template>

<script setup lang="ts">
  import { appData } from '@app-data'
  import { RouterLink } from 'vue-router'
</script>
