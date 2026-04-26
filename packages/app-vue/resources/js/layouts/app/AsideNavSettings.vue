<template>
  <nav class="space-y-1">
    <RouterLink
      v-for="item in navItems"
      :key="item.name"
      :to="{ name: item.name }"
      custom
      v-slot="{ href, navigate, isActive }"
    >
      <a
        :href="href"
        @click="navigate"
        class="group flex items-center gap-3 rounded-lg px-3.5 py-2 text-sm transition"
        :class="
          isActive
            ? 'bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white'
            : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-900 dark:hover:text-white'
        "
      >
        <span class="truncate">{{ item.label }}</span>
      </a>
    </RouterLink>
  </nav>
</template>

<script setup lang="ts">
  const navItems = [
    { name: 'settings.general', label: 'General' },
    { name: 'users.index', label: 'Users' },
  ]
</script>
