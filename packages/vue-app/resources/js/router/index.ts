import { appData } from 'appdata'
import { createRouter, createWebHistory } from 'vue-router'
import { routes } from './routes'
import { globalRouter } from 'ahsandevs'

export const router = createRouter({
  history: createWebHistory(appData.prefix),
  routes,
})

globalRouter.router = router
