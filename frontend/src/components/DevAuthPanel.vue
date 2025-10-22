<template>
  <div v-if="isDev" class="fixed bottom-4 right-4 z-[9999]">
    <!-- Toggle Button -->
    <button 
      v-if="!isOpen"
      @click="isOpen = true"
      class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-full shadow-lg flex items-center gap-2 font-medium"
    >
      <span>🔧</span>
      <span>Dev Tools</span>
    </button>

    <!-- Dev Panel -->
    <div 
      v-else
      class="bg-white rounded-2xl shadow-2xl border-2 border-purple-200 w-80 overflow-hidden"
    >
      <!-- Header -->
      <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span>🔧</span>
          <h3 class="font-bold">Dev Auth Tools</h3>
        </div>
        <button 
          @click="isOpen = false"
          class="text-white hover:bg-white/20 rounded-lg p-1 transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Content -->
      <div class="p-4 space-y-4">
        <!-- Current Status -->
        <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
          <p class="text-xs font-semibold text-gray-600 mb-2">CURRENT STATUS</p>
          <div class="space-y-1">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Auth Bypass:</span>
              <span :class="bypassEnabled ? 'text-green-600 font-bold' : 'text-red-600 font-bold'">
                {{ bypassEnabled ? 'ENABLED' : 'DISABLED' }}
              </span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Role:</span>
              <span class="font-mono font-bold text-purple-600">{{ currentRole }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Token:</span>
              <span class="font-mono text-xs text-gray-500">{{ hasToken ? '✓ Set' : '✗ None' }}</span>
            </div>
          </div>
        </div>

        <!-- Quick Role Switch -->
        <div>
          <p class="text-xs font-semibold text-gray-600 mb-2">QUICK ROLE SWITCH</p>
          <div class="grid grid-cols-3 gap-2">
            <button
              @click="setRole('user')"
              :class="[
                'px-3 py-2 rounded-lg text-sm font-medium transition-all',
                currentRole === 'user'
                  ? 'bg-blue-600 text-white shadow-md'
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
              ]"
            >
              User
            </button>
            <button
              @click="setRole('therapist')"
              :class="[
                'px-3 py-2 rounded-lg text-sm font-medium transition-all',
                currentRole === 'therapist'
                  ? 'bg-emerald-600 text-white shadow-md'
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
              ]"
            >
              Therapist
            </button>
            <button
              @click="setRole('admin')"
              :class="[
                'px-3 py-2 rounded-lg text-sm font-medium transition-all',
                currentRole === 'admin'
                  ? 'bg-purple-600 text-white shadow-md'
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
              ]"
            >
              Admin
            </button>
          </div>
        </div>

        <!-- Quick Navigation -->
        <div>
          <p class="text-xs font-semibold text-gray-600 mb-2">QUICK NAVIGATION</p>
          <div class="space-y-1">
            <button
              @click="navigateTo('/therapist')"
              class="w-full px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-sm font-medium text-left transition-colors"
            >
              → Therapist Dashboard
            </button>
            <button
              @click="navigateTo('/dashboard/user')"
              class="w-full px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-sm font-medium text-left transition-colors"
            >
              → User Dashboard
            </button>
            <button
              @click="navigateTo('/admin')"
              class="w-full px-3 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg text-sm font-medium text-left transition-colors"
            >
              → Admin Dashboard
            </button>
          </div>
        </div>

        <!-- Auth Actions -->
        <div>
          <p class="text-xs font-semibold text-gray-600 mb-2">ACTIONS</p>
          <div class="space-y-2">
            <button
              @click="mockLogin"
              class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors"
            >
              Mock Login
            </button>
            <button
              @click="clearAll"
              class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors"
            >
              Clear All & Logout
            </button>
          </div>
        </div>

        <!-- Info -->
        <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
          <p class="text-xs text-amber-800">
            <strong>⚠️ Dev Mode Only:</strong> This panel only appears in development. 
            Auth bypass is currently <strong>{{ bypassEnabled ? 'ENABLED' : 'DISABLED' }}</strong>.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// State
const isDev = ref(import.meta.env.DEV)
const isOpen = ref(false)
const bypassEnabled = ref(true) // Should match DEV_BYPASS_AUTH in router

// Computed
const currentRole = computed(() => {
  return localStorage.getItem('DEV_MOCK_ROLE') || 'therapist'
})

const hasToken = computed(() => {
  return !!localStorage.getItem('authToken')
})

// Methods
const setRole = (role: string) => {
  localStorage.setItem('DEV_MOCK_ROLE', role)
  localStorage.setItem('DEV_MOCK_AUTH', 'true')
  
  // Also set regular auth for consistency
  localStorage.setItem('userRole', role)
  
  console.log(`✅ Dev role set to: ${role}`)
  
  // Navigate to appropriate dashboard
  if (role === 'therapist') {
    router.push('/therapist')
  } else if (role === 'admin') {
    router.push('/admin')
  } else {
    router.push('/dashboard/user')
  }
}

const mockLogin = () => {
  const role = currentRole.value
  
  // Set mock auth tokens
  localStorage.setItem('authToken', 'dev-mock-token-' + Date.now())
  localStorage.setItem('userSession', Date.now().toString())
  localStorage.setItem('userRole', role)
  localStorage.setItem('DEV_MOCK_AUTH', 'true')
  localStorage.setItem('DEV_MOCK_ROLE', role)
  localStorage.setItem('userData', JSON.stringify({
    id: '1',
    name: role === 'therapist' ? 'Dr. Emily Johnson' : 'John Doe',
    email: `${role}@mindwell.com`,
    role: role
  }))
  
  console.log(`✅ Mock login successful as ${role}`)
  
  // Navigate based on role
  if (role === 'therapist') {
    router.push('/therapist')
  } else if (role === 'admin') {
    router.push('/admin')
  } else {
    router.push('/dashboard/user')
  }
}

const clearAll = () => {
  localStorage.clear()
  sessionStorage.clear()
  console.log('🗑️ All storage cleared')
  router.push('/login')
}

const navigateTo = (path: string) => {
  router.push(path)
}

// Keyboard shortcut to toggle panel
onMounted(() => {
  const handleKeyPress = (e: KeyboardEvent) => {
    // Ctrl/Cmd + Shift + D to toggle dev panel
    if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
      e.preventDefault()
      isOpen.value = !isOpen.value
    }
  }
  
  window.addEventListener('keydown', handleKeyPress)
  
  return () => {
    window.removeEventListener('keydown', handleKeyPress)
  }
})
</script>