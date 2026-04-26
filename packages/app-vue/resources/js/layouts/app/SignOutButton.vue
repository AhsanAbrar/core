<template>
  <button type="button" :class="classes" @click="signOut">
    <slot> Sign out </slot>
  </button>
</template>

<script setup lang="ts">
  import { computed, ref } from 'vue'
  import { appData } from '@app-data'

  type Props = {
    csrfToken?: string
    action?: string
    disabled?: boolean
    class?: string
  }

  const props = withDefaults(defineProps<Props>(), {
    action: '/logout',
    disabled: false,
  })

  const submitting = ref(false)

  const classes = computed(() => {
    // default classes can be overridden by passing class=""
    return props.class ?? ''
  })

  function signOut() {
    if (props.disabled || submitting.value) return

    submitting.value = true

    const form = document.createElement('form')
    form.method = 'POST'
    form.action = props.action
    form.style.display = 'none'

    const token = document.createElement('input')
    token.type = 'hidden'
    token.name = '_token'
    token.value = appData.csrf_token

    form.appendChild(token)
    document.body.appendChild(form)
    form.submit()
  }
</script>
