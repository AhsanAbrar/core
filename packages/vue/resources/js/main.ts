import '@css/tailwind.css'

import { app } from './app'
import { router } from './router'
import { createPinia } from 'pinia'

app.use(createPinia()).use(router)

// Handoff: remove shell mode, Vue + Tailwind take over
document.documentElement.removeAttribute('data-shell')

app.mount('#app')
