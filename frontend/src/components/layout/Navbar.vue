<template>
  <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200/50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        
        <!-- Logo/Brand -->
        <div class="flex items-center">
          <router-link to="/" class="flex items-center space-x-2 group">
            <!-- Logo Icon -->
            <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
              </svg>
            </div>
            <!-- Brand Name -->
            <div class="hidden sm:block">
              <h1 class="text-xl font-bold text-gray-900">Ever Peaceful</h1>
              <p class="text-xs text-gray-500 -mt-1">Mental Health Support</p>
            </div>
          </router-link>
        </div>
        
        <!-- Desktop Navigation Menu -->
        <div class="hidden md:flex items-center space-x-8">
          <router-link 
            to="/psychological-assessments" 
            class="nav-link text-gray-700 hover:text-teal-600 font-medium transition-colors duration-200"
            :class="{ 'text-teal-600': $route.name === 'psychological-assessments' }"
          >
            Psychological Assessments
          </router-link>
          
          <router-link 
            to="/education" 
            class="nav-link text-gray-700 hover:text-teal-600 font-medium transition-colors duration-200"
            :class="{ 'text-teal-600': $route.name === 'education' }"
          >
            Education
          </router-link>
          
          <router-link 
            to="/engagement" 
            class="nav-link text-gray-700 hover:text-teal-600 font-medium transition-colors duration-200"
            :class="{ 'text-teal-600': $route.name === 'engagement' }"
          >
            Engagement
          </router-link>
          
          <router-link 
            to="/support-us" 
            class="nav-link text-gray-700 hover:text-teal-600 font-medium transition-colors duration-200"
            :class="{ 'text-teal-600': $route.name === 'support-us' }"
          >
            Support Us
          </router-link>
        </div>
        
        <!-- CTA Button & Mobile Menu Button -->
        <div class="flex items-center space-x-4">
          <!-- CTA Button (hidden on mobile) -->
          <router-link 
            to="/login" 
            class="hidden md:inline-flex items-center px-6 py-2 bg-gradient-to-r from-teal-600 to-teal-700 text-white font-semibold rounded-xl hover:shadow-lg hover:from-teal-700 hover:to-teal-800 transform hover:-translate-y-0.5 transition-all duration-200"
          >
            Take Assessment
          </router-link>
          
          <!-- Mobile menu button -->
          <button
            @click="toggleMobileMenu"
            class="md:hidden p-2 rounded-lg text-gray-700 hover:text-teal-600 hover:bg-gray-100 transition-colors duration-200"
            :class="{ 'text-teal-600 bg-gray-100': isMobileMenuOpen }"
            aria-label="Toggle mobile menu"
          >
            <svg 
              class="w-6 h-6" 
              fill="none" 
              stroke="currentColor" 
              viewBox="0 0 24 24"
              :class="{ 'rotate-90': isMobileMenuOpen }"
              style="transition: transform 0.2s ease"
            >
              <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                :d="isMobileMenuOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'"
              />
            </svg>
          </button>
        </div>
      </div>
      
      <!-- Mobile Navigation Menu -->
      <div 
        class="md:hidden overflow-hidden transition-all duration-300 ease-in-out"
        :class="isMobileMenuOpen ? 'max-h-96 pb-4' : 'max-h-0'"
      >
        <div class="space-y-2 pt-4 border-t border-gray-200">
          <router-link 
            to="/psychological-assessments" 
            @click="closeMobileMenu"
            class="block px-4 py-3 text-gray-700 hover:text-teal-600 hover:bg-gray-50 rounded-lg font-medium transition-colors duration-200"
            :class="{ 'text-teal-600 bg-teal-50': $route.name === 'psychological-assessments' }"
          >
            Psychological Assessments
          </router-link>
          
          <router-link 
            to="/education" 
            @click="closeMobileMenu"
            class="block px-4 py-3 text-gray-700 hover:text-teal-600 hover:bg-gray-50 rounded-lg font-medium transition-colors duration-200"
            :class="{ 'text-teal-600 bg-teal-50': $route.name === 'education' }"
          >
            Education
          </router-link>
          
          <router-link 
            to="/engagement" 
            @click="closeMobileMenu"
            class="block px-4 py-3 text-gray-700 hover:text-teal-600 hover:bg-gray-50 rounded-lg font-medium transition-colors duration-200"
            :class="{ 'text-teal-600 bg-teal-50': $route.name === 'engagement' }"
          >
            Engagement
          </router-link>
          
          <router-link 
            to="/support-us" 
            @click="closeMobileMenu"
            class="block px-4 py-3 text-gray-700 hover:text-teal-600 hover:bg-gray-50 rounded-lg font-medium transition-colors duration-200"
            :class="{ 'text-teal-600 bg-teal-50': $route.name === 'support-us' }"
          >
            Support Us
          </router-link>
          
          <!-- Mobile CTA Button -->
          <div class="pt-4">
            <router-link 
              to="/login" 
              @click="closeMobileMenu"
              class="block w-full text-center px-6 py-3 bg-gradient-to-r from-teal-600 to-teal-700 text-white font-semibold rounded-xl hover:shadow-lg hover:from-teal-700 hover:to-teal-800 transition-all duration-200"
            >
              Take Assessment
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

// Mobile menu state
const isMobileMenuOpen = ref(false)

// Toggle mobile menu
const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}

// Close mobile menu
const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
}

// Close mobile menu when clicking outside
const handleClickOutside = (event: Event) => {
  const target = event.target as HTMLElement
  const nav = document.querySelector('nav')
  if (nav && !nav.contains(target)) {
    closeMobileMenu()
  }
}

// Close mobile menu on escape key
const handleEscapeKey = (event: KeyboardEvent) => {
  if (event.key === 'Escape') {
    closeMobileMenu()
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
  document.addEventListener('keydown', handleEscapeKey)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
  document.removeEventListener('keydown', handleEscapeKey)
})
</script>

<style scoped>
.nav-link {
  position: relative;
}

.nav-link::after {
  content: '';
  position: absolute;
  bottom: -4px;
  left: 0;
  right: 0;
  height: 2px;
  background: linear-gradient(90deg, #0d9488, #0f766e);
  transform: scaleX(0);
  transition: transform 0.2s ease;
}

.nav-link:hover::after,
.nav-link.router-link-active::after {
  transform: scaleX(1);
}
</style>