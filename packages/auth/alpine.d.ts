declare module "alpinejs" {
  const Alpine: {
    start(): void
    plugin(plugin: unknown): void
    store(name: string, value: unknown): void
    data(name: string, callback: () => Record<string, unknown>): void
    [key: string]: any
  }
  export default Alpine
}
