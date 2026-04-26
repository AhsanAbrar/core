import { ref, computed, onMounted } from 'vue'
import type { UserFormData, UserFormFieldsResponse } from '@features/users/types'
import { usersCreate, usersEdit, usersStore, usersUpdate } from '@features/users/api'

export function useUserFormPage(id?: string | number) {
  const isEdit = computed(() => !!id)

  // Loading & submitting states
  const initLoading = ref(true)
  const submitting = ref(false)

  // Form state
  const form = ref<UserFormData>({
    name: '',
    email: '',
    password: '',
  })

  // Validation errors
  const errors = ref<Record<string, string[]>>({})

  const options = ref<UserFormFieldsResponse['options']>({})

  // Initialize form on mount
  onMounted(init)

  /** Fetch form options & data for edit */
  async function init() {
    errors.value = {}

    try {
      const response: UserFormFieldsResponse = isEdit.value
        ? await usersEdit(id as number)
        : await usersCreate()

      form.value = {
        ...form.value,
        name: response.data?.name ?? '',
        email: response.data?.email ?? '',
        password: '',
      }

      options.value = response.options
    } finally {
      initLoading.value = false
    }
  }

  /** Submit form to API */
  async function submit() {
    if (submitting.value) return
    submitting.value = true
    errors.value = {}

    try {
      if (isEdit.value) {
        await usersUpdate(id as number, form.value)
      } else {
        await usersStore(form.value)
      }

      return true
    } catch (e: any) {
      // Laravel validation errors (422)
      if (e?.response?.status === 422 && e.response.data?.errors) {
        errors.value = e.response.data.errors
        return false
      }

      throw e
    } finally {
      submitting.value = false
    }
  }

  return {
    isEdit,
    initLoading,
    submitting,
    form,
    errors,
    options,
    submit,
  }
}
