import { app } from './app'
import { router } from './router'
import { createPinia } from 'pinia'
// import { createSpanvel } from './spanvel/createSpanvel'
import './tailwind.css'
import './style.css'

app
//   .use(createSpanvel)
  .use(createPinia())
  .use(router)
  .mount('#app')
