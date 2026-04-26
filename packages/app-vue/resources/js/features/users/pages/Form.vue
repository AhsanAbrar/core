<template>
  <FormModal
    :title="isEdit ? __('Edit User') : __('Create User')"
    :submit-text="isEdit ? __('Update User') : __('Create User')"
    :loading="initLoading"
    :submitting="submitting"
    @submit="save"
  >
    <div class="col-span-12">
      <label class="form-label" for="user-name">{{ __('Name') }}</label>
      <input
        id="user-name"
        v-model="form.name"
        type="text"
        class="form-input"
        :class="{ 'form-control--error': errors.name?.[0] }"
        autocomplete="name"
      />
      <p v-if="errors.name?.[0]" class="form-error">
        {{ errors.name[0] }}
      </p>
    </div>

    <div class="col-span-12">
      <label class="form-label" for="user-email">{{ __('Email') }}</label>
      <input
        id="user-email"
        v-model="form.email"
        type="email"
        class="form-input"
        :class="{ 'form-control--error': errors.email?.[0] }"
        autocomplete="email"
      />
      <p v-if="errors.email?.[0]" class="form-error">
        {{ errors.email[0] }}
      </p>
    </div>

    <div class="col-span-12">
      <label class="form-label" for="user-password">
        {{ isEdit ? __('New Password') : __('Password') }}
      </label>
      <input
        id="user-password"
        v-model="form.password"
        type="password"
        class="form-input"
        :class="{ 'form-control--error': errors.password?.[0] }"
        autocomplete="new-password"
      />
      <p v-if="errors.password?.[0]" class="form-error">
        {{ errors.password[0] }}
      </p>
    </div>
  </FormModal>
</template>

<script setup lang="ts">
  import FormModal from '@/components/FormModal.vue'
  import { useUserFormPage } from '@features/users/composables/useUserFormPage'
  import { useModalStore } from '@stores/modal'

  const props = defineProps<{
    id?: number
    onSaved?: () => void
  }>()

  const { isEdit, initLoading, submitting, form, errors, submit } = useUserFormPage(props.id)

  async function save() {
    const saved = await submit()

    if (!saved) return

    props.onSaved?.()
    useModalStore().pop()
  }
</script>
