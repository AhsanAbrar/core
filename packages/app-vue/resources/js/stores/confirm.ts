import { defineStore } from 'pinia'
import { computed, ref } from 'vue'

export type ConfirmOptions = {
  title?: string
  message?: string
  confirmText?: string
  cancelText?: string
  danger?: boolean
  onConfirm?: () => Promise<void>
}

export const useConfirmStore = defineStore('confirm', () => {
  const open = ref(false)
  const loading = ref(false)

  const title = ref('Confirm')
  const message = ref('Are you sure?')
  const confirmText = ref('Confirm')
  const cancelText = ref('Cancel')
  const danger = ref(false)

  const onConfirm = ref<null | (() => Promise<void>)>(null)

  let resolver: ((v: boolean) => void) | null = null

  const buttonClass = computed(() =>
    danger.value
      ? 'rounded-md bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-500 disabled:opacity-50'
      : 'rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-800 disabled:opacity-50',
  )

  function reset() {
    title.value = 'Confirm'
    message.value = 'Are you sure?'
    confirmText.value = 'Confirm'
    cancelText.value = 'Cancel'
    danger.value = false
    onConfirm.value = null
    loading.value = false
  }

  function ask(options: ConfirmOptions = {}) {
    reset()

    title.value = options.title ?? title.value
    message.value = options.message ?? message.value
    confirmText.value = options.confirmText ?? confirmText.value
    cancelText.value = options.cancelText ?? cancelText.value
    danger.value = options.danger ?? danger.value
    onConfirm.value = options.onConfirm ?? null

    open.value = true

    return new Promise<boolean>(resolve => {
      resolver = resolve
    })
  }

  function close(result: boolean) {
    if (loading.value) return
    open.value = false
    resolver?.(result)
    resolver = null
    reset()
  }

  async function confirm() {
    if (loading.value) return

    loading.value = true
    try {
      if (onConfirm.value) await onConfirm.value()

      // IMPORTANT: allow close
      loading.value = false
      close(true)

      return true
    } catch (e) {
      loading.value = false
      throw e
    }
  }

  return {
    open,
    loading,
    title,
    message,
    confirmText,
    cancelText,
    danger,
    buttonClass,

    ask,
    close,
    confirm,
  }
})
