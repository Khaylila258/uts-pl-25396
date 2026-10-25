import { createRouter, createWebHistory } from 'vue-router'
import { auth } from '../stores/auth'
import LoginView from '../views/LoginView.vue'
import ItemsView from '../views/ItemsView.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/login', name: 'login', component: LoginView },
    { path: '/', name: 'items', component: ItemsView, meta: { requiresAuth: true } },
  ],
})

// Penjaga rute: halaman barang hanya bisa dibuka jika sudah login.
router.beforeEach((to) => {
  if (to.meta.requiresAuth && !auth.token) return { name: 'login' }
  if (to.name === 'login' && auth.token) return { name: 'items' }
})

export default router
