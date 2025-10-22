<template>
  <div id="app">
    <!-- Router View with smooth transitions -->
    <router-view v-slot="{ Component, route }" name="navbar">
    <DevAuthPanel />
      <transition name="fade" mode="out-in">
        <component :is="Component" :key="route.path" v-if="!route.meta?.hideLayout" />
      </transition>
    </router-view>
    
    <main>
      <router-view v-slot="{ Component, route }">
        <transition
          :name="getTransitionName(route)"
          mode="out-in"
          @enter="onEnter"
          @leave="onLeave"
        >
          <component :is="Component" :key="route.path" />
        </transition>
      </router-view>
    </main>
    
    <router-view v-slot="{ Component, route }" name="footer">
      <transition name="fade" mode="out-in">
        <component :is="Component" :key="route.path" v-if="!route.meta?.hideLayout" />
      </transition>
    </router-view>
    
    <!-- Loading overlay for route transitions -->
    <transition name="fade">
      <div 
        v-if="isRouteLoading" 
        class="fixed inset-0 bg-white/80 backdrop-blur-sm z-50 flex items-center justify-center"
      >
        <div class="flex flex-col items-center space-y-4">
          <div class="w-12 h-12 border-4 border-teal-200 border-t-teal-600 rounded-full animate-spin"></div>
          <p class="text-gray-600 font-medium">Loading...</p>
        </div>
      </div>
    </transition>
  </div>
</template>
<script setup lang="ts">
import { ref, nextTick } from 'vue'
import { useRouter, RouteLocationNormalized } from 'vue-router'
import DevAuthPanel from './components/DevAuthPanel.vue'

const router = useRouter()
const isRouteLoading = ref(false)

// Transition names based on routes
const getTransitionName = (route: RouteLocationNormalized): string => {
  const routeName = route.name as string
  
  // Authentication flow transitions (smooth fade)
  if (['login', 'signup', 'reset-password'].includes(routeName)) {
    return 'auth-fade'
  }
  
  // Dashboard and protected routes (fade)
  if (['dashboard', 'profile', 'appointments'].includes(routeName)) {
    return 'dashboard-fade'
  }
  
  // Default page transition
  return 'page-fade'
}

// Transition hooks
const onEnter = (el: Element) => {
  nextTick(() => {
    isRouteLoading.value = false
  })
}

const onLeave = (el: Element) => {
  isRouteLoading.value = true
}

// Listen to router events for loading states
router.beforeEach(() => {
  isRouteLoading.value = true
})

router.afterEach(() => {
  setTimeout(() => {
    isRouteLoading.value = false
  }, 100)
})
</script>

<style>
/* Global styles and transitions */
* {
  box-sizing: border-box;
}


/* Page transition styles - FIXED: Removed transforms that break absolute positioning */
.page-fade-enter-active,
.page-fade-leave-active {
  transition: opacity 0.4s ease;
}

.page-fade-enter-from,
.page-fade-leave-to {
  opacity: 0;
}

/* Authentication flow transitions - FIXED: Pure opacity transition */
.auth-fade-enter-active,
.auth-fade-leave-active {
  transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.auth-fade-enter-from,
.auth-fade-leave-to {
  opacity: 0;
}

/* Dashboard fade transition - FIXED: Removed transform that breaks positioning */
.dashboard-fade-enter-active {
  transition: opacity 0.6s ease;
}

.dashboard-fade-leave-active {
  transition: opacity 0.3s ease;
}

.dashboard-fade-enter-from,
.dashboard-fade-leave-to {
  opacity: 0;
}

/* Layout component transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Enhanced focus styles for accessibility */
*:focus-visible {
  outline: 2px solid #0d9488;
  outline-offset: 2px;
  border-radius: 6px;
}

/* Custom scrollbar styling */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Print styles */
@media print {
  .no-print,
  button,
  [role="button"] {
    display: none !important;
  }
}

/* Reduced motion preferences */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
  button {
    border: 2px solid currentColor;
  }
  
  input,
  textarea {
    border: 2px solid currentColor;
  }
}

/* Dark mode preparation (optional) */
@media (prefers-color-scheme: dark) {
  /* Add dark mode styles if needed */
}
</style>