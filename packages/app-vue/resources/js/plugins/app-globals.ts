import type { App } from 'vue'
import { __, can, cannot } from '@utils/app'

export const appGlobalsPlugin = {
  install(app: App) {
    app.config.globalProperties.__ = __
    app.config.globalProperties.can = can
    app.config.globalProperties.cannot = cannot
  },
}
