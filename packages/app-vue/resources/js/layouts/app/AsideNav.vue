<template>
  <nav class="space-y-1">
    <AsideNavItem
      v-for="item in navItems"
      :key="item.label"
      :to="item.to"
      :label="item.label"
    >
      <component :is="item.icon" class="size-4" aria-hidden="true" />
    </AsideNavItem>
  </nav>
</template>

<script setup lang="ts">
  import AsideNavItem from './AsideNavItem.vue'
  import { HomeIcon, UsersIcon } from '@heroicons/vue/24/outline'

  const navItems = [
    { to: { name: 'dashboard' }, label: 'Dashboard', icon: HomeIcon },
    { to: { name: 'users.index' }, label: 'Users', icon: UsersIcon },
  ]
</script>
