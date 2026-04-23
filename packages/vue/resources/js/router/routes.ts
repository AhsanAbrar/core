import type { RouteRecordRaw } from 'vue-router'

import Forbidden from '@views/error/Forbidden.vue'
import NotFound from '@views/error/NotFound.vue'

import Home from '@views/Home.vue'

export const routes: RouteRecordRaw[] = [
  {
    path: '/',
    name: 'home',
    component: Home,
  },

  // Error pages
  {
    path: '/403',
    name: 'Forbidden',
    component: Forbidden,
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: NotFound,
  },
]
