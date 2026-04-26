<template>
  <div class="card">
    <table class="w-full table-fixed">
      <!-- Header -->
      <thead
        class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900"
      >
        <tr>
          <th
            class="px-6 py-3 text-left text-xs font-medium tracking-wide text-gray-500 uppercase dark:text-gray-400"
          >
            {{ __('User') }}
          </th>
          <th
            class="w-[10rem] px-6 py-3 text-right text-xs font-medium tracking-wide text-gray-500 uppercase dark:text-gray-400"
          >
            &nbsp;
          </th>
        </tr>
      </thead>

      <!-- Body -->
      <tbody
        class="divide-y divide-gray-100 bg-white dark:divide-gray-800 dark:bg-gray-950"
      >
        <tr
          v-for="user in users"
          :key="user.id"
          class="transition hover:bg-gray-50 dark:hover:bg-gray-900/50"
        >
          <!-- User -->
          <td class="px-6 py-4">
            <div class="flex items-center gap-3">
              <div
                class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 font-medium text-gray-500 uppercase dark:bg-gray-800 dark:text-gray-400"
              >
                {{ user.name.charAt(0) }}
              </div>
              <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ user.name }}
                </span>
                <span class="text-xs text-gray-500 dark:text-gray-400">
                  {{ user.email }}
                </span>
              </div>
            </div>
          </td>

          <!-- Actions -->
          <td class="px-6 py-4">
            <div class="flex justify-end gap-4">
              <!-- Edit (always) -->
              <button
                type="button"
                class="text-gray-500 hover:text-gray-900"
                aria-label="Edit"
                data-cooltipz-dir="top"
                @click="emit('edit', user)"
              >
                <PencilIcon class="size-4" />
              </button>

              <!-- Remove -->
              <button
                class="text-gray-500 hover:text-red-500"
                aria-label="Delete"
                data-cooltipz-dir="top"
                @click="emit('remove', user)"
              >
                <TrashIcon class="size-4" />
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
  import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline'
  import type { User } from '@features/users/types'

  defineProps<{
    users: User[]
  }>()

  const emit = defineEmits<{
    (e: 'edit', user: User): void
    (e: 'remove', user: User): void
  }>()
</script>
