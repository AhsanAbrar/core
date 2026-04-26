<template>
  <section class="card">
    <div class="flex flex-col items-center justify-center px-6 py-12 text-center">
      <!-- Icon -->
      <div
        class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-600 ring-1 ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700"
      >
        <UserIcon class="size-6" aria-hidden="true" />
      </div>

      <!-- Title -->
      <h3 class="text-base font-medium text-gray-900 dark:text-white">
        {{ __('No users yet') }}
      </h3>

      <!-- Description -->
      <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
        {{ __('You haven’t added any users yet.') }}
      </p>

      <!-- Action -->
      <div class="mt-6">
        <RouterLink :to="{ name: 'users.create' }" class="btn-primary btn-sm">
          {{ __('Add user') }}
        </RouterLink>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
  import { UserIcon } from '@heroicons/vue/24/outline';
</script>
