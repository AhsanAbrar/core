<template>
  <ModalBase stop-hide>
    <FormModalSkeleton v-if="loading" />

    <section v-else>
      <div class="flex items-center px-6 pt-6">
        <h1 class="text-xl font-semibold text-gray-900" data-cy="page-title">
          {{ title }}
        </h1>
        <span
          class="block h-7 w-7 cursor-pointer rounded-md p-0.5 hover:bg-gray-100 ltr:ml-auto rtl:mr-auto"
          @click="useModalStore().pop()"
        >
          <XMarkIcon class="text-gray-400" />
        </span>
      </div>

      <div class="px-6">
        <form
          class="grid grid-cols-12 gap-6 pb-6 pt-3"
          novalidate
          @submit.prevent="emit('submit')"
        >
          <slot />
        </form>
      </div>

      <div class="bottom-0 flex justify-end rounded-b-lg bg-gray-50 px-6 py-5">
        <button
          type="button"
          class="btn-secondary ltr:mr-3 rtl:ml-3"
          :disabled="submitting"
          @click="useModalStore().pop()"
        >
          {{ __('Cancel') }}
        </button>

        <button
          type="button"
          class="btn-primary"
          data-cy="submit-button"
          :disabled="submitting"
          @click="emit('submit')"
        >
          {{ submitText }}
        </button>
      </div>
    </section>
  </ModalBase>
</template>

<script setup lang="ts">
  import ModalBase from './ModalBase.vue'
  import FormModalSkeleton from './FormModalSkeleton.vue'
  import { useModalStore } from '@stores/modal'
  import { XMarkIcon } from '@heroicons/vue/24/outline'

  withDefaults(
    defineProps<{
      title: string
      submitText: string
      loading?: boolean
      submitting?: boolean
    }>(),
    {
      loading: false,
      submitting: false,
    },
  )

  const emit = defineEmits<{
    (e: 'submit'): void
  }>()
</script>
