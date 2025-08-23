<template>
  <FormModal :id="id" :name="name" uri="users">
    <FieldText name="name" :label="__('Name')" class="col-span-12" />
    <FieldText name="email" :label="__('Email')" class="col-span-12" />
    <FieldText name="password" :label="__('Password')" class="col-span-12" />
  </FormModal>
</template>

<script setup lang="ts">
  import { FieldText, FormModal } from 'thetheme'
  import { useFormStore, useModalStore, useIndexStore } from 'ahsandevs'

  defineProps<{
    id?: number
  }>()

  const name = 'user'
  const form = useFormStore(name)()
  const index = useIndexStore<User>(name)()

  form.onSuccess((response) => {
    index.updateOrCreate(response.model)

    useModalStore().pop()
  })
</script>
