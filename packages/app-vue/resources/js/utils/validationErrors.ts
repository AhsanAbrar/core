/**
 * Laravel typically returns validation errors like:
 * { errors: { title: ["The title field is required."], status: ["..."] } }
 *
 * We map it to: { title: "The title field is required.", status: "..." }
 */
export function pickFirstFieldErrors<TForm extends Record<string, any>>(
  bag: Record<string, string[] | undefined> | undefined,
): Partial<Record<keyof TForm, string>> {
  const out: Partial<Record<keyof TForm, string>> = {}
  if (!bag) return out

  for (const key in bag) {
    const first = bag[key]?.[0]
    if (first) (out as any)[key] = first
  }

  return out
}
