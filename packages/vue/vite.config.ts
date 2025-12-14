import { dirname, resolve } from 'node:path'
import { fileURLToPath } from 'node:url'

import { defineConfig } from 'vite'
import spanvel from 'spanvel-vite-plugin'
import vue from '@vitejs/plugin-vue'

const __dirname = dirname(fileURLToPath(import.meta.url))

export default defineConfig({
  plugins: [
    spanvel(),
    vue(),
  ],
  base: '',
  server: {
    cors: true
  },
  resolve: {
    alias: {
      '@': resolve(__dirname, 'resources/js'),
      '@app-data': resolve(__dirname, 'resources/js/app-data.ts'),
      '@components': resolve(__dirname, 'resources/js/components'),
      '@composables': resolve(__dirname, 'resources/js/composables'),
      '@features': resolve(__dirname, 'resources/js/features'),
      '@layouts': resolve(__dirname, 'resources/js/layouts'),
      '@services': resolve(__dirname, 'resources/js/services'),
      '@stores': resolve(__dirname, 'resources/js/stores'),
      '@types': resolve(__dirname, 'resources/js/types/index.d.ts'),
      '@ui': resolve(__dirname, 'resources/js/ui'),
      '@utils': resolve(__dirname, 'resources/js/utils'),
      '@views': resolve(__dirname, 'resources/js/views'),
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
