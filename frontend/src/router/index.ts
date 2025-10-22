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
        //requiresGuest: true,
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
        //requiresGuest: true,
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
        //requiresAuth: true,
        role: 'user'
      }
    },
    // Therapist routes
    {
      path: '/therapist',
      component: () => import('../components/layout/TherapistLayout.vue'),
      meta: {
        requiresAuth: true,
        role: 'therapist'
      },
      children: [
        {
          path: '',
          name: 'therapist',
          component: { template: '<div></div>' }, // Empty component, layout handles dynamic rendering
          meta: {
            title: 'Therapist Dashboard - MindWell Platform',
            description: 'Manage your clients and sessions'
          }
        },
        {
          path: 'calendar',
          name: 'therapist-calendar',
          component: () => import('../views/therapist/Calendar.vue'),
          meta: {
            title: 'Therapist Calendar - MindWell Platform',
            description: 'Manage your appointments via Calendly'
          }
        },
        {
          path: 'callback',
          name: 'calendly-callback',
          component: () => import('../views/therapist/Calendar.vue'),
          meta: {
            title: 'Processing Calendly Authentication',
            description: 'Processing your Calendly connection'
          }
        }
      ]
    },
    // Admin route
    {
      path: '/admin',
      name: 'admin',
      component: () => import('../components/layout/AdminLayout.vue'),
      meta: {
        title: 'Admin Dashboard - MindWell Platform',
        description: 'Manage platform settings, users, and analytics',
        //requiresAuth: true,
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

// ===========================
// DEVELOPMENT MODE - BYPASS AUTH
// ===========================
const DEV_MODE = import.meta.env.DEV // true in development, false in production
const DEV_BYPASS_AUTH = true // Set to false to test auth guards in dev

// Mock authentication for development
const getMockAuthState = () => {
  // Check if mock auth is set in localStorage (for persistence across refreshes)
  const mockAuth = localStorage.getItem('DEV_MOCK_AUTH')
  const mockRole = localStorage.getItem('DEV_MOCK_ROLE')
  
  if (mockAuth === 'true') {
    return {
      isAuthenticated: true,
      userRole: mockRole || 'therapist'
    }
  }
  
  // Default: authenticated as therapist for easy testing
  return {
    isAuthenticated: true,
    userRole: 'therapist'
  }
}

// Helper function to set mock auth state (call this from browser console if needed)
// Example: setMockAuth('user'), setMockAuth('therapist'), setMockAuth('admin'), setMockAuth(null)
if (DEV_MODE) {
  (window as any).setMockAuth = (role: string | null) => {
    if (role) {
      localStorage.setItem('DEV_MOCK_AUTH', 'true')
      localStorage.setItem('DEV_MOCK_ROLE', role)
      console.log(`✅ Mock auth set: role = ${role}`)
    } else {
      localStorage.removeItem('DEV_MOCK_AUTH')
      localStorage.removeItem('DEV_MOCK_ROLE')
      console.log('❌ Mock auth cleared')
    }
    window.location.reload()
  }
  
  console.log('🔧 Development Mode Active')
  console.log('To test different roles, use: setMockAuth("user"), setMockAuth("therapist"), setMockAuth("admin")')
  console.log('To test auth guards, use: setMockAuth(null)')
}

// Navigation guards
router.beforeEach((to, from, next) => {
  // Update page title
  if (to.meta?.title) {
    document.title = to.meta.title as string
  }

  // Update meta description
  if (to.meta?.description) {
    let descriptionElement = document.querySelector('meta[name="description"]')
    if (!descriptionElement) {
      descriptionElement = document.createElement('meta')
      descriptionElement.setAttribute('name', 'description')
      document.head.appendChild(descriptionElement)
    }
    descriptionElement.setAttribute('content', to.meta.description as string)
  }

  // In dev mode with bypass enabled, use mock auth state
  const auth = DEV_MODE && DEV_BYPASS_AUTH ? getMockAuthState() : {
    isAuthenticated: false, // Replace with real auth check
    userRole: null // Replace with real role check
  }

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    // Route requires auth but user is not authenticated
    return next({ name: 'login' })
  }

  if (to.meta.requiresGuest && auth.isAuthenticated) {
    // Route is for guests but user is authenticated
    return next({ name: 'dashboard' })
  }

  if (to.meta.role && auth.userRole !== to.meta.role) {
    // Route requires specific role which user does not have
    return next({ name: 'not-found' })
  }

  return next()
})

export default router 