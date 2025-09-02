import { dirname, resolve } from 'node:path'
import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'
import spanvel from 'spanvel-vite-plugin'

const __dirname = dirname(fileURLToPath(import.meta.url))

export default defineConfig({
  plugins: [
    spanvel(),
  ],
  server: {
    cors: true
  },
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
    },
  },
  build: {
    manifest: true,
    emptyOutDir: true,
    outDir: resolve(__dirname, '../../public/vendor/auth'),
    rollupOptions: {
      input: resolve(__dirname, 'resources/js/main.ts'),
    },
  },
})
