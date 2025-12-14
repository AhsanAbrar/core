import type { RouteRecordRaw } from 'vue-router'
import Forbidden from 'views/error/Forbidden.vue'
import NotFound from 'views/error/NotFound.vue'
// import HomeLayout from 'layouts/HomeLayout.vue'
import HomeIndex from 'views/Home.vue'

export const routes: RouteRecordRaw[] = [
  { path: '/', name: 'home', component: HomeIndex },

  // {
  //   path: '/',
  //   component: HomeLayout,
  //   props: route => ({ showSidebar: route.meta.sidebar === true }),
  //   children: [
  //     { path: '', name: 'home', component: HomeIndex, meta: { sidebar: true } },
  //   ],
  // },

  { path: '/403', name: 'Forbidden', component: Forbidden },
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
]
