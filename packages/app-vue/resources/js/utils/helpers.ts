/**
 * Check if object is empty.
 */
export function isObjectEmpty(obj: Record<string, unknown>): boolean {
  return Object.keys(obj).length === 0
}

/**
 * Converts a string to title case by replacing underscores and dashes with spaces
 * and capitalizing the first letter of each word.
 */
export function formatToTitleCase(str: string): string {
  return str.replace(/[_-]/g, ' ').replace(/\b\w/g, letter => letter.toUpperCase())
}

/**
 * Retrieves a value from a nested object or array using a dot-separated path.
 * Supports array indices in the path (e.g., `items[0]`).
 * Returns a default value if the path is not found.
 *
 * @param obj - The object or array to query.
 * @param path - The dot-separated path to the value, with optional array indices.
 * @param defaultValue - The value to return if the path is not found.
 * @returns The value at the specified path, or `defaultValue` if not found.
 *
 * @example
 * const data = { items: ['a', 'b'], details: { id: 1 } }
 * getNestedValue(data, 'items[1]', 'default') // 'b'
 * getNestedValue(data, 'details.id', 'default') // 1
 * getNestedValue(data, 'non.existent.path', 'default') // 'default'
 */
export function getNestedValue(
  obj: Record<string, unknown> | unknown[],
  path: string,
  defaultValue: unknown = undefined,
): unknown {
  const keys = path.split('.').flatMap((key): Array<string | number> => {
    const match = key.match(/^(\w+)\[(\d+)\]$/)

    if (!match) return [key]

    const [, name, index] = match

    return [name!, Number(index)]
  })

  return keys.reduce<unknown>((acc, key) => {
    if (acc === null || acc === undefined) return defaultValue

    if (Array.isArray(acc) && typeof key === 'number') {
      return acc[key] ?? defaultValue
    }

    if (typeof acc === 'object' && key in acc) {
      return (acc as Record<string, unknown>)[String(key)]
    }

    return defaultValue
  }, obj)
}

/**
 * Generates a random string of the specified length.
 */
export function generateRandomString(length: number = 10): string {
  const charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'

  let result = ''

  for (let i = 0; i < length; i++) {
    const randomIndex = Math.floor(Math.random() * charset.length)

    result += charset[randomIndex]
  }

  return result
}
