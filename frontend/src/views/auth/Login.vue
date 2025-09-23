<template>
  <section class="relative min-h-screen bg-white flex flex-row">
    <!-- Subtle background pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-teal-50/30 via-transparent to-emerald-50/30 pointer-events-none"></div>
    
    <!-- Decorative circles (visible on form side only) -->
    <div class="absolute top-20 right-20 w-20 h-20 bg-teal-500 rounded-full opacity-20 animate-pulse -z-10 hidden lg:block"></div>
    <div class="absolute bottom-20 right-20 w-16 h-16 bg-emerald-500 rounded-full opacity-30 animate-pulse -z-10 hidden lg:block" style="animation-delay: 1s;"></div>
    <div class="absolute top-1/2 right-1/4 w-12 h-12 bg-yellow-400 rounded-full opacity-25 animate-pulse -z-10 hidden lg:block" style="animation-delay: 2s;"></div>
    
    <!-- Left side: Full-height Image -->
    <div class="fixed top-0 left-0 w-full h-screen lg:w-1/2 hidden lg:block">
      <img 
        src="@/assets/images/therapy.jpg" 
        alt="Secure Login - Wellness Platform" 
        class="w-full h-full object-cover rounded-none"
      />
    </div>
    
    <!-- Right side: Login Form -->
    <div class="w-full lg:w-1/2 ml-auto flex items-center justify-center py-12 lg:py-0 relative z-10">
      <div class="bg-white rounded-3xl shadow-2xl p-8 sm:p-10 border border-gray-100 max-w-md mx-auto lg:mx-8 mt-16 mb-16">
        <!-- Logo -->
        <div class="text-center mb-6">
          <img 
            src="@/assets/images/bird.jpg" 
            alt="Ever Peaceful Logo" 
            class="max-w-[80px] sm:max-w-[80px] mx-auto rounded-full"
          />
        </div>
        
        <!-- Header -->
        <div class="text-center mb-8">
          <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">Welcome Back</h1>
          <p class="text-gray-600 text-lg">Sign in to your wellness account</p>
        </div>
        
        <!-- Login Form -->
        <form @submit.prevent="handleLogin" class="space-y-6">
          <!-- Email Field -->
          <div class="space-y-2">
            <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <Mail class="h-5 w-5 text-gray-400" />
              </div>
              <input
                id="email"
                v-model="formData.email"
                type="email"
                autocomplete="email"
                class="w-full pl-12 pr-4 py-4 border-2 rounded-2xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-0 transition-all duration-300"
                :class="{
                  'border-red-300 focus:border-red-500 bg-red-50/50': errors.email,
                  'border-gray-200 focus:border-teal-500 hover:border-gray-300': !errors.email
                }"
                placeholder="Enter your email"
                @blur="validateEmail"
                @input="clearError('email')"
              />
            </div>
            <Transition name="error">
              <p v-if="errors.email" class="text-red-600 text-sm flex items-center mt-1">
                <AlertCircle class="w-4 h-4 mr-1" />
                {{ errors.email }}
              </p>
            </Transition>
          </div>
          
          <!-- Password Field -->
          <div class="space-y-2">
            <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <Lock class="h-5 w-5 text-gray-400" />
              </div>
              <input
                id="password"
                v-model="formData.password"
                :type="showPassword ? 'text' : 'password'"
                autocomplete="current-password"
                class="w-full pl-12 pr-12 py-4 border-2 rounded-2xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-0 transition-all duration-300"
                :class="{
                  'border-red-300 focus:border-red-500 bg-red-50/50': errors.password,
                  'border-gray-200 focus:border-teal-500 hover:border-gray-300': !errors.password
                }"
                placeholder="Enter your password"
                @input="clearError('password')"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                aria-label="Toggle password visibility"
              >
                <Eye v-if="!showPassword" class="h-5 w-5" />
                <EyeOff v-else class="h-5 w-5" />
              </button>
            </div>
            <Transition name="error">
              <p v-if="errors.password" class="text-red-600 text-sm flex items-center mt-1">
                <AlertCircle class="w-4 h-4 mr-1" />
                {{ errors.password }}
              </p>
            </Transition>
          </div>
          
          <!-- Remember Me & Forgot Password -->
          <div class="flex items-center justify-between">
            <label class="flex items-center">
              <input
                v-model="formData.rememberMe"
                type="checkbox"
                class="h-4 w-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500 focus:ring-2"
              />
              <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
            <router-link
              to="/reset-password"
              class="text-teal-600 hover:text-teal-700 text-sm font-medium transition-colors duration-200"
            >
              Forgot your password?
            </router-link>
          </div>
          
          <!-- Login Button -->
          <button
            type="submit"
            :disabled="isLoading"
            class="w-full flex items-center justify-center px-8 py-4 bg-teal-600 text-white font-semibold text-lg rounded-2xl shadow-xl hover:shadow-2xl hover:bg-teal-700 transform hover:-translate-y-1 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none group relative overflow-hidden"
          >
            <LogIn v-if="!isLoading" class="mr-3 w-5 h-5" />
            <Loader2 v-else class="mr-3 w-5 h-5 animate-spin" />
            <span>{{ isLoading ? 'Signing In...' : 'Sign In' }}</span>
            <span v-if="!isLoading" class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -left-full group-hover:left-full transition-all duration-500"></span>
          </button>
          
          <!-- Divider for Social Login -->
          <div class="relative mt-6">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-4 bg-white text-gray-500">OR</span>
            </div>
          </div>
          
          <!-- Social Login Buttons -->
          <div class="grid grid-cols-1 gap-3 mt-4">
            <button
              type="button"
              @click="handleSocialLogin('google')"
              :disabled="isLoading"
              class="flex items-center justify-center px-6 py-3 bg-white text-gray-700 font-semibold rounded-2xl border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
              </svg>
              Continue with Google
            </button>
            <button
              type="button"
              @click="handleSocialLogin('microsoft')"
              :disabled="isLoading"
              class="flex items-center justify-center px-6 py-3 bg-white text-gray-700 font-semibold rounded-2xl border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="#F25022" d="M11.4 2H2v9.4h9.4V2z" />
                <path fill="#00A4EF" d="M11.4 12.6H2V22h9.4v-9.4z" />
                <path fill="#7FBA00" d="M22 2h-9.4v9.4H22V2z" />
                <path fill="#FFB900" d="M22 12.6h-9.4V22H22v-9.4z" />
              </svg>
              Continue with Microsoft
            </button>
          </div>
          
          <!-- Success Message -->
          <Transition name="success">
            <div v-if="successMessage" class="bg-teal-50 border border-teal-200 rounded-2xl p-4 mt-6">
              <p class="text-teal-700 text-sm flex items-center">
                <CheckCircle class="w-4 h-4 mr-2" />
                {{ successMessage }}
              </p>
            </div>
          </Transition>
        </form>
        
        <!-- Divider for Signup Link -->
        <div class="mt-8 pt-6 border-t border-gray-200">
          <p class="text-center text-gray-600">
            Don't have an account?
            <router-link
              to="/signup"
              class="text-teal-600 hover:text-teal-700 font-semibold ml-1 transition-colors duration-200"
            >
              Sign up
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { 
  Mail, 
  Lock, 
  Eye, 
  EyeOff, 
  LogIn, 
  AlertCircle, 
  CheckCircle, 
  Loader2
} from 'lucide-vue-next'

