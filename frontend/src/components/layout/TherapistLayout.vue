<template>
  <div class="min-h-screen bg-gradient-to-br from-emerald-50/30 via-white to-teal-50/30">
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
          <div class="w-10 h-10 bg-emerald-600 rounded-2xl flex items-center justify-center flex-shrink-0">
            <Stethoscope class="w-6 h-6 text-white" />
          </div>
          <div class="transition-opacity duration-200">
            <h1 class="text-lg font-bold text-gray-900">Therapist Portal</h1>
            <p class="text-xs text-gray-500">MindWell Platform</p>
          </div>
        </div>
        <!-- Collapse Button -->
        <button 
          @click="sidebarCollapsed = !sidebarCollapsed"
          :class="[
            'ml-auto p-2 text-gray-400 hover:text-emerald-600 transition-colors',
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
          <!-- Dynamic navigation items before calendar -->
          <button
            v-for="item in navItemsBeforeCalendar"
            :key="item.name"
            @click="setActiveComponent(item.name)"
            :title="sidebarCollapsed ? item.title : ''"
            :class="[
              'w-full flex items-center px-3 py-3 text-left text-sm font-medium rounded-xl border border-transparent transition-all duration-200',
              activeComponent === item.name ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'text-gray-600 hover:bg-emerald-50'
            ]"
          >
            <component :is="item.icon" class="w-5 h-5 flex-shrink-0" :class="sidebarCollapsed ? 'mx-auto' : 'mr-3'" />
            <span v-if="!sidebarCollapsed" class="truncate flex-1">{{ item.title }}</span>
            <span v-if="item.badge && !sidebarCollapsed" class="ml-auto px-2 py-1 text-xs rounded-full" :class="item.badgeClass">
              {{ item.badge }}
            </span>
          </button>

          <!-- Calendar as a router-link -->
          <router-link
            to="/therapist/calendar"
            :title="sidebarCollapsed ? 'Calendar' : ''"
            :class="[
              'w-full flex items-center px-3 py-3 text-left text-sm font-medium rounded-xl border border-transparent transition-all duration-200',
              isCalendarActive ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'text-gray-600 hover:bg-emerald-50'
            ]"
            @click="isMobileMenuOpen = false"
          >
            <Calendar class="w-5 h-5 flex-shrink-0" :class="sidebarCollapsed ? 'mx-auto' : 'mr-3'" />
            <span v-if="!sidebarCollapsed" class="truncate flex-1">Calendar</span>
          </router-link>

          <!-- Dynamic navigation items after calendar -->
          <button
            v-for="item in navItemsAfterCalendar"
            :key="item.name"
            @click="setActiveComponent(item.name)"
            :title="sidebarCollapsed ? item.title : ''"
            :class="[
              'w-full flex items-center px-3 py-3 text-left text-sm font-medium rounded-xl border border-transparent transition-all duration-200',
              activeComponent === item.name ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'text-gray-600 hover:bg-emerald-50'
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
                class="lg:hidden p-2 text-gray-400 hover:text-emerald-600 transition-colors"
              >
                <Menu class="w-6 h-6" />
              </button>
              
              <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ currentSectionTitle }}</h1>
                <p class="text-sm text-gray-600 mt-1">{{ currentSectionDescription }}</p>
              </div>
            </div>
            
            <div class="flex items-center space-x-4">
              <!-- Emergency Protocol -->
              <button 
                @click="startEmergencyProtocol" 
                class="px-3 py-2 bg-red-100 text-red-700 rounded-xl hover:bg-red-200 transition-colors font-medium text-sm"
              >
                Emergency Protocol
              </button>
              
              <!-- Status Toggle -->
              <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">Status:</span>
                <button 
                  @click="toggleAvailability"
                  class="px-3 py-1 rounded-full text-xs font-medium"
                  :class="isAvailable ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                >
                  {{ isAvailable ? 'Available' : 'Offline' }}
                </button>
              </div>
              
              <!-- Notifications -->
              <div class="relative">
                <button 
                  @click="toggleNotificationsDropdown"
                  class="relative p-2 text-gray-400 hover:text-emerald-600 transition-colors"
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
                      class="px-4 py-3 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors cursor-pointer border-b border-gray-100 last:border-b-0"
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
                  <div class="w-8 h-8 rounded-full border-2 border-emerald-200 bg-emerald-600 text-white flex items-center justify-center text-sm font-medium">
                    {{ therapistInitials }}
                  </div>
                </button>
                
                <!-- Profile Dropdown -->
                <div 
                  v-if="showProfileDropdown"
                  class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50"
                >
                  <div class="px-4 py-2 border-b border-gray-100">
                    <p class="text-sm font-medium text-gray-700">{{ therapistProfile.name }}</p>
                    <p class="text-xs text-gray-500">{{ therapistProfile.email }}</p>
                  </div>
                  <button
                    @click="setActiveComponent('profile')"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700"
                  >
                    Profile Settings
                  </button>
                  <button
                    @click="setActiveComponent('account')"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700"
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
        <!-- Router view for calendar (with callback) -->
        <router-view v-if="isCalendarRoute" />
        
        <!-- Dynamic component rendering for all other views -->
        <transition
          v-else
          name="dashboard-fade"
          mode="out-in"
        >
          <component :is="componentMap[activeComponent]" :key="activeComponent" />
        </transition>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { 
  Stethoscope, Bell, Home, Calendar, Menu, ChevronLeft, ChevronRight, LogOut, 
  Users, FileText, ClipboardList, MessageCircle, BookOpen
} from 'lucide-vue-next'
import { logout } from '../../utils/auth'
import { defineAsyncComponent } from 'vue'

