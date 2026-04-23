import { appData } from '@app-data'
import { createRouter, createWebHistory } from 'vue-router'
import { routes } from './routes'

export const router = createRouter({
  history: createWebHistory(appData.prefix),
  routes,
})
