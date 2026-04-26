import { ref } from 'vue'
import { watchDebounced } from '@vueuse/core'
import type { User, UsersIndexParams } from '@features/users/types'
import type { Pagination } from '@types'
import { usersIndex, usersRemove } from '@features/users/api'
import { useConfirmAction } from '@composables/useConfirmAction'
import { useModalStore } from '@stores/modal'
import Form from '@features/users/pages/Form.vue'

export function useUsersIndexPage() {
  // First load – controls PageContainer overlay
  const initLoading = ref(true)

  // Subsequent loads – for light refresh (topbar, table, etc.)
  const loading = ref(false)

  const users = ref<User[]>([])
  const pagination = ref<Pagination>({} as Pagination)
  const params = ref<UsersIndexParams>({
    page: 1,
    search: '',
  })

  const { run: confirmRun } = useConfirmAction()

  /** Fetch users from API */
  async function fetch() {
    // Only use "loading" for non-initial fetches
    if (!initLoading.value) {
      loading.value = true
    }

    try {
      const response = await usersIndex(params.value)
      users.value = response.data
      pagination.value = response.pagination
    } finally {
      if (initLoading.value) {
        initLoading.value = false
      }

      loading.value = false
    }
  }

  // 🔥 Debounced watcher for search / filters / tabs
  watchDebounced(
    params,
    () => {
      params.value.page = 1
      fetch()
    },
    { debounce: 400, deep: true },
  )

  /**
   * Edit a user in the shared create/edit form.
   */
  function edit(user: User) {
    useModalStore().add(Form, {
      id: user.id,
      onSaved: fetch,
    })
  }

  /**
   * Remove a user with confirmation
   */
  async function remove(user: User) {
    await confirmRun(
      {
        title: 'Delete Resource',
        message: `Are you sure you want to delete this resource?`,
        confirmText: 'Delete',
        cancelText: 'Cancel',
        danger: true,
      },
      () => removeNow(user.id),
    )
  }

  /** Remove immediately */
  async function removeNow(id: number) {
    await usersRemove(id)
    // Remove user from list
    users.value = users.value.filter(u => u.id !== id)
  }

  return {
    initLoading,
    loading,
    users,
    pagination,
    params,
    fetch,
    edit,
    remove,
  }
}
