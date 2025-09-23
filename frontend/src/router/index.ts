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
      meta: {
        title: 'Mental Health & Wellness Platform',
        description: 'Connect with licensed therapists and access personalized mental health resources'
      }
    },
    {
      path: '/about',
      name: 'about',
      components: {
        default: () => import('../views/AboutView.vue'),
        navbar: Navbar,
        footer: Footer
      },
      meta: {
        title: 'About Us - Mental Health Platform',
        description: 'Learn about our mission to provide accessible mental health care'
      }
    },
    // Authentication routes (no navbar/footer for clean auth experience)
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/auth/Login.vue'),
      meta: {
        title: 'Sign In - Mental Health Platform',
        description: 'Sign in to your wellness account',
        requiresGuest: true,
        hideLayout: true // Flag to hide navbar/footer
      }
    },
    {
      path: '/signup',
      name: 'signup', 
      component: () => import('../views/auth/Signup.vue'),
      meta: {
        title: 'Create Account - Mental Health Platform',
        description: 'Join our wellness community today',
        requiresGuest: true,
        hideLayout: true
      }
    },
    {
      path: '/reset-password',
      name: 'reset-password',
      component: () => import('../views/auth/ResetPassword.vue'),
      meta: {
        title: 'Reset Password - Mental Health Platform', 
        description: 'Reset your account password',
        requiresGuest: true,
        hideLayout: true
      }
    },
    // Protected routes (with navbar/footer) - TODO: Implement when needed
    // {
    //   path: '/dashboard',
    //   name: 'dashboard',
    //   components: {
    //     default: () => import('../views/Dashboard.vue'),
    //     navbar: Navbar,
    //     footer: Footer
    //   },
    //   meta: {
    //     title: 'Dashboard - Mental Health Platform',
    //     description: 'Your personal wellness dashboard',
    //     requiresAuth: true
    //   }
    // },
    // {
    //   path: '/profile',
    //   name: 'profile',
    //   components: {
    //     default: () => import('../views/Profile.vue'),
    //     navbar: Navbar,
    //     footer: Footer
    //   },
    //   meta: {
    //     title: 'Profile - Mental Health Platform',
    //     description: 'Manage your account settings and preferences',
    //     requiresAuth: true
    //   }
    // },
    // {
    //   path: '/appointments',
    //   name: 'appointments',
    //   components: {
    //     default: () => import('../views/Appointments.vue'),
    //     navbar: Navbar,
    //     footer: Footer
    //   },
    //   meta: {
    //     title: 'My Appointments - Mental Health Platform',
    //     description: 'View and manage your therapy appointments',
    //     requiresAuth: true
    //   }
    // },
    // Catch all 404 - with layout
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      components: {
        default: () => import('../views/NotFound.vue'),
        //navbar: Navbar,
        //footer: Footer
      },
      meta: {
        title: 'Page Not Found - Mental Health Platform',
        description: 'The page you are looking for does not exist'
      }
    }
  ],
  scrollBehavior(to, from, savedPosition) {
    // Always scroll to top for smooth page transitions
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0, behavior: 'smooth' }
    }
  }
})

// Global navigation guards
router.beforeEach((to, from, next) => {
  // Set page title
  if (to.meta?.title) {
    document.title = to.meta.title as string
  }
  
  // Set meta description
  if (to.meta?.description) {
    let descriptionElement = document.querySelector('meta[name="description"]')
    if (!descriptionElement) {
      descriptionElement = document.createElement('meta')
      descriptionElement.setAttribute('name', 'description')
      document.head.appendChild(descriptionElement)
    }
    descriptionElement.setAttribute('content', to.meta.description as string)
  }

  // Mock authentication state - replace with your actual auth logic
  const isAuthenticated = localStorage.getItem('isAuthenticated') === 'true'
  
  // Handle authentication requirements
  if (to.meta?.requiresAuth && !isAuthenticated) {
    // Redirect to login with return path
    next({
      name: 'login',
      query: { redirect: to.fullPath }
    })
    return
  }
  
  // Handle guest-only routes (login, signup, etc.)
  if (to.meta?.requiresGuest && isAuthenticated) {
    // Redirect authenticated users to dashboard
    next({ name: 'dashboard' })
    return
  }
  
  next()
})

// After each route change
router.afterEach((to, from) => {
  // Optional: Add analytics tracking or other post-navigation logic
  if (process.env.NODE_ENV === 'development') {
    console.log(`Navigated from ${from.path || 'initial'} to ${to.path}`)
  }
})

export default router