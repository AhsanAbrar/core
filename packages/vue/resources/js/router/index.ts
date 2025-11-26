import { appData } from 'app-data'
import { createRouter, createWebHistory } from 'vue-router'
import { routes } from './routes'
// import { globalRouter } from 'spanvel'

export const router = createRouter({
  history: createWebHistory(appData.prefix),
  routes,
})

// globalRouter.router = router
