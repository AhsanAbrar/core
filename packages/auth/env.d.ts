/// <reference types="vite/client" />

export {}

declare global {
  interface Window {
    Alpine: typeof import("alpinejs").default
  }
}
