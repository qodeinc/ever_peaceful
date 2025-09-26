<template>
  <div class="min-h-screen bg-gradient-to-br from-teal-50/30 via-white to-emerald-50/30">
    <!-- Sidebar -->
    <div 
      :class="[
        'fixed inset-y-0 left-0 z-50 bg-white shadow-xl border-r border-gray-200 transition-all duration-300',
        sidebarCollapsed ? 'w-16' : 'w-64',
        isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
      ]"
    >
      <!-- Sidebar Header -->
      <div class="flex items-center h-20 px-4 border-b border-gray-200">
        <div v-if="!sidebarCollapsed" class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-teal-600 rounded-2xl flex items-center justify-center flex-shrink-0">
            <Brain class="w-6 h-6 text-white" />
          </div>
          <div class="transition-opacity duration-200">
            <h1 class="text-lg font-bold text-gray-900">MindWell Dashboard</h1>
            <p class="text-xs text-gray-500">Your Wellness Journey</p>
          </div>
        </div>
        <!-- Collapse Button -->
        <button 
          @click="sidebarCollapsed = !sidebarCollapsed"
          :class="[
            'ml-auto p-2 text-gray-400 hover:text-teal-600 transition-colors',
            sidebarCollapsed ? 'mx-auto' : ''
          ]"
        >
          <ChevronLeft v-if="!sidebarCollapsed" class="w-5 h-5" />
          <ChevronRight v-else class="w-5 h-5" />
        </button>
      </div>

      <!-- Navigation - Scrollable -->
      <nav class="mt-6 px-2 h-[calc(100vh-5rem)] overflow-y-auto">
        <div class="space-y-2">
          <button
            v-for="item in navItems"
            :key="item.name"
            @click="setActiveComponent(item.name)"
            :title="sidebarCollapsed ? item.title : ''"
            :class="[
              'w-full flex items-center px-3 py-3 text-left text-sm font-medium rounded-xl border border-transparent transition-all duration-200',
              activeComponent === item.name ? 'bg-teal-100 text-teal-700 border-teal-200' : 'text-gray-600 hover:bg-teal-50'
            ]"
          >
            <component :is="item.icon" class="w-5 h-5 flex-shrink-0" :class="sidebarCollapsed ? 'mx-auto' : 'mr-3'" />
            <span v-if="!sidebarCollapsed" class="truncate flex-1">{{ item.title }}</span>
            <span v-if="item.badge && !sidebarCollapsed" class="ml-auto px-2 py-1 text-xs rounded-full" :class="item.badgeClass">
              {{ item.badge }}
            </span>
          </button>
        </div>

        <!-- Logout Button -->
        <button
          v-if="!sidebarCollapsed"
          @click="handleLogout"
          class="w-full flex items-center px-3 py-3 text-left text-sm font-medium rounded-xl border-t border-gray-200 text-red-600 hover:bg-red-50 hover:text-red-700 transition-all duration-200 mt-2"
        >
          <LogOut class="w-5 h-5 flex-shrink-0 mr-3" />
          <span class="truncate flex-1">Logout</span>
        </button>
        <button
          v-else
          @click="handleLogout"
          :title="'Logout'"
          class="w-full flex items-center px-3 py-3 text-left text-sm font-medium rounded-xl border-t border-gray-200 text-red-600 hover:bg-red-50 hover:text-red-700 transition-all duration-200 mt-2"
        >
          <LogOut class="w-5 h-5 flex-shrink-0 mx-auto" />
        </button>
      </nav>
    </div>

    <!-- Main Content Area -->
    <div :class="['transition-all duration-300', sidebarCollapsed ? 'ml-16' : 'ml-64']">
      <!-- Top Navigation Bar -->
      <header class="bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-md sticky top-0 z-40 h-20 flex items-center">
        <div class="px-6 w-full">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <button 
                @click="isMobileMenuOpen = !isMobileMenuOpen"
                class="lg:hidden p-2 text-gray-400 hover:text-teal-600 transition-colors"
              >
                <Menu class="w-6 h-6" />
              </button>
              
              <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ currentSectionTitle }}</h1>
                <p class="text-sm text-gray-600 mt-1">{{ currentSectionDescription }}</p>
              </div>
            </div>
            
            <div class="flex items-center space-x-4">
              <!-- Notifications -->
              <div class="relative">
                <button 
                  @click="toggleNotificationsDropdown"
                  class="relative p-2 text-gray-400 hover:text-teal-600 transition-colors"
                >
                  <Bell class="w-5 h-5" />
                  <span 
                    v-if="notifications > 0" 
                    class="absolute top-0 right-0 w-5 h-5 bg-red-500 rounded-full text-xs text-white flex items-center justify-center border-2 border-white"
                  >
                    {{ notifications > 9 ? '9+' : notifications }}
                  </span>
                </button>
                
                <!-- Notifications Dropdown -->
                <div 
                  v-if="showNotificationsDropdown"
                  class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50 max-h-96 overflow-y-auto"
                >
                  <div v-if="notificationList.length > 0" class="space-y-2">
                    <div 
                      v-for="notification in notificationList" 
                      :key="notification.id"
                      class="px-4 py-3 text-sm text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition-colors cursor-pointer border-b border-gray-100 last:border-b-0"
                      @click="handleNotificationClick(notification.id)"
                    >
                      <p class="font-medium">{{ notification.title }}</p>
                      <p class="text-xs text-gray-500">{{ notification.time }}</p>
                    </div>
                  </div>
                  <div v-else class="px-4 py-3 text-sm text-gray-500 text-center">
                    No new notifications
                  </div>
                </div>
              </div>

              <!-- Profile Dropdown with Avatar Initials -->
              <div class="relative">
                <button 
                  @click="toggleProfileDropdown"
                  class="flex items-center space-x-3 focus:outline-none"
                >
                  <div class="w-8 h-8 rounded-full border-2 border-teal-200 bg-teal-600 text-white flex items-center justify-center text-sm font-medium">
                    {{ userInitials }}
                  </div>
                </button>
                
                <!-- Profile Dropdown -->
                <div 
                  v-if="showProfileDropdown"
                  class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50"
                >
                  <div class="px-4 py-2 border-b border-gray-100">
                    <p class="text-sm font-medium text-gray-700">{{ userProfile.name }}</p>
                    <p class="text-xs text-gray-500">{{ userProfile.email }}</p>
                  </div>
                  <button
                    @click="setActiveComponent('profile')"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-teal-50 hover:text-teal-700"
                  >
                    Profile Settings
                  </button>
                  <button
                    @click="setActiveComponent('account')"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-teal-50 hover:text-teal-700"
                  >
                    Account
                  </button>
                  <button
                    @click="handleLogout"
                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"
                  >
                    Logout
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Content Area with Transition -->
      <main class="p-6">
        <transition
          name="dashboard-fade"
          mode="out-in"
          @enter="onEnter"
          @leave="onLeave"
        >
          <component :is="componentMap[activeComponent]" :key="activeComponent" :sessionId="sessionId" />
        </transition>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, defineAsyncComponent } from 'vue'
