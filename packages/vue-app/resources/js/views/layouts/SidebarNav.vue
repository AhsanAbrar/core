<template>
  <div class="space-y-1 px-2 py-4">
    <template v-for="item in useSidebarNav" :key="item.label">
      <RouterLink
        v-if="!item.permission || can(item.permission)"
        v-slot="{ href, navigate }"
        custom
        :to="item.uri"
      >
        <a
          :href="href"
          :class="[
            isActive(item.uri)
              ? 'bg-gray-900 text-white'
              : 'text-gray-300 hover:bg-gray-700 hover:text-white',
            'group flex items-center rounded-md py-2 text-sm font-medium ltr:pl-3 rtl:pr-3',
          ]"
          @click="navigate"
        >
          <Component
            :is="item.icon"
            :class="[
              isActive(item.uri) ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300',
              'h-5 w-5 shrink-0 ltr:mr-3 rtl:ml-3',
            ]"
            aria-hidden="true"
          />
          {{ __(item.label) }}

          <span
            v-if="item.create && can(item.createPermission || 'false')"
            class="hover:opacity-250 flex h-5 w-5 cursor-pointer items-center justify-center rounded-sm bg-gray-700 text-white hover:bg-gray-800 hover:text-gray-300 group-hover:bg-gray-500 ltr:ml-auto rtl:mr-auto"
            @click.prevent="create(item.create || '/')"
          >
            <Component :is="PlusIcon" class="h-3.5 w-3.5" />
          </span>
        </a>
      </RouterLink>
    </template>
  </div>
</template>

<script setup lang="ts">
  import { computed } from 'vue'
  import { useSidebarNav } from 'Use/sidebar-nav'
  import { useRouter } from 'vue-router'
  import { PlusIcon } from '@heroicons/vue/24/outline'

  const router = useRouter()
  const path = computed(() => router.currentRoute.value.path)

  function isActive(uri: string) {
    if (uri === '/') {
      return path.value === uri
    }

    return path.value.startsWith(uri)
  }

  function create(uri: string) {
    router.push(uri)
  }
</script>
