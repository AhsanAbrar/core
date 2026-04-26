<template>
  <div class="px-2.5 py-2.5">
    <div class="flex items-center">
      <div>
        <RouterLink to="/" class="block rounded-md p-2 hover:bg-gray-50">
          <ArrowLeftIcon class="size-4 text-gray-700" />
        </RouterLink>
      </div>
      <div class="ml-2.5 truncate">
        <div class="text-lg font-semibold text-gray-900">
          Recruty <span class="text-sm font-normal">{{ __('Settings') }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ArrowLeftIcon } from '@heroicons/vue/24/outline'
</script>
