<template>
  <Teleport to="body">
    <Transition name="modal">
      <div
        v-if="confirm.open"
        class="fixed inset-0 z-50 flex items-center justify-center"
        role="dialog"
        aria-modal="true"
      >
        <div class="absolute inset-0 bg-black/40" @click="confirm.close(false)" />

        <div
          class="modal-content relative w-full max-w-md rounded-lg bg-white p-5 shadow-lg"
        >
          <h3 class="text-sm font-semibold text-gray-900">{{ confirm.title }}</h3>

          <p class="mt-2 text-sm text-gray-600">{{ confirm.message }}</p>

          <div class="mt-5 flex items-center justify-end gap-2">
            <button
              type="button"
              class="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50"
              :disabled="confirm.loading"
              @click="confirm.close(false)"
            >
              {{ confirm.cancelText }}
            </button>

            <button
              type="button"
              :class="confirm.buttonClass"
              :disabled="confirm.loading"
              @click="confirm.confirm()"
            >
              <span v-if="confirm.loading">Please wait...</span>
              <span v-else>{{ confirm.confirmText }}</span>
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
  import { useConfirmStore } from '@stores/confirm'

  const confirm = useConfirmStore()
</script>
