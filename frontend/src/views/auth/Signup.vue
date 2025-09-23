<template>
  <section class="relative min-h-screen bg-white flex flex-row">
    <!-- Subtle background pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-teal-50/30 via-transparent to-emerald-50/30 pointer-events-none"></div>
    
    <!-- Decorative circles (visible on form side only) -->
    <div class="absolute top-20 right-20 w-20 h-20 bg-teal-500 rounded-full opacity-20 animate-pulse -z-10 hidden lg:block"></div>
    <div class="absolute bottom-20 right-20 w-16 h-16 bg-emerald-500 rounded-full opacity-30 animate-pulse -z-10 hidden lg:block" style="animation-delay: 1s;"></div>
    <div class="absolute top-1/3 right-1/4 w-12 h-12 bg-yellow-400 rounded-full opacity-25 animate-pulse -z-10 hidden lg:block" style="animation-delay: 2s;"></div>
    
    <!-- Left side: Full-height Image -->
    <div class="fixed top-0 left-0 w-full h-screen lg:w-1/2 hidden lg:block">
      <img 
        src="@/assets/images/therapy.jpg" 
        alt="Secure Signup - Ever Peaceful In Teal" 
        class="w-full h-full object-cover rounded-none"
      />
    </div>
    
    <!-- Right side: Signup Form -->
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
        <div class="text-center mb-6">
          <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">Join Our Community</h2>
          <p class="text-gray-600 text-lg">Start your wellness journey today</p>
        </div>
        
        <!-- Signup Form -->
        <form @submit.prevent="handleSignup" class="space-y-6">
          <!-- Full Name Field -->
          <div class="space-y-2">
            <label for="fullName" class="block text-sm font-semibold text-gray-700">Full Name</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <User class="h-5 w-5 text-gray-400" />
              </div>
              <input
                id="fullName"
                v-model="formData.fullName"
                type="text"
                autocomplete="name"
                class="w-full pl-12 pr-4 py-4 border-2 rounded-2xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-0 transition-all duration-300"
                :class="{
                  'border-red-300 focus:border-red-500 bg-red-50/50': errors.fullName,
                  'border-gray-200 focus:border-teal-500 hover:border-gray-300': !errors.fullName
                }"
                placeholder="Enter your full name"
                @blur="validateFullName"
                @input="clearError('fullName')"
              />
            </div>
            <Transition name="error">
              <p v-if="errors.fullName" class="text-red-600 text-sm flex items-center mt-1">
                <AlertCircle class="w-4 h-4 mr-1" />
                {{ errors.fullName }}
              </p>
            </Transition>
          </div>
          
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
                autocomplete="new-password"
                class="w-full pl-12 pr-12 py-4 border-2 rounded-2xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-0 transition-all duration-300"
                :class="{
                  'border-red-300 focus:border-red-500 bg-red-50/50': errors.password,
                  'border-gray-200 focus:border-teal-500 hover:border-gray-300': !errors.password
                }"
                placeholder="Create a strong password"
                @blur="validatePassword"
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
            <!-- Password strength indicator -->
            <div v-if="formData.password" class="space-y-1">
              <div class="flex space-x-1">
                <div 
                  v-for="i in 4" 
                  :key="i"
                  class="h-1 flex-1 rounded-full transition-colors duration-300"
                  :class="passwordStrength >= i ? getStrengthColor(passwordStrength) : 'bg-gray-200'"
                ></div>
              </div>
              <p class="text-xs text-gray-500">
                {{ getStrengthText(passwordStrength) }}
              </p>
            </div>
          </div>
          
          <!-- Confirm Password Field -->
          <div class="space-y-2">
            <label for="confirmPassword" class="block text-sm font-semibold text-gray-700">Confirm Password</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <Shield class="h-5 w-5 text-gray-400" />
              </div>
              <input
                id="confirmPassword"
                v-model="formData.confirmPassword"
                :type="showConfirmPassword ? 'text' : 'password'"
                autocomplete="new-password"
                class="w-full pl-12 pr-12 py-4 border-2 rounded-2xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-0 transition-all duration-300"
                :class="{
                  'border-red-300 focus:border-red-500 bg-red-50/50': errors.confirmPassword,
                  'border-gray-200 focus:border-teal-500 hover:border-gray-300': !errors.confirmPassword
                }"
                placeholder="Confirm your password"
                @blur="validateConfirmPassword"
                @input="clearError('confirmPassword')"
              />
              <button
                type="button"
                @click="showConfirmPassword = !showConfirmPassword"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                aria-label="Toggle confirm password visibility"
              >
                <Eye v-if="!showConfirmPassword" class="h-5 w-5" />
                <EyeOff v-else class="h-5 w-5" />
              </button>
            </div>
            <Transition name="error">
              <p v-if="errors.confirmPassword" class="text-red-600 text-sm flex items-center mt-1">
                <AlertCircle class="w-4 h-4 mr-1" />
                {{ errors.confirmPassword }}
              </p>
            </Transition>
          </div>
          
          <!-- Terms and Privacy Policy Checkbox -->
          <div class="space-y-2">
            <label class="flex items-start">
              <input
                v-model="formData.termsAgreed"
                type="checkbox"
                class="h-4 w-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500 focus:ring-2 mt-1"
                @change="clearError('termsAgreed')"
              />
              <span class="ml-2 text-sm text-gray-600">
                I agree to the 
                <router-link to="/terms" class="text-teal-600 hover:text-teal-700 font-medium transition-colors duration-200">
                  Terms of Service
                </router-link>
                and 
                <router-link to="/privacy" class="text-teal-600 hover:text-teal-700 font-medium transition-colors duration-200">
                  Privacy Policy
                </router-link>
              </span>
            </label>
            <Transition name="error">
              <p v-if="errors.termsAgreed" class="text-red-600 text-sm flex items-center mt-1">
                <AlertCircle class="w-4 h-4 mr-1" />
                {{ errors.termsAgreed }}
              </p>
            </Transition>
          </div>
          
          <!-- Sign Up Button -->
          <button
            type="submit"
            :disabled="isLoading"
            class="w-full flex items-center justify-center px-8 py-4 bg-teal-600 text-white font-semibold text-lg rounded-2xl shadow-xl hover:shadow-2xl hover:bg-teal-700 transform hover:-translate-y-1 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none group relative overflow-hidden"
          >
            <UserPlus v-if="!isLoading" class="mr-3 w-5 h-5" />
            <Loader2 v-else class="mr-3 w-5 h-5 animate-spin" />
            <span>{{ isLoading ? 'Creating Account...' : 'Create Account' }}</span>
            <span v-if="!isLoading" class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -left-full group-hover:left-full transition-all duration-500"></span>
          </button>
          
          <!-- Success Message -->
          <Transition name="success">
            <div v-if="successMessage" class="bg-teal-50 border border-teal-200 rounded-2xl p-4">
              <p class="text-teal-700 text-sm flex items-center">
                <CheckCircle class="w-4 h-4 mr-2" />
                {{ successMessage }}
              </p>
            </div>
          </Transition>
        </form>
        
        <!-- Divider -->
        <div class="mt-8 pt-6 border-t border-gray-200">
          <p class="text-center text-gray-600">
            Already have an account?
            <router-link
              to="/login"
              class="text-teal-600 hover:text-teal-700 font-semibold ml-1 transition-colors duration-200"
            >
              Sign in
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue'
import { useRouter } from 'vue-router'
import { 
  User,
  Mail, 
  Lock, 
  Shield,
  Eye, 
  EyeOff, 
  UserPlus, 
  AlertCircle, 
  CheckCircle, 
  Loader2
} from 'lucide-vue-next'

