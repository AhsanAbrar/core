import { dirname, resolve } from 'node:path'
import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'
import spanvel from 'spanvel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'

const __dirname = dirname(fileURLToPath(import.meta.url))

export default defineConfig({
  plugins: [
    spanvel(),
    vue(),
    vueDevTools()
  ],
  server: {
    cors: true
  },
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
      'appdata': fileURLToPath(new URL('./resources/js/app-data.ts', import.meta.url)),
      'types': fileURLToPath(new URL('./resources/js/types/index.d.ts', import.meta.url)),
      'Component': fileURLToPath(new URL('./resources/js/components', import.meta.url)),
      'Store': fileURLToPath(new URL('./resources/js/stores', import.meta.url)),
      'Use': fileURLToPath(new URL('./resources/js/composables', import.meta.url)),
      'View': fileURLToPath(new URL('./resources/js/views', import.meta.url)),
    },
  },
  build: {
    manifest: true,
    emptyOutDir: true,
    outDir: resolve(__dirname, '../../public/vendor/[[name]]'),
    rollupOptions: {
      input: resolve(__dirname, 'resources/js/main.ts'),
    },
  },
})
