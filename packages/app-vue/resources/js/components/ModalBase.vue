<template>
  <div
    class="fixed inset-0 z-[250] overflow-y-auto bg-gray-500/75 py-12 px-8"
    @click="hide"
  >
    <div
      class="modal-content mx-auto rounded-lg bg-white shadow-xl"
      :class="[width]"
      @click.stop
    >
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
  import { useModalStore } from '@stores/modal'

  const props = withDefaults(
    defineProps<{
      stopHide?: boolean
      width?: string
    }>(),
    {
      width: 'max-w-2xl',
    },
  )

  function hide() {
    if (props.stopHide) return

    useModalStore().pop()
  }
</script>
