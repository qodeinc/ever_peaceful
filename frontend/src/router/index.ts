import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import Navbar from '../components/layout/Navbar.vue'
import Footer from '../components/layout/Footer.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      components: {
        default: HomeView,
        navbar: Navbar,
        footer: Footer
      },
    },
    {
      path: '/about',
      name: 'about',
      components: {
        default: () => import('../views/AboutView.vue'),
        navbar: Navbar,
        footer: Footer
      },
    },
  ],
})

export default router