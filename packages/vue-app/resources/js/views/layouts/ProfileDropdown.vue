<template>
  <Dropdown close-outside :close="closeDropdown" @toggle="onToggleDropdown">
    <template #trigger>
      <form id="logout-form" method="POST" action="/logout">
        <input type="hidden" name="_token" :value="csrf_token">
      </form>

      <button
        class="flex items-center text-sm font-medium text-gray-500 transition duration-150 ease-in-out hover:border-gray-300 hover:text-gray-700 focus:border-gray-300 focus:text-gray-700 focus:outline-hidden"
      >
        <UserAvatar :avatar="user.avatar" class="h-6 w-6" />

        <div class="ltr:ml-2 rtl:mr-2">
          {{ user.name }}
        </div>

        <div class="ltr:ml-1 rtl:mr-1">
          <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path
              fill-rule="evenodd"
              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
              clip-rule="evenodd"
            />
          </svg>
        </div>
      </button>
    </template>

    <template #content>
      <div
        class="absolute mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-hidden ltr:right-0 rtl:left-0"
        role="menu"
        aria-orientation="vertical"
        aria-labelledby="user-menu"
      >
        <div class="divide-y divide-gray-100">
          <p class="px-4 py-2.5">
            <span class="block text-xs font-normal text-gray-500">
              {{ __('Signed in as') }}
            </span>
            <span class="mt-0.5 block truncate text-sm font-normal text-gray-600" role="none">
              {{ user.email }}
            </span>
          </p>

          <div class="py-1" role="none">
            <RouterLink
              to="/profile"
              class="block px-4 py-2 text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden"
            >
              {{ __('Profile') }}
            </RouterLink>
            <RouterLink
              v-if="can('setting')"
              to="/settings/general"
              class="block px-4 py-2 text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden"
            >
              {{ __('Settings') }}
            </RouterLink>

            <a
              href="/logout"
              class="block px-4 py-2 text-sm text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100"
              @click.prevent="logout"
            >
              {{ __('Logout') }}
            </a>
          </div>
        </div>
      </div>
    </template>
  </Dropdown>
</template>

<script setup lang="ts">
  import { ref } from 'vue'
  import { appData } from 'appdata'
  import { Dropdown, UserAvatar } from 'thetheme'
  import { useRouter } from 'vue-router'

  const user = appData.user
  const csrf_token = appData.csrf_token
  const closeDropdown = ref(false)
  const router = useRouter()

  router.afterEach(() => (closeDropdown.value = true))

  function onToggleDropdown() {
    closeDropdown.value = false
  }

  function logout() {
    const form = document.querySelector('#logout-form') as HTMLFormElement

    form.submit()
  }
</script>
