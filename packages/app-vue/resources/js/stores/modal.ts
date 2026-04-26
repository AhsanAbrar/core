import { defineStore } from 'pinia'
import { markRaw, ref } from 'vue'
import type { Component } from 'vue'

export const useModalStore = defineStore('modals', () => {
  const components = ref<{ component: Component; payload: any }[]>([])
  const hideHook = ref(() => {})

  function add(component: Component, payload: any = null) {
    payload = cleanupPayload(payload)

    components.value.push({
      component: markRaw(component),
      payload,
    })
  }

  function remove(name: string) {
    // @ts-ignore
    const index = components.value.findIndex((x) => x.component.__name == name)
    components.value.splice(index)
    hideHook.value()
  }

  function pop() {
    components.value.pop()
    hideHook.value()
  }

  function onHide(cb: () => void) {
    hideHook.value = cb
  }

  function cleanupPayload(payload: any) {
    if (payload === null) return null

    const newPayload: any = {}

    for (const [key, value] of Object.entries(payload)) {
      newPayload[key] = value instanceof Event ? null : value
    }

    return newPayload
  }

  return {
    add,
    components,
    pop,
    remove,
    onHide,
  }
})