const router = useRouter()

// Mock existing emails for uniqueness check
const existingEmails = ['test@example.com', 'user@demo.com', 'admin@platform.com']

// Form data
const formData = reactive({
  fullName: '',
  email: '',
  password: '',
  confirmPassword: '',
  termsAgreed: false
})

// Form state
const showPassword = ref(false)
const showConfirmPassword = ref(false)
const isLoading = ref(false)
const successMessage = ref('')

// Validation errors
const errors = reactive({
  fullName: '',
  email: '',
  password: '',
  confirmPassword: '',
  termsAgreed: ''
})

// Password strength calculation
const passwordStrength = computed(() => {
  const password = formData.password
  let strength = 0
  
  if (password.length >= 8) strength++
  if (/[A-Z]/.test(password)) strength++
  if (/[a-z]/.test(password)) strength++
  if (/\d/.test(password)) strength++
  if (/[@$!%*?&]/.test(password)) strength++
  
  return Math.min(4, strength)
})

// Validation functions
const validateFullName = () => {
  if (!formData.fullName.trim()) {
    errors.fullName = 'Full name is required'
  } else if (formData.fullName.trim().length < 2) {
    errors.fullName = 'Full name must be at least 2 characters'
  } else {
    errors.fullName = ''
  }
  return !errors.fullName
}

