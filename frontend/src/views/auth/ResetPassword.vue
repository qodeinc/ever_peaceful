<template>
  <section class="relative min-h-screen bg-white flex items-center justify-center">
    <!-- Subtle background pattern matching hero -->
    <div class="absolute inset-0 bg-gradient-to-br from-yellow-50/30 via-transparent to-teal-50/30 pointer-events-none"></div>
    
    <!-- Decorative circles -->
    <div class="absolute top-20 right-20 w-20 h-20 bg-yellow-400 rounded-full opacity-20 animate-pulse -z-10"></div>
    <div class="absolute bottom-20 left-20 w-16 h-16 bg-teal-500 rounded-full opacity-30 animate-pulse -z-10" style="animation-delay: 1s;"></div>
    <div class="absolute top-1/2 left-1/4 w-12 h-12 bg-emerald-500 rounded-full opacity-25 animate-pulse -z-10" style="animation-delay: 2s;"></div>

    <div class="relative z-10 w-full max-w-md mx-auto px-4 sm:px-6">
      <div class="bg-white rounded-3xl shadow-2xl p-8 sm:p-10 border border-gray-100">
        <!-- Header -->
        <div class="text-center mb-8">
          <div class="w-16 h-16 bg-yellow-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
            <KeyRound class="w-8 h-8 text-yellow-600" />
          </div>
          <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">Reset Password</h1>
          <p class="text-gray-600 text-lg">Enter your email to receive reset instructions</p>
        </div>

        <!-- Reset Form -->
        <form @submit.prevent="handleResetPassword" class="space-y-6" v-if="!isSubmitted">
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
                :class="[
                  'w-full pl-12 pr-4 py-4 border-2 rounded-2xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-0 transition-all duration-300',
                  errors.email 
                    ? 'border-red-300 focus:border-red-500 bg-red-50/50' 
                    : 'border-gray-200 focus:border-yellow-500 hover:border-gray-300'
                ]"
                placeholder="Enter your email address"
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

          <!-- Reset Button -->
          <button
            type="submit"
            :disabled="isLoading"
            class="w-full group px-8 py-4 bg-yellow-500 text-white font-semibold text-lg rounded-2xl shadow-xl hover:shadow-2xl hover:bg-yellow-600 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
          >
            <Send v-if="!isLoading" class="mr-3 w-5 h-5" />
            <Loader2 v-else class="mr-3 w-5 h-5 animate-spin" />
            <span>{{ isLoading ? 'Sending Instructions...' : 'Send Reset Instructions' }}</span>
          </button>
        </form>

        <!-- Success State -->
        <div v-else class="text-center space-y-6">
          <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto">
            <CheckCircle class="w-10 h-10 text-emerald-600" />
          </div>
          <div class="space-y-4">
            <h2 class="text-2xl font-bold text-gray-900">Check Your Email</h2>
            <p class="text-gray-600 leading-relaxed">
              We've sent password reset instructions to 
              <span class="font-semibold text-gray-900">{{ formData.email }}</span>
            </p>
            <p class="text-sm text-gray-500">
              Didn't receive the email? Check your spam folder or 
              <button 
                @click="resendEmail" 
                :disabled="resendCooldown > 0"
                class="text-yellow-600 hover:text-yellow-700 font-medium disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
              >
                {{ resendCooldown > 0 ? `resend in ${resendCooldown}s` : 'click to resend' }}
              </button>
            </p>
          </div>
          
          <!-- Back to Login -->
          <button
            @click="goToLogin"
            class="w-full group px-8 py-4 bg-white text-gray-700 font-semibold text-lg rounded-2xl border-2 border-gray-200 hover:border-yellow-300 hover:bg-gray-50 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center"
          >
            <ArrowLeft class="mr-3 w-5 h-5" />
            <span>Back to Login</span>
          </button>
        </div>

        <!-- Navigation Links (only show when not submitted) -->
        <div v-if="!isSubmitted" class="mt-8 pt-6 border-t border-gray-200 space-y-4">
          <div class="text-center">
            <p class="text-gray-600 mb-4">Remember your password?</p>
            <div class="space-y-2 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
              <router-link
                to="/login"
                class="block sm:inline-block text-yellow-600 hover:text-yellow-700 font-semibold transition-colors duration-200"
              >
                Sign In
              </router-link>
              <span class="hidden sm:inline text-gray-300">•</span>
              <router-link
                to="/signup"
                class="block sm:inline-block text-yellow-600 hover:text-yellow-700 font-semibold transition-colors duration-200"
              >
                Create Account
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, reactive, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { 
  Mail, 
  KeyRound,
  Send,
  CheckCircle,
  ArrowLeft,
  AlertCircle, 
  Loader2 
} from 'lucide-vue-next'

const router = useRouter()

// Form data
const formData = reactive({
  email: ''
})

// Form state
const isLoading = ref(false)
const isSubmitted = ref(false)
const resendCooldown = ref(0)
let resendTimer: NodeJS.Timeout | null = null

// Validation errors
const errors = reactive({
  email: ''
})

// Validation functions
const validateEmail = () => {
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!formData.email) {
    errors.email = 'Email address is required'
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
const handleResetPassword = async () => {
  // Validate email
  if (!validateEmail()) {
    return
  }

  isLoading.value = true
  
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1500))
    
    // Show success state
    isSubmitted.value = true
    startResendCooldown()
  } catch (error) {
    errors.email = 'Failed to send reset instructions. Please try again.'
  } finally {
    isLoading.value = false
  }
}

// Resend functionality
const resendEmail = async () => {
  if (resendCooldown.value > 0) return
  
  isLoading.value = true
  
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000))
    startResendCooldown()
  } catch (error) {
    // Handle error silently or show a toast
  } finally {
    isLoading.value = false
  }
}

const startResendCooldown = () => {
  resendCooldown.value = 30
  resendTimer = setInterval(() => {
    resendCooldown.value--
    if (resendCooldown.value <= 0) {
      clearInterval(resendTimer!)
      resendTimer = null
    }
  }, 1000)
}

const goToLogin = () => {
  router.push('/login')
}

// Cleanup
onUnmounted(() => {
  if (resendTimer) {
    clearInterval(resendTimer)
  }
})
</script>

<style scoped>
/* Enhanced button hover effects matching hero */
button {
  position: relative;
  overflow: hidden;
}

button:not(:disabled)::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s;
}

button:hover:not(:disabled)::before {
  left: 100%;
}

/* Smooth transitions */
.error-enter-active, .error-leave-active {
  transition: all 0.3s ease;
}

.error-enter-from, .error-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* Float animation matching hero */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}
</style>