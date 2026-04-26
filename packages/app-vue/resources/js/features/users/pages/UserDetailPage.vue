<template>
  <PageContainer>
    <!-- Page header -->
    <header class="mb-6 flex items-center">
      <div>
        <h1 class="page-title">{{ __('Users') }}</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ __('Manage who can access your Recruty workspace.') }}
        </p>
      </div>
    </header>
  </PageContainer>
</template>

<script setup lang="ts">
  import PageContainer from '@components/PageContainer.vue'
</script>
