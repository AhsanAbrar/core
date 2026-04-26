import { useConfirmStore, type ConfirmOptions } from '@stores/confirm'

export function useConfirmAction() {
  const confirm = useConfirmStore()

  function run(options: Omit<ConfirmOptions, 'onConfirm'>, action: () => Promise<void>) {
    return confirm.ask({
      ...options,
      onConfirm: action,
    })
  }

  return { run }
}
