import { app } from './app'
import { router } from './router'
import { createPinia } from 'pinia'
import { createAhsandevs } from 'ahsandevs'
import './tailwind.css'
import 'thetheme/dist/thetheme.css'

app
  .use(createAhsandevs)
  .use(createPinia())
  .use(router)
  .mount('#app')
