import ts from 'typescript-eslint'
import vueParser from 'vue-eslint-parser'
import tsParser from '@typescript-eslint/parser'
import vuePlugin from 'eslint-plugin-vue'

export default [
  ...ts.configs.recommended,
  ...vuePlugin.configs['flat/recommended'],
  {
    files: ['resources/js/**/*.{js,ts,tsx,vue}'],
    languageOptions: {
      parser: vueParser,
      parserOptions: {
        parser: tsParser,
        sourceType: 'module',
      },
    },
    rules: {
      semi: ['error', 'never'],
      quotes: ['error', 'single'],
      'vue/max-attributes-per-line': 'off',
    },
  },
]
