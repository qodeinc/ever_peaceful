<template>
  <div class="min-h-screen bg-gradient-to-br from-teal-50/30 via-white to-cyan-50/30">
    <!-- Sidebar -->
    <div 
      :class="[
        'fixed inset-y-0 left-0 z-50 bg-white shadow-xl border-r border-gray-200 transition-all duration-300',
        sidebarCollapsed ? 'w-16' : 'w-64'
      ]"
    >
      <!-- Sidebar Header -->
      <div class="flex items-center h-20 px-4 border-b border-gray-200">
        <div v-if="!sidebarCollapsed" class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-teal-600 rounded-2xl flex items-center justify-center flex-shrink-0">
            <Shield class="w-6 h-6 text-white" />
          </div>
          <div class="transition-opacity duration-200">
            <h1 class="text-lg font-bold text-gray-900">Admin Portal</h1>
            <p class="text-xs text-gray-500">MindWell Platform</p>
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
              activeComponent === item.name ? 'bg-teal-100 text-teal-700 border-teal-200' : 'text-gray-600 hover:bg-gray-50'
            ]"
          >
            <component :is="item.icon" class="w-5 h-5 flex-shrink-0" :class="sidebarCollapsed ? 'mx-auto' : 'mr-3'" />
            <span v-if="!sidebarCollapsed" class="truncate flex-1">{{ item.title }}</span>
            <span v-if="item.badge && !sidebarCollapsed" class="ml-auto px-2 py-1 text-xs rounded-full" :class="item.badgeClass">
              {{ item.badge }}
            </span>
          </button>
        </div>

        <!-- System Status -->
        <div v-if="!sidebarCollapsed" class="mt-8 p-4 bg-gray-50 rounded-xl">
          <h3 class="text-sm font-medium text-gray-900 mb-3">System Status</h3>
          <div class="space-y-2">
            <div class="flex items-center justify-between text-xs">
              <span class="text-gray-600">Server Health</span>
              <div class="flex items-center space-x-1">
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                <span class="text-green-600 font-medium">Healthy</span>
              </div>
            </div>
            <div class="flex items-center justify-between text-xs">
              <span class="text-gray-600">Active Users</span>
              <span class="font-medium text-gray-900">1,247</span>
            </div>
            <div class="flex items-center justify-between text-xs">
              <span class="text-gray-600">Live Sessions</span>
              <span class="font-medium text-gray-900">34</span>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div v-if="!sidebarCollapsed" class="mt-6 p-4 bg-gradient-to-r from-teal-100 to-cyan-100 rounded-xl">
          <h3 class="text-sm font-medium text-gray-900 mb-3">Quick Actions</h3>
          <div class="space-y-2">
            <button class="w-full text-left text-xs text-teal-700 hover:text-teal-900 p-2 rounded-lg hover:bg-white/50 transition-colors">
              Send Platform Announcement
            </button>
            <button class="w-full text-left text-xs text-teal-700 hover:text-teal-900 p-2 rounded-lg hover:bg-white/50 transition-colors">
              Export Monthly Report
            </button>
            <button class="w-full text-left text-xs text-teal-700 hover:text-teal-900 p-2 rounded-lg hover:bg-white/50 transition-colors">
              Schedule Maintenance
            </button>
          </div>
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
                @click="sidebarCollapsed = !sidebarCollapsed"
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
              <!-- Time Range Selector (First) -->
              <select 
                v-model="selectedTimeframe" 
                class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
              >
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="quarter">This Quarter</option>
              </select>

              <!-- Notifications (Second) -->
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

              <!-- Profile Dropdown with Avatar (Last) -->
              <div class="relative">
                <button 
                  @click="toggleProfileDropdown"
                  class="flex items-center space-x-3 focus:outline-none"
                >
                  <div class="w-8 h-8 rounded-full border-2 border-teal-200 bg-teal-600 text-white flex items-center justify-center text-sm font-medium">
                    {{ adminInitials }}
                  </div>
                </button>
                
                <!-- Profile Dropdown -->
                <div 
                  v-if="showProfileDropdown"
                  class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50"
                >
                  <div class="px-4 py-2 border-b border-gray-100">
                    <p class="text-sm font-medium text-gray-700">{{ adminProfile.name }}</p>
                    <p class="text-xs text-gray-500">{{ adminProfile.email }}</p>
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
          <component :is="componentMap[activeComponent]" :key="activeComponent" />
        </transition>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, defineAsyncComponent } from 'vue'
import { useRouter } from 'vue-router'
import { 
  Shield, Bell, BarChart3, Users, Heart, Calendar, Settings,
  BookOpen, MessageCircle, FileText, Menu, ChevronLeft, ChevronRight, LogOut
} from 'lucide-vue-next'

