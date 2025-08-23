<template>
  <div class="grid border-b border-gray-200 py-5 last:border-b-0 sm:grid-cols-3">
    <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
      {{ __('Logo') }}
    </label>

    <div class="sm:col-span-2">
      <div class="flex">
        <span v-if="src" class="mr-2 flex h-[2.125rem] items-center bg-gray-900 px-4">
          <img class="block h-8" :src="src">
        </span>

        <label
          class="cursor-pointer rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-700 shadow-xs hover:bg-gray-50 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
          {{ __('Change') }}
          <input type="file" class="hidden" accept="image/*" name="logo" @change="onChoose">
        </label>

        <button
          v-if="src"
          type="button"
          class="ml-2 inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-700 shadow-xs hover:bg-gray-50 focus:outline-hidden focus:ring-0"
          @click="remove"
        >
          {{ __('Remove') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue'
  import { axios, useFormStore } from 'ahsandevs'

  const form = useFormStore('setting-general')(),
    width = 1600,
    height = 1200,
    src = ref<string | null>(null)

  src.value = form.data.app_logo ? `/${form.data.app_logo}` : null

  function remove() {
    src.value = null
    form.data.app_logo = null
  }

  function onChoose(e: Event) {
    if (!e.target.files.length) return

    let file = e.target.files[0]

    let target = e.target

    let reader = new FileReader()

    reader.readAsDataURL(file)

    reader.onload = (e) => {
      let img = new Image()

      img.src = e.target!.result

      img.onload = function () {
        if (img.width > width || img.height > height) {
          alert('you can upload max ' + width + ' x ' + height)

          target.value = null

          return
        }

        src.value = e.target!.result

        const formData = new FormData()
        formData.append('logo', file)

        axios.post('logo', formData).then(({ data }) => {
          form.data.app_logo = data
        })
      }
    }
  }
</script>