const router = useRouter()

// Form data
const formData = reactive({
  email: '',
  password: '',
  rememberMe: false
})

// Form state
const showPassword = ref(false)
const isLoading = ref(false)
const successMessage = ref('')

// Validation errors
const errors = reactive({
  email: '',
  password: ''
})

// Validation functions
const validateEmail = () => {
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!formData.email) {
    errors.email = 'Email is required'
  } else if (!emailPattern.test(formData.email)) {
    errors.email = 'Please enter a valid email address'
  } else {
    errors.email = ''
  }
  return !errors.email
}

const clearError = (field: keyof typeof errors) => {
  errors[field] = ''
}

// Form submission
const handleLogin = async () => {
  // Validate email only
  const isEmailValid = validateEmail()

  // Check if password is empty
  if (!formData.password) {
    errors.password = 'Password is required'
  } else {
    errors.password = ''
  }

  if (!isEmailValid || errors.password) {
    return
  }

  isLoading.value = true
  
  try {
    await new Promise(resolve => setTimeout(resolve, 1500))
    successMessage.value = 'Login successful! Redirecting...'
    setTimeout(() => {
      router.push('/dashboard')
    }, 1000)
  } catch (error) {
    errors.password = 'Invalid email or password. Please try again.'
  } finally {
    isLoading.value = false
  }
}

// Social login handler
const handleSocialLogin = (provider: string) => {
  console.log(`Logging in with ${provider}`)
  // In a real app, you'd redirect to the OAuth endpoint
  // Example: window.location.href = `/auth/${provider}`
  isLoading.value = true
  
  // Simulate social login
  setTimeout(() => {
    successMessage.value = `Login with ${provider} successful! Redirecting...`
    setTimeout(() => {
      router.push('/dashboard')
    }, 1000)
    isLoading.value = false
  }, 1500)
}
</script>

<style scoped>
/* Transitions for error/success messages */
.error-enter-active, .error-leave-active {
  transition: all 0.3s ease;
}

.error-enter-from, .error-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.success-enter-active, .success-leave-active {
  transition: all 0.3s ease;
}

.success-enter-from, .success-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* Pulse animation for decorative elements */
@keyframes pulse {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.animate-pulse {
  animation: pulse 3s ease-in-out infinite;
}
</style>