import { useRouter } from 'vue-router'
import { 
  Brain, Bell, Calendar, BookOpen, MessageCircle, TrendingUp, 
  Menu, ChevronLeft, ChevronRight, LogOut, Home, User, Settings, FileText 
} from 'lucide-vue-next'
import { useDashboardNavigation } from '../../composables/useDashboardNavigation'

// Async components for dynamic rendering
const componentMap = {
  dashboard: defineAsyncComponent(() => import('../../views/dashboard/UserDashboard.vue')),
  'book-session': defineAsyncComponent(() => import('../../views/user/BookSession.vue')),
  resources: defineAsyncComponent(() => import('../../views/user/Resources.vue')),
  chat: defineAsyncComponent(() => import('../../views/user/Chat.vue')),
  progress: defineAsyncComponent(() => import('../../views/user/Progress.vue')),
  profile: defineAsyncComponent(() => import('../../views/user/Profile.vue')),
  account: defineAsyncComponent(() => import('../../views/user/Account.vue')),
  'session-details': defineAsyncComponent(() => import('../../views/user/SessionDetails.vue'))
}

const router = useRouter()

// Navigation composable
const { activeComponent, sessionId, setActiveComponent } = useDashboardNavigation()

// Reactive state
const sidebarCollapsed = ref(false)
const isMobileMenuOpen = ref(false)
const notifications = ref(3)
const showNotificationsDropdown = ref(false)
const showProfileDropdown = ref(false)
const unreadMessages = ref(2)

