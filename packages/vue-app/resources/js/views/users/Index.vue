<template>
  <PageContainer :loading="index.initializing">
    <div class="mb-4 flex">
      <h1 class="text-2xl font-semibold text-gray-900">
        {{ __('Users') }}
      </h1>

      <div class="flex ltr:ml-auto rtl:mr-auto">
        <button
          v-if="can('user:create')"
          type="button"
          class="rounded-md bg-blue-600 px-2.5 py-1.5 text-sm font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 ltr:ml-4 rtl:mr-4"
          @click="useModalStore().add(Form)"
        >
          {{ __('Create User') }}
        </button>
      </div>
    </div>

    <IndexTable :name="name">
      <TableRow v-for="row in index.data" :key="row.id" :row="row">
        <TableColumn field="id" sortable />
        <TableColumn field="name" sortable />
        <TableColumn field="email" sortable />
        <TableColumnActions :edit="Form" delete-it />
      </TableRow>
    </IndexTable>
  </PageContainer>
</template>

<script setup lang="ts">
  import { useIndexStore, useModalStore } from 'ahsandevs'
  import { PageContainer, IndexTable, TableRow, TableColumn, TableColumnActions } from 'thetheme'
  import Form from './Form.vue'

  const name = 'user'
  const index = useIndexStore<User>(name)()

  index.init({ uri: 'users' })
</script>
