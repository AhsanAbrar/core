import { defineConfig } from 'vite'
import { resolve } from 'path'
import ahsandevs from 'ahsandevs-vite-plugin'

export default defineConfig({
  plugins: [
    ahsandevs(),
  ],
  resolve: {
    alias: {
      '@': resolve(__dirname, 'resources/js'),
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
