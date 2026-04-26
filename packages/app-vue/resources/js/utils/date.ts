/**
 * Format ISO datetime into something readable for UI.
 */
export function formatDateTime(value: string | null | undefined) {
  if (!value) return ''

  const date = new Date(value)

  return date.toLocaleString(undefined, {
    year: 'numeric',
    month: 'short',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  })
}
