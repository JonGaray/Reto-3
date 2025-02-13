import { createRouter, createWebHistory } from 'vue-router'
import userView from '../views/UserView.vue'
import login from "../components/Login.vue";
import register from "../components/Register.vue"
import panel from "../components/admin/AdminPanel.vue"
import perfil from "../components/user/Perfil.vue"
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'userView',
      component: userView,
    },
    {
      path: '/login',
      name: 'login',
      component: login,
    },
    {
      path: '/register',
      name: '/register',
      component: register,
    },
    {
      path: '/perfil',
      name: '/perfil',
      component: perfil,
    },
    {
      path: '/panel',
      name: '/panel',
      component: panel,
    },
  ],
})

export default router
