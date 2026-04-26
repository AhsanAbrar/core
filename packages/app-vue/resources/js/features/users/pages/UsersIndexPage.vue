<template>
  <!-- Only first load shows full page loader -->
  <PageContainer :loading="initLoading">
    <!-- Page header -->
    <header class="mb-6 flex items-center">
      <div>
        <h1 class="page-title">{{ __('Users') }}</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ __('Manage who can access your Recruty workspace.') }}
        </p>
      </div>

      <div class="ml-auto">
        <button
          type="button"
          class="btn-primary"
          @click="useModalStore().add(Form, { onSaved: fetch })"
        >
          {{ __('Create User') }}
        </button>
      </div>
    </header>

    <!-- Filters & Actions -->
    <div class="mb-6 flex">
      <div class="w-3xs">
        <input
          v-model="params.search"
          type="search"
          class="form-input"
          placeholder="Search..."
        />
      </div>

      <div class="ml-auto">
        &nbsp;
      </div>
    </div>

    <!-- Users table -->
    <UsersTable
      v-if="users.length"
      :users="users"
      @edit="edit"
      @remove="remove"
    />

    <!-- Empty state -->
    <UsersEmptyState v-else />
  </PageContainer>
</template>

<script setup lang="ts">
  import PageContainer from '@components/PageContainer.vue'
  import UsersTable from '@features/users/components/UsersTable.vue'
  import UsersEmptyState from '@features/users/components/UsersEmptyState.vue'
  import { useUsersIndexPage } from '@features/users/composables/useUsersIndexPage'
  import { useModalStore } from '@stores/modal'
  import Form from './Form.vue'

  const { initLoading, users, params, fetch, edit, remove } = useUsersIndexPage()

  // Initial fetch
  fetch()
</script>
