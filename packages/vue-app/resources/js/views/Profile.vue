<template>
  <PageContainer>
    <h1 class="mb-4 text-2xl font-semibold text-gray-900">
      {{ __('Profile') }}
    </h1>

    <FormBase name="profile" uri="profile" save-only>
      <FieldAvatar />
      <FieldText name="name" :label="__('Name')" inline />
      <FieldText name="email" :label="__('Email')" inline disabled />
      <FieldText name="password" :label="__('New Password')" type="password" inline />
    </FormBase>
  </PageContainer>
</template>

<script setup lang="ts">
  import { useFormStore } from 'ahsandevs'
  import { FieldText, FormBase, PageContainer } from 'thetheme'
  import FieldAvatar from 'Component/FieldAvatar.vue'

  const form = useFormStore('profile')()

  form.onSuccess(() => {
    form.submitting = false
    form.data.password = ''
  })
</script>
