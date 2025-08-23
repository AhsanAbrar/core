import type { RouteRecordRaw } from 'vue-router'
import { NotFound, Forbidden } from 'thetheme'
import Home from 'View/Home.vue'
import Profile from 'View/Profile.vue'
import UserIndex from 'View/users/Index.vue'
import SettingsGeneral from 'View/settings/General.vue'
import SettingsEmail from 'View/settings/Email.vue'

export const routes: RouteRecordRaw[] = [
  { path: '/', component: Home },
  { path: '/profile', component: Profile },
  { path: '/users', name: 'UserIndex', component: UserIndex },
  { path: '/settings/general', component: SettingsGeneral },
  { path: '/settings/email', component: SettingsEmail },

  { path: '/403', name: 'Forbidden', component: Forbidden },
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
]
