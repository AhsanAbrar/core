import type { RouteRecordRaw } from 'vue-router'
import Forbidden from 'views/error/Forbidden.vue'
import NotFound from 'views/error/NotFound.vue'
import HomeIndex from 'views/Home.vue'

export const routes: RouteRecordRaw[] = [
  { path: '/', name: 'home', component: HomeIndex },

  { path: '/403', name: 'Forbidden', component: Forbidden },
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
]
