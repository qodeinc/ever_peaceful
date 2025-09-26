import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      components: {
        default: () => import('../views/HomeView.vue'),
        navbar: () => import('../components/layout/Navbar.vue'),
        footer: () => import('../components/layout/Footer.vue')
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
        navbar: () => import('../components/layout/Navbar.vue'),
        footer: () => import('../components/layout/Footer.vue')
      },
      meta: {
        title: 'About Us - Mental Health Platform',
        description: 'Learn about our mission to provide accessible mental health care'
      }
    },
    
    // Authentication routes
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/auth/Login.vue'),
      meta: {
        title: 'Sign In - Mental Health Platform',
        description: 'Sign in to your wellness account',
        requiresGuest: true,
        hideLayout: true
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

    // Dashboard routes
    {
      path: '/dashboard',
      name: 'dashboard',
      redirect: '/dashboard/user'
    },
    {
      path: '/dashboard/user',
      name: 'user',
      component: () => import('../components/layout/UserLayout.vue'),
      meta: {
        title: 'User Dashboard - MindWell Platform',
        description: 'Your personal wellness dashboard',
        requiresAuth: true,
        role: 'user'
      }
    },
    
    // Therapist route
    {
      path: '/therapist',
      name: 'therapist',
      component: () => import('../components/layout/TherapistLayout.vue'),
      meta: {
        title: 'Therapist Dashboard - MindWell Platform',
        description: 'Manage your clients and sessions',
        requiresAuth: true,
        role: 'therapist'
      }
    },

    // Admin route
    {
      path: '/admin',
      name: 'admin',
      component: () => import('../components/layout/AdminLayout.vue'),
      meta: {
        title: 'Admin Dashboard - MindWell Platform',
        description: 'Manage platform settings, users, and analytics',
        requiresAuth: true,
        role: 'admin'
      }
    },

    // Catch all 404
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      components: {
        default: () => import('../views/NotFound.vue'),
        navbar: () => import('../components/layout/Navbar.vue'),
        footer: () => import('../components/layout/Footer.vue')
      },
      meta: {
        title: 'Page Not Found - MindWell Platform',
        description: 'The page you are looking for does not exist'
      }
    }
  ],
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0, behavior: 'smooth' }
    }
  }
})

// Navigation guards
router.beforeEach((to, from, next) => {
  if (to.meta?.title) {
    document.title = to.meta.title as string
  }
  
  if (to.meta?.description) {
    let descriptionElement = document.querySelector('meta[name="description"]')
    if (!descriptionElement) {
      descriptionElement = document.createElement('meta')
      descriptionElement.setAttribute('name', 'description')
      document.head.appendChild(descriptionElement)
    }
    descriptionElement.setAttribute('content', to.meta.description as string)
  }

  const isAuthenticated = true
  const userRole = to.meta?.role || 'user'
  
  next()
})

router.afterEach((to, from) => {
  if (process.env.NODE_ENV === 'development') {
    console.log(`Navigated from ${from.path || 'initial'} to ${to.path}`)
  }
})

export default router