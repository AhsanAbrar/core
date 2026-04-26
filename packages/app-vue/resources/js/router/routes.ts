import type { RouteRecordRaw } from 'vue-router'

import Forbidden from '@views/error/Forbidden.vue'
import NotFound from '@views/error/NotFound.vue'

import AppLayout from '@layouts/app/AppLayout.vue'

import DashboardPage from '@features/dashboard/pages/DashboardPage.vue'
import UsersIndexPage from '@features/users/pages/UsersIndexPage.vue'
import UserDetailPage from '@features/users/pages/UserDetailPage.vue'

export const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: AppLayout,
    children: [
      // Dashboard
      {
        path: '',
        name: 'dashboard',
        component: DashboardPage,
      },
      {
        path: 'users',
        name: 'users.index',
        component: UsersIndexPage,
      },
      {
        path: 'users/:id',
        name: 'users.show',
        component: UserDetailPage,
        props: route => ({ id: String(route.params.id) }),
      },
    ],
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
