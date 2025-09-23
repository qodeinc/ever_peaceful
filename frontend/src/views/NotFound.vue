<template>
  <section class="relative min-h-screen bg-white flex items-center justify-center">
    <!-- Subtle background pattern matching hero -->
    <div class="absolute inset-0 bg-gradient-to-br from-teal-50/30 via-transparent to-emerald-50/30 pointer-events-none"></div>
    
    <!-- Decorative circles -->
    <div class="absolute top-20 right-20 w-20 h-20 bg-teal-500 rounded-full opacity-20 animate-pulse -z-10"></div>
    <div class="absolute bottom-20 left-20 w-16 h-16 bg-emerald-500 rounded-full opacity-30 animate-pulse -z-10" style="animation-delay: 1s;"></div>
    <div class="absolute top-1/2 right-1/4 w-12 h-12 bg-yellow-400 rounded-full opacity-25 animate-pulse -z-10" style="animation-delay: 2s;"></div>
    <div class="absolute bottom-1/3 right-1/3 w-8 h-8 bg-teal-400 rounded-full opacity-20 animate-pulse -z-10" style="animation-delay: 3s;"></div>

    <div class="relative z-10 w-full max-w-2xl mx-auto px-4 sm:px-6 text-center">
      <!-- 404 Icon -->
      <div class="mb-8">
        <div class="w-32 h-32 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-6">
          <AlertTriangle class="w-16 h-16 text-yellow-500" />
        </div>
        <div class="space-y-2">
          <h1 class="text-8xl sm:text-9xl font-bold text-gray-200 leading-none">404</h1>
          <div class="text-2xl sm:text-3xl font-bold text-gray-900">Page Not Found</div>
        </div>
      </div>

      <!-- Message -->
      <div class="space-y-6 mb-12">
        <p class="text-xl sm:text-2xl text-gray-600 leading-relaxed max-w-xl mx-auto">
          Oops! The page you're looking for seems to have wandered off on its own wellness journey.
        </p>
        <p class="text-lg text-gray-500 max-w-lg mx-auto">
          Don't worry, we're here to help you find your way back to better mental health resources.
        </p>
      </div>

      <!-- Action Buttons -->
      <div class="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center sm:items-center">
        <router-link
          to="/"
          class="inline-flex items-center justify-center w-full sm:w-auto group px-8 py-4 bg-teal-600 text-white font-semibold text-lg rounded-2xl shadow-xl hover:shadow-2xl hover:bg-teal-700 transform hover:-translate-y-1 transition-all duration-300"
        >
          <Home class="mr-3 w-5 h-5" />
          <span>Go Back Home</span>
          <ArrowRight class="ml-3 w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" />
        </router-link>
        
        <button
          @click="goBack"
          class="inline-flex items-center justify-center w-full sm:w-auto group px-8 py-4 bg-white text-gray-700 font-semibold text-lg rounded-2xl border-2 border-gray-200 hover:border-teal-300 hover:bg-gray-50 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300"
        >
          <ArrowLeft class="mr-3 w-5 h-5" />
          <span>Go Back</span>
        </button>
      </div>

      <!-- Help Links -->
      <div class="mt-16 pt-8 border-t border-gray-200">
        <p class="text-gray-600 mb-6">Looking for something specific?</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-2xl mx-auto">
          <router-link
            to="/"
            class="group p-4 bg-white rounded-xl border border-gray-200 hover:border-teal-300 hover:shadow-lg transition-all duration-300 text-center"
          >
            <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-teal-200 transition-colors">
              <Heart class="w-6 h-6 text-teal-600" />
            </div>
            <h3 class="font-semibold text-gray-900 mb-1">Wellness Resources</h3>
            <p class="text-sm text-gray-500">Find mental health support</p>
          </router-link>

          <router-link
            to="/about"
            class="group p-4 bg-white rounded-xl border border-gray-200 hover:border-emerald-300 hover:shadow-lg transition-all duration-300 text-center"
          >
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-emerald-200 transition-colors">
              <Users class="w-6 h-6 text-emerald-600" />
            </div>
            <h3 class="font-semibold text-gray-900 mb-1">About Us</h3>
            <p class="text-sm text-gray-500">Learn about our mission</p>
          </router-link>

          <router-link
            to="/login"
            class="group p-4 bg-white rounded-xl border border-gray-200 hover:border-yellow-300 hover:shadow-lg transition-all duration-300 text-center"
          >
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-yellow-200 transition-colors">
              <LogIn class="w-6 h-6 text-yellow-600" />
            </div>
            <h3 class="font-semibold text-gray-900 mb-1">Sign In</h3>
            <p class="text-sm text-gray-500">Access your account</p>
          </router-link>
        </div>
      </div>

      <!-- Contact Support -->
      <div class="mt-12 p-6 bg-gray-50 rounded-2xl">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Still need help?</h3>
        <p class="text-gray-600 mb-4">
          If you believe this is an error, please don't hesitate to reach out to our support team.
        </p>
        <button
          @click="contactSupport"
          class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-300 transition-colors duration-200"
        >
          <Mail class="mr-2 w-4 h-4" />
          Contact Support
        </button>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { 
  AlertTriangle, 
  Home, 
  ArrowRight, 
  ArrowLeft, 
  Heart, 
  Users, 
  LogIn, 
  Mail 
} from 'lucide-vue-next'

const router = useRouter()

const goBack = () => {
  // Check if there's history to go back to
  if (window.history.length > 1) {
    router.go(-1)
  } else {
    // If no history, redirect to home
    router.push('/')
  }
}

const contactSupport = () => {
  // In a real app, this could open a contact form, chat widget, or mailto link
  window.location.href = 'mailto:support@mentalhealthplatform.com?subject=404 Error - Page Not Found'
}
</script>

<style scoped>
/* Enhanced button hover effects matching hero */
button, a {
  position: relative;
  overflow: hidden;
}

button:not(:disabled)::before,
a::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s;
}

button:hover:not(:disabled)::before,
a:hover::before {
  left: 100%;
}

/* Float animation matching hero */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}

/* Bounce animation for 404 number */
@keyframes bounce-subtle {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-5px); }
}

.animate-bounce-subtle {
  animation: bounce-subtle 2s ease-in-out infinite;
}

/* Pulse effect for help cards */
.group:hover .w-12 {
  transform: scale(1.1);
}
</style>