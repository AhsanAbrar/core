import '@css/app.css'

import { app } from './app'
import { router } from './router'
import { createPinia } from 'pinia'
import { appGlobalsPlugin } from '@/plugins/app-globals'

app.use(appGlobalsPlugin).use(createPinia()).use(router)

// Handoff: remove shell mode, Vue + Tailwind take over
document.documentElement.removeAttribute('data-shell')

app.mount('#app')
