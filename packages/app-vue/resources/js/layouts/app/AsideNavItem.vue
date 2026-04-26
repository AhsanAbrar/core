<template>
  <RouterLink
    :to="to"
    class="group flex items-center gap-3 rounded-lg px-3.5 py-2 text-sm transition"
    :class="
      isActive
        ? 'bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white'
        : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-900 dark:hover:text-white'
    "
  >
    <span
      class="inline-flex items-center justify-center rounded-lg"
      :class="
        isActive
          ? 'text-accent-600 dark:text-accent-400'
          : 'text-gray-500 group-hover:text-gray-700 dark:text-gray-400 dark:group-hover:text-gray-200'
      "
    >
      <slot />
    </span>

    <span class="truncate">{{ label }}</span>
  </RouterLink>
</template>

<script setup lang="ts">
  import { computed } from 'vue'
  import type { RouteLocationRaw } from 'vue-router'
  import { RouterLink, useRoute, useRouter } from 'vue-router'

  const props = defineProps<{
    to: RouteLocationRaw
    label: string
  }>()

  const route = useRoute()
  const router = useRouter()

  const resolved = computed(() => router.resolve(props.to))

  const isActive = computed(() => {
    const current = route.path
    const target = resolved.value.path

    if (target === '/') {
      return current === '/'
    }

    return current === target || current.startsWith(target + '/')
  })
</script>