// User profile
const userProfile = ref({
  name: 'Sarah Johnson',
  email: 'sarah.johnson@mindwell.com'
})

// Compute initials
const userInitials = computed(() => {
  const names = userProfile.value.name.trim().split(' ')
  return names.length > 1
    ? `${names[0][0]}${names[names.length - 1][0]}`
    : names[0][0] || 'SJ'
})

// Notification list
const notificationList = ref([
  { id: 1, title: 'New session scheduled with Dr. Chen', time: '5 min ago' },
  { id: 2, title: 'Message received from therapist', time: '15 min ago' },
  { id: 3, title: 'New wellness resource available', time: '1 hour ago' }
])

// Navigation items
const navItems = ref([
  { name: 'dashboard', title: 'Dashboard', icon: Home },
  { name: 'book-session', title: 'Book Session', icon: Calendar },
  { name: 'resources', title: 'Resources', icon: BookOpen },
  { name: 'chat', title: 'Chat', icon: MessageCircle, badge: unreadMessages.value, badgeClass: 'bg-red-100 text-red-600' },
  { name: 'progress', title: 'Progress', icon: TrendingUp },
  { name: 'profile', title: 'Profile', icon: User },
  { name: 'account', title: 'Account', icon: Settings },
  { name: 'session-details', title: 'Session Details', icon: FileText }
])

// Section configuration
const sectionTitles: Record<string, string> = {
  dashboard: 'Dashboard',
  'book-session': 'Book Session',
  resources: 'Resources',
  chat: 'Chat',
  progress: 'Progress',
  profile: 'Profile Settings',
  account: 'Account Settings',
  'session-details': 'Session Details'
}

const sectionDescriptions: Record<string, string> = {
  dashboard: 'Your wellness journey overview',
  'book-session': 'Schedule a session with your therapist',
  resources: 'Access wellness content and resources',
  chat: 'Message your therapist',
  progress: 'Track your wellness journey',
  profile: 'Manage your profile settings',
  account: 'Manage your account details',
  'session-details': 'View session details'
}

// Compute current section title and description
const currentSectionTitle = computed(() => {
  return sectionTitles[activeComponent.value] || 'Dashboard'
})

const currentSectionDescription = computed(() => {
  return sectionDescriptions[activeComponent.value] || 'Your wellness journey overview'
})

// Methods
const toggleNotificationsDropdown = () => {
  showNotificationsDropdown.value = !showNotificationsDropdown.value
  showProfileDropdown.value = false
}

const toggleProfileDropdown = () => {
  showProfileDropdown.value = !showProfileDropdown.value
  showNotificationsDropdown.value = false
}

const handleNotificationClick = (id: number) => {
  showNotificationsDropdown.value = false
  console.log(`Notification ${id} clicked`)
  // Add navigation or action logic
}

const handleLogout = () => {
  showProfileDropdown.value = false
  router.push('/login')
}

const onEnter = () => {
  // Handled in App.vue
}

const onLeave = () => {
  // Handled in App.vue
}
</script>

<style scoped>
/* Enhanced animations */
@keyframes fadeInRight {
  from {
    opacity: 0;
    transform: translateX(20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fadeInRight {
  animation: fadeInRight 0.6s ease-out;
}

.animate-fadeInUp {
  animation: fadeInUp 0.6s ease-out;
}

/* Sidebar styling */
.sidebar-nav button {
  transition: all 0.2s ease;
}

.sidebar-nav button:hover {
  transform: translateX(2px);
}

/* Content area styling */
main {
  animation: fadeInRight 0.6s ease-out;
}

/* Button styling */
button {
  position: relative;
  overflow: hidden;
}

button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s;
}

button:hover::before {
  left: 100%;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 4px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #0d9488;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #0f766e;
}

/* Smooth transitions for sidebar */
.transition-all {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Mobile responsiveness */
@media (max-width: 1024px) {
  .sidebar {
    transform: translateX(-100%);
  }
  
  .sidebar.open {
    transform: translateX(0);
  }
}

/* Dropdown styling */
.profile-dropdown, .notifications-dropdown {
  animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Transition styles */
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
</style>