// Async components for dynamic rendering (based on folder tree)
const componentMap = {
  overview: defineAsyncComponent(() => import('../../views/dashboard/AdminDashboard.vue')),
  users: defineAsyncComponent(() => import('../../views/dashboard/pages/UserManagement.vue')),
  therapists: defineAsyncComponent(() => import('../../views/dashboard/pages/TherapistManagement.vue')),
  sessions: defineAsyncComponent(() => import('../../views/dashboard/pages/SessionManagement.vue')),
  content: defineAsyncComponent(() => import('../../views/dashboard/pages/ContentManagement.vue')),
  support: defineAsyncComponent(() => import('../../views/dashboard/pages/SupportTickets.vue')),
  reports: defineAsyncComponent(() => import('../../views/dashboard/pages/ReportsAnalytics.vue')),
  system: defineAsyncComponent(() => import('../../views/dashboard/pages/SystemSettings.vue'))
  //profile: defineAsyncComponent(() => import('../../views/Profile.vue')),
  //account: defineAsyncComponent(() => import('../../views/Account.vue'))
}

const router = useRouter()

// Reactive state
const sidebarCollapsed = ref(false)
const notifications = ref(7)
const selectedTimeframe = ref('month')
const showProfileDropdown = ref(false)
const showNotificationsDropdown = ref(false)
const activeComponent = ref('overview')

// Sidebar indicators
const pendingUsers = ref(12)
const pendingTherapists = ref(3)
const urgentTickets = ref(5)
const systemAlerts = ref(2)

// Notification list
const notificationList = ref([
  {
    id: 1,
    title: 'New therapist application received',
    time: '5 min ago'
  },
  {
    id: 2,
    title: 'User reported an issue with payment',
    time: '15 min ago'
  },
  {
    id: 3,
    title: 'System maintenance scheduled',
    time: '1 hour ago'
  },
  {
    id: 4,
    title: 'High server load detected',
    time: '2 hours ago'
  }
])

// Navigation items
const navItems = ref([
  { name: 'overview', title: 'Overview & Analytics', icon: BarChart3 },
  { name: 'users', title: 'User Management', icon: Users, badge: pendingUsers.value, badgeClass: 'bg-blue-100 text-blue-600' },
  { name: 'therapists', title: 'Therapist Panel', icon: Heart, badge: pendingTherapists.value, badgeClass: 'bg-emerald-100 text-emerald-600' },
  { name: 'sessions', title: 'Sessions & Bookings', icon: Calendar },
  { name: 'content', title: 'Content Management', icon: BookOpen },
  { name: 'support', title: 'Support & Tickets', icon: MessageCircle, badge: urgentTickets.value, badgeClass: 'bg-red-100 text-red-600' },
  { name: 'reports', title: 'Reports & Exports', icon: FileText },
  { name: 'system', title: 'System Settings', icon: Settings, badge: systemAlerts.value, badgeClass: 'bg-orange-100 text-orange-600' },
])

// Section configuration
const sectionTitles: Record<string, string> = {
  overview: 'Dashboard Overview',
  users: 'User Management',
  therapists: 'Therapist Management',
  sessions: 'Session Management',
  content: 'Content Management',
  support: 'Support & Tickets',
  reports: 'Reports & Analytics',
  system: 'System Settings',
  profile: 'Profile Settings',
  account: 'Account Settings'
}

const sectionDescriptions: Record<string, string> = {
  overview: 'Platform analytics and key performance metrics',
  users: 'Manage user accounts, permissions, and activity',
  therapists: 'Handle therapist applications and certifications',
  sessions: 'Monitor and manage therapy sessions',
  content: 'Manage resources, articles, and educational content',
  support: 'Handle support tickets and user inquiries',
  reports: 'Generate reports and export data',
  system: 'Configure system settings and maintenance',
  profile: 'Manage your profile settings',
  account: 'Manage your account details'
}

// Compute current section title and description based on active component
const currentSectionTitle = computed(() => {
  return sectionTitles[activeComponent.value] || 'Dashboard Overview'
})

const currentSectionDescription = computed(() => {
  return sectionDescriptions[activeComponent.value] || 'Platform analytics and key performance metrics'
})

// Admin profile
const adminProfile = ref({
  name: 'Sarah Wilson',
  email: 'sarah.wilson@mindwell.com'
})

// Compute initials from admin name
const adminInitials = computed(() => {
  const names = adminProfile.value.name.trim().split(' ')
  return names.length > 1
    ? `${names[0][0]}${names[names.length - 1][0]}`
    : names[0][0] || 'SW'
})

// Set active component
const setActiveComponent = (name: string) => {
  activeComponent.value = name
  showProfileDropdown.value = false // Close profile dropdown if open
}

// Transition hooks (aligned with App.vue)
const onEnter = () => {
  // Handled in App.vue
}

const onLeave = () => {
  // Handled in App.vue
}

// Toggle profile dropdown
const toggleProfileDropdown = () => {
  showProfileDropdown.value = !showProfileDropdown.value
  showNotificationsDropdown.value = false // Close notifications dropdown if open
}

// Toggle notifications dropdown
const toggleNotificationsDropdown = () => {
  showNotificationsDropdown.value = !showNotificationsDropdown.value
  showProfileDropdown.value = false // Close profile dropdown if open
}

// Handle notification click
const handleNotificationClick = (id: number) => {
  showNotificationsDropdown.value = false
  console.log(`Notification ${id} clicked`)
  // Add navigation or action logic here if needed
}

// Handle logout
const handleLogout = () => {
  showProfileDropdown.value = false
  router.push('/login')
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

.animate-fadeInRight {
  animation: fadeInRight 0.6s ease-out;
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

/* Enhanced button styling */
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
  background: #14b8a6;
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

/* Transition styles from App.vue */
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