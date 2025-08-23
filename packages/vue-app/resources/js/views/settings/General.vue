<template>
  <SettingsLayout>
    <FormBase name="setting-general" uri="settings/general" title="General" save-only>
      <FieldText name="app_name" :label="__('App Name')" inline />
      <FieldText name="app_url" :label="__('App URL')" inline />
      <FieldSelect
        name="app_locale"
        :label="__('Language')"
        translatable
        input-class="max-w-lg sm:max-w-xs"
        inline
      />
      <FieldSelect
        name="app_timezone"
        :label="__('Timezone')"
        input-class="max-w-lg sm:max-w-xs"
        inline
      />
      <FieldSelect
        name="app_direction"
        :label="__('Direction')"
        input-class="max-w-lg sm:max-w-xs"
        inline
      />
      <FieldLogo />
    </FormBase>
  </SettingsLayout>
</template>

<script setup lang="ts">
  import { useFormStore } from 'ahsandevs'
  import { FieldSelect, FieldText, FormBase } from 'thetheme'
  import SettingsLayout from './SettingsLayout.vue'
  import FieldLogo from './FieldLogo.vue'

  const form = useFormStore('setting-general')()

  form.onSuccess(() => location.reload())
</script>