// Async components for dynamic rendering (excluding calendar)
const componentMap = {
  dashboard: defineAsyncComponent(() => import('../../views/dashboard/TherapistDashboard.vue')),
  clients: defineAsyncComponent(() => import('../../views/therapist/Clients.vue')),
  resources: defineAsyncComponent(() => import('../../views/therapist/Resources.vue')),
  'session-notes': defineAsyncComponent(() => import('../../views/therapist/SessionNotes.vue')),
  tasks: defineAsyncComponent(() => import('../../views/therapist/Tasks.vue')),
  chat: defineAsyncComponent(() => import('../../views/therapist/Chat.vue')),
  profile: defineAsyncComponent(() => import('../../views/therapist/Profile.vue')),
  account: defineAsyncComponent(() => import('../../views/therapist/Account.vue'))
}

const router = useRouter()
const route = useRoute()

// Reactive state
const sidebarCollapsed = ref(false)
const isMobileMenuOpen = ref(false)
const isAvailable = ref(true)
const notifications = ref(5)
const showNotificationsDropdown = ref(false)
const showProfileDropdown = ref(false)
const activeComponent = ref('dashboard')

// Notification list
const notificationList = ref([
  { id: 1, title: 'New message from Sarah Johnson', time: '10 minutes ago' },
  { id: 2, title: 'Session reminder: Mike Chen at 3:30 PM', time: '30 minutes ago' },
  { id: 3, title: 'Task due: Follow-up notes', time: '1 hour ago' },
  { id: 4, title: 'New resource added to library', time: '2 hours ago' },
  { id: 5, title: 'Client update requested', time: '1 day ago' }
])

// Navigation items split around calendar
const navItemsBeforeCalendar = ref([
  { name: 'dashboard', title: 'Dashboard', icon: Home },
  { name: 'clients', title: 'Clients', icon: Users }
])

const navItemsAfterCalendar = ref([
  { name: 'session-notes', title: 'Session Notes', icon: FileText },
  { name: 'tasks', title: 'Tasks/Reminders', icon: ClipboardList },
  { name: 'chat', title: 'Messaging', icon: MessageCircle },
  { name: 'resources', title: 'Resources', icon: BookOpen }
])

// Section configuration
const sectionTitles: Record<string, string> = {
  dashboard: 'Dashboard',
  clients: 'Clients',
  resources: 'Resources',
  'session-notes': 'Session Notes',
  tasks: 'Tasks/Reminders',
  chat: 'Messaging',
  profile: 'Profile Settings',
  account: 'Account Settings',
  calendar: 'Calendar'
}

const sectionDescriptions: Record<string, string> = {
  dashboard: 'Manage your clients and sessions',
  clients: 'View and manage your clients',
  resources: 'Access wellness resources and materials',
  'session-notes': 'View and edit session notes',
  tasks: 'Manage your tasks and reminders',
  chat: 'Communicate with clients',
  profile: 'Manage your therapist profile settings',
  account: 'Manage your account details',
  calendar: 'View and manage your schedule'
}

// Check if current route is calendar-related
const isCalendarRoute = computed(() => {
  return route.name === 'therapist-calendar' || route.name === 'calendly-callback'
})

// Check if calendar is active (for styling)
const isCalendarActive = computed(() => {
  return isCalendarRoute.value
})

// Compute current section title and description
const currentSectionTitle = computed(() => {
  if (isCalendarRoute.value) {
    return sectionTitles['calendar']
  }
  return sectionTitles[activeComponent.value] || 'Dashboard'
})

const currentSectionDescription = computed(() => {
  if (isCalendarRoute.value) {
    return sectionDescriptions['calendar']
  }
  return sectionDescriptions[activeComponent.value] || 'Manage your clients and sessions'
})

// Therapist profile
const therapistProfile = ref({
  name: 'Dr. Emily Johnson',
  email: 'emily.johnson@mindwell.com'
})

// Compute initials
const therapistInitials = computed(() => {
  const names = therapistProfile.value.name.trim().split(' ')
  return names.length > 1
    ? `${names[0][0]}${names[names.length - 1][0]}`
    : names[0][0] || 'EJ'
})

// Watch for route changes to reset active component when navigating away from calendar
watch(() => route.name, (newRouteName) => {
  // When navigating to base therapist route, reset to dashboard
  if (newRouteName === 'therapist') {
    activeComponent.value = 'dashboard'
  }
})

// Methods
const setActiveComponent = (name: string) => {
  activeComponent.value = name
  showProfileDropdown.value = false
  isMobileMenuOpen.value = false
  
  // Navigate to base therapist route when selecting dynamic components
  if (route.path !== '/therapist') {
    router.push('/therapist')
  }
}

const toggleAvailability = () => {
  isAvailable.value = !isAvailable.value
  // API call to update status
}

const startEmergencyProtocol = () => {
  alert('Emergency protocol activated')
}

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

const handleLogout = async () => {
  showProfileDropdown.value = false
  await logout(router)
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
  background: #047857;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #065f46;
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