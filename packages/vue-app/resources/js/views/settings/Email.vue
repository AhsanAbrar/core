<template>
  <SettingsLayout>
    <FormBase name="setting-email" uri="settings/email" title="Email" save-only>
      <FieldSelect
        name="mail_driver"
        :label="__('Driver')"
        input-class="max-w-lg sm:max-w-xs"
        inline
      />
      <FieldText name="mail_host" :label="__('Host')" inline />
      <FieldText name="mail_port" :label="__('Port')" inline />
      <FieldText name="mail_username" :label="__('Username')" inline />
      <FieldText name="mail_password" :label="__('Password')" inline />
      <FieldSelect
        name="mail_encryption"
        :label="__('Encryption')"
        input-class="max-w-lg sm:max-w-xs"
        inline
      />
      <FieldText name="mail_from_address" :label="__('From Address')" inline />
      <FieldText name="mail_from_name" :label="__('From Name')" inline />
      <FieldCheckbox name="mail_verify_peer" :label="__('Verify Peer')" inline />
    </FormBase>
  </SettingsLayout>
</template>

<script setup lang="ts">
  import { useFormStore } from 'ahsandevs'
  import { FieldCheckbox, FieldSelect, FieldText, FormBase } from 'thetheme'
  import SettingsLayout from './SettingsLayout.vue'

  const form = useFormStore('setting-email')()

  form.onSuccess(() => location.reload())
</script>
