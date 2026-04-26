export {}

declare global {
  interface Window {
    AppData: AppData
  }
}

declare module 'vue' {
  interface ComponentCustomProperties {
    __: (word: string) => string
    can: (permission: string) => boolean
    cannot: (permission: string) => boolean
  }
}
