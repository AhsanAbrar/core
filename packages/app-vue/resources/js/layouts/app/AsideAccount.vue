<template>
  <div class="relative">
    <button
      ref="btnRef"
      type="button"
      class="flex w-full items-center gap-3 rounded-xl px-2 py-2 text-left hover:bg-gray-50 dark:hover:bg-gray-900"
      @click="toggle"
      @keydown.esc.prevent="close"
    >
      <div
        class="grid size-9 place-items-center rounded-full bg-gray-200 text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-200"
      >
        CA
      </div>

      <div class="min-w-0 flex-1">
        <div class="truncate text-sm font-medium text-gray-900 dark:text-white">
          Codedot
        </div>
        <div class="truncate text-xs text-gray-500 dark:text-gray-400">
          codedott@gmail.com
        </div>
      </div>

      <ChevronUpIcon
        class="size-4 text-gray-500 transition-transform dark:text-gray-400"
        :class="open ? 'rotate-180' : ''"
        aria-hidden="true"
      />
    </button>

    <!-- Dropdown (opens upward) -->
    <div
      v-if="open"
      ref="menuRef"
      class="absolute bottom-full left-0 mb-2 w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-gray-950"
      role="menu"
      aria-label="Account menu"
    >
      <button
        type="button"
        class="flex w-full items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900"
        role="menuitem"
        @click="onItemClick"
      >
        <UserIcon class="size-4 text-gray-500 dark:text-gray-400" aria-hidden="true" />
        Profile
      </button>

      <button
        type="button"
        class="flex w-full items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900"
        role="menuitem"
        @click="onItemClick"
      >
        <Cog6ToothIcon class="size-4 text-gray-500 dark:text-gray-400" aria-hidden="true" />
        Settings
      </button>

      <div class="my-1 h-px bg-gray-200 dark:bg-gray-800" />

      <SignOutButton
        class="flex w-full items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30"
        role="menuitem"
        @click="close"
      >
        <PowerIcon class="size-4" aria-hidden="true" />
        Sign out
      </SignOutButton>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { onBeforeUnmount, onMounted, ref } from 'vue'
  import {
    ChevronUpIcon,
    UserIcon,
    Cog6ToothIcon,
    PowerIcon,
  } from '@heroicons/vue/24/outline'
  import SignOutButton from './SignOutButton.vue'

  const open = ref(false)

  const btnRef = ref<HTMLElement | null>(null)
  const menuRef = ref<HTMLElement | null>(null)

  function toggle() {
    open.value = !open.value
  }

  function close() {
    open.value = false
  }

  function onItemClick() {
    // you will wire routes/actions later
    close()
  }

  function onClickOutside(e: MouseEvent) {
    if (!open.value) return
    const target = e.target as Node
    const insideButton = btnRef.value?.contains(target) ?? false
    const insideMenu = menuRef.value?.contains(target) ?? false
    if (!insideButton && !insideMenu) close()
  }

  onMounted(() => {
    window.addEventListener('mousedown', onClickOutside)
  })

  onBeforeUnmount(() => {
    window.removeEventListener('mousedown', onClickOutside)
  })
</script>