const validateEmail = async () => {
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!formData.email) {
    errors.email = 'Email is required'
  } else if (!emailPattern.test(formData.email)) {
    errors.email = 'Please enter a valid email address'
  } else if (existingEmails.includes(formData.email.toLowerCase())) {
    errors.email = 'This email is already registered. Please use a different email.'
  } else {
    errors.email = ''
  }
  return !errors.email
}

const validatePassword = () => {
  if (!formData.password) {
    errors.password = 'Password is required'
  } else if (formData.password.length < 8) {
    errors.password = 'Password must be at least 8 characters'
  } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/.test(formData.password)) {
    errors.password = 'Password must contain uppercase, lowercase, number, and special character'
  } else {
    errors.password = ''
  }
  
  if (formData.confirmPassword) {
    validateConfirmPassword()
  }
  
  return !errors.password
}

const validateConfirmPassword = () => {
  if (!formData.confirmPassword) {
    errors.confirmPassword = 'Please confirm your password'
  } else if (formData.password !== formData.confirmPassword) {
    errors.confirmPassword = 'Passwords do not match'
  } else {
    errors.confirmPassword = ''
  }
  return !errors.confirmPassword
}

const validateTerms = () => {
  if (!formData.termsAgreed) {
    errors.termsAgreed = 'You must agree to the Terms of Service and Privacy Policy'
  } else {
    errors.termsAgreed = ''
  }
  return !errors.termsAgreed
}

const clearError = (field: keyof typeof errors) => {
  errors[field] = ''
}

// Helper functions for password strength
const getStrengthColor = (strength: number) => {
  const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-teal-500']
  return colors[strength - 1] || 'bg-gray-200'
}

const getStrengthText = (strength: number) => {
  const texts = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong']
  return texts[strength] || ''
}

// Form submission
const handleSignup = async () => {
  const isFullNameValid = validateFullName()
  const isEmailValid = await validateEmail()
  const isPasswordValid = validatePassword()
  const isConfirmPasswordValid = validateConfirmPassword()
  const isTermsValid = validateTerms()

  if (!isFullNameValid || !isEmailValid || !isPasswordValid || !isConfirmPasswordValid || !isTermsValid) {
    return
  }

  isLoading.value = true
  
  try {
    await new Promise(resolve => setTimeout(resolve, 2000))
    successMessage.value = 'Account created successfully! Redirecting to login...'
    setTimeout(() => {
      router.push('/login')
    }, 1500)
  } catch (error) {
    errors.email = 'Something went wrong. Please try again.'
  } finally {
    isLoading.value = false
  }
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