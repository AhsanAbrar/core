import { defineConfig } from 'vite'
import { resolve } from 'path'
import ahsandevs from 'ahsandevs-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [
    ahsandevs(),
    vue(),
  ],
  server: {
    cors: true
  },
  resolve: {
    alias: {
      '@': resolve(__dirname, 'resources/js'),
      'appdata': resolve(__dirname, 'resources/js/app-data.ts'),
      'types': resolve(__dirname, 'resources/js/types/index.d.ts'),
      'Component': resolve(__dirname, 'resources/js/components'),
      'Store': resolve(__dirname, 'resources/js/stores'),
      'Use': resolve(__dirname, 'resources/js/composables'),
      'View': resolve(__dirname, 'resources/js/views'),
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
