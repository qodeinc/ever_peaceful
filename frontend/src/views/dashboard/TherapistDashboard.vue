<template>
  <div class="min-h-screen bg-gradient-to-br from-emerald-50/30 via-white to-teal-50/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Welcome Section -->
      <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-3xl p-8 text-white mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-3xl font-bold mb-2">Good {{ timeOfDay }}, {{ therapistProfile.title }} {{ therapistProfile.lastName }}! 👨‍⚕️</h2>
            <p class="text-emerald-100 text-lg">
              You have {{ overviewStats.sessions }} sessions scheduled today. 
              {{ overviewStats.sessions > 0 ? "Ready to make a difference?" : "Enjoy your lighter day!" }}
            </p>
          </div>
          <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
            <div class="text-2xl font-bold text-white">{{ therapistInitials }}</div>
          </div>
        </div>
      </div>

      <!-- Overview Stats -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Active Clients</p>
              <p class="text-2xl font-bold text-gray-900">{{ overviewStats.activeClients }}</p>
              <p class="text-xs text-green-600 mt-1">+{{ overviewStats.newClients }} new</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
              <Users class="w-6 h-6 text-emerald-600" />
            </div>
          </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Pending Tasks</p>
              <p class="text-2xl font-bold text-gray-900">{{ overviewStats.tasks }}</p>
              <p class="text-xs text-orange-600 mt-1">{{ overviewStats.urgentTasks }} urgent</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center">
              <ClipboardList class="w-6 h-6 text-orange-600" />
            </div>
          </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Unread Messages</p>
              <p class="text-2xl font-bold text-gray-900">{{ overviewStats.messages }}</p>
              <p class="text-xs text-blue-600 mt-1">In Messaging</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
              <MessageCircle class="w-6 h-6 text-blue-600" />
            </div>
          </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Avg. Rating</p>
              <p class="text-2xl font-bold text-gray-900">{{ overviewStats.rating.toFixed(1) }}</p>
              <div class="flex items-center mt-1">
                <Star v-for="n in 5" :key="n" 
                      class="w-3 h-3" 
                      :class="n <= overviewStats.rating ? 'text-yellow-400 fill-current' : 'text-gray-300'" />
              </div>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-2xl flex items-center justify-center">
              <Star class="w-6 h-6 text-yellow-600" />
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Primary Column: Schedule & Updates -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Integrated Schedule & Tasks -->
          <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-xl font-bold text-gray-900">Today's Schedule & Tasks</h3>
              <div class="flex space-x-3">
                <button @click="setActiveComponent('calendar')" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                  Full Calendar
                </button>
                <button @click="setActiveComponent('tasks')" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                  All Tasks
                </button>
              </div>
            </div>
            <div class="space-y-4">
              <!-- Upcoming Sessions -->
              <div v-if="todaysSchedule.length > 0" class="border-b border-gray-100 pb-4">
                <h4 class="font-semibold text-gray-900 mb-2">Upcoming Sessions</h4>
                <div v-for="appointment in todaysSchedule" :key="appointment.id" 
                     class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-emerald-50 transition-colors cursor-pointer"
                     @click="viewClientDetails(appointment.client.id)">
                  <div class="flex items-center space-x-3 flex-1">
                    <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-white font-medium text-sm">
                      {{ appointment.client.initials }}
                    </div>
                    <div>
                      <h5 class="font-medium text-gray-900">{{ appointment.client.name }}</h5>
                      <p class="text-sm text-gray-600">{{ appointment.type }}</p>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="font-medium text-gray-900 text-sm">{{ formatTime(appointment.startTime) }}</p>
                    <span class="px-2 py-1 text-xs rounded-full font-medium mt-1 block"
                          :class="getStatusClass(appointment.status)">
                      {{ appointment.status }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Pending Tasks -->
              <div v-if="pendingTasks.length > 0" class="pt-4">
                <h4 class="font-semibold text-gray-900 mb-2">Pending Tasks</h4>
                <div v-for="task in pendingTasks" :key="task.id" 
                     class="flex items-start justify-between p-3 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors cursor-pointer"
                     @click="setActiveComponent('tasks')">
                  <div class="flex items-center space-x-3 flex-1">
                    <div class="w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center text-white text-xs font-medium">
                      {{ task.initials }}
                    </div>
                    <div>
                      <h5 class="font-medium text-gray-900 text-sm">{{ task.title }}</h5>
                      <p class="text-xs text-gray-600">{{ task.description }}</p>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="text-xs text-gray-500">{{ task.due }}</p>
                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full mt-1 block">
                      Urgent
                    </span>
                  </div>
                </div>
              </div>

              <div v-if="todaysSchedule.length === 0 && pendingTasks.length === 0" class="text-center py-8">
                <Calendar class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                <p class="text-gray-500">Clear schedule</p>
                <p class="text-sm text-gray-400 mt-1">Time for reflection or prep</p>
              </div>
            </div>
          </div>

          <!-- Recent Activity Feed -->
          <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Recent Activity</h3>
            <div class="space-y-4 max-h-80 overflow-y-auto">
              <div v-for="activity in recentActivity" :key="activity.id" 
                   class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer"
                   @click="handleActivityClick(activity.type, activity.id)">
                <div class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center text-white text-xs font-medium mt-0.5">
                  {{ activity.initials }}
                </div>
                <div class="flex-1">
                  <div class="flex items-center justify-between">
                    <h4 class="font-medium text-gray-900 text-sm">{{ activity.title }}</h4>
                    <span class="text-xs text-gray-500">{{ activity.time }}</span>
                  </div>
                  <p class="text-sm text-gray-600 mt-1">{{ activity.description }}</p>
                  <div class="flex items-center mt-2 space-x-2 text-xs">
                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">{{ activity.type }}</span>
                    <span v-if="activity.unread" class="px-2 py-1 bg-blue-100 text-blue-700 rounded">Unread</span>
                  </div>
                </div>
              </div>
              <div v-if="recentActivity.length === 0" class="text-center py-8">
                <MessageCircle class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                <p class="text-gray-500">No recent activity</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Secondary Column: Quick Access & Alerts -->
        <div class="space-y-6">
          <!-- Quick Access Links -->
          <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Access</h3>
            <div class="grid grid-cols-1 gap-3">
              <button @click="setActiveComponent('clients')" 
                      class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors w-full text-left">
                <Users class="w-5 h-5 text-emerald-600" />
                <span class="text-sm font-medium">Manage Clients</span>
              </button>
              <button @click="setActiveComponent('session-notes')" 
                      class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors w-full text-left">
                <FileText class="w-5 h-5 text-blue-600" />
                <span class="text-sm font-medium">Session Notes</span>
              </button>
              <button @click="setActiveComponent('chat')" 
                      class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors w-full text-left">
                <MessageCircle class="w-5 h-5 text-purple-600" />
                <span class="text-sm font-medium">Messaging</span>
                <span v-if="overviewStats.messages > 0" class="ml-auto px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">{{ overviewStats.messages }}</span>
              </button>
              <button @click="setActiveComponent('resources')" 
                      class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors w-full text-left">
                <BookOpen class="w-5 h-5 text-teal-600" />
                <span class="text-sm font-medium">Resources</span>
              </button>
            </div>
          </div>

          <!-- Urgent Alerts -->
          <div v-if="urgentAlerts.length > 0" class="bg-white rounded-2xl p-6 shadow-lg border border-red-200">
            <h3 class="text-xl font-bold text-red-900 mb-4 flex items-center">
              <AlertTriangle class="w-5 h-5 mr-2" />
              Urgent Alerts ({{ urgentAlerts.length }})
            </h3>
            <div class="space-y-3 max-h-48 overflow-y-auto">
              <div v-for="alert in urgentAlerts" :key="alert.id" 
                   class="p-3 bg-red-50 rounded-lg border border-red-100">
                <p class="font-medium text-red-900 text-sm">{{ alert.title }}</p>
                <p class="text-sm text-red-700 mt-1">{{ alert.description }}</p>
                <button @click="handleAlert(alert.id)" 
                        class="mt-2 w-full px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                  Review Now
                </button>
              </div>
            </div>
          </div>

          <!-- Quick Notes -->
          <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Notes</h3>
            <textarea 
              v-model="quickNotes"
              placeholder="Jot down thoughts, reminders..."
              class="w-full p-3 border border-gray-200 rounded-xl resize-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
              rows="3"
            ></textarea>
            <button @click="saveQuickNotes" 
                    :disabled="!quickNotes.trim()"
                    class="mt-3 w-full bg-emerald-600 text-white py-2 px-4 rounded-xl hover:bg-emerald-700 transition-colors font-medium disabled:bg-gray-300 disabled:cursor-not-allowed text-sm">
              Save
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { 
  Calendar, Users, Star, MessageCircle, AlertTriangle, ClipboardList, 
  FileText, BookOpen, RefreshCw, Eye, ChevronLeft, ChevronRight 
} from 'lucide-vue-next'

const router = useRouter()
const emit = defineEmits(['set-active-component'])

// Reactive state
const quickNotes = ref('')
const overviewStats = ref({
  sessions: 3,
  activeClients: 28,
  newClients: 2,
  tasks: 5,
  urgentTasks: 1,
  messages: 3,
  rating: 4.7
})

const todaysSchedule = ref([
  {
    id: 1,
    client: { 
      id: 1,
      name: 'Wanjiku Mwangi', 
      initials: 'WM'
    },
    type: 'Individual Therapy',
    startTime: new Date('2025-10-01T14:00:00'),
    status: 'upcoming',
    sessionNumber: 5
  },
  {
    id: 2,
    client: { 
      id: 2,
      name: 'Ochieng Kiprop', 
      initials: 'OK'
    },
    type: 'Follow-up Session',
    startTime: new Date('2025-10-01T16:00:00'),
    status: 'upcoming',
    sessionNumber: 12
  }
])

const pendingTasks = ref([
  {
    id: 1,
    title: 'Review Session Notes - Wanjiku',
    initials: 'WM',
    description: 'Update progress notes from last session',
    due: 'Oct 2, 2025'
  },
  {
    id: 2,
    title: 'Assign Resources - Ochieng',
    initials: 'OK',
    description: 'Send mindfulness exercises',
    due: 'Oct 3, 2025'
  }
])

const recentActivity = ref([
  {
    id: 1,
    title: 'New Message from Wanjiku Mwangi',
    initials: 'WM',
    description: 'Checking in about anxiety triggers',
    time: '2 hours ago',
    type: 'Messaging',
    unread: true
  },
  {
    id: 2,
    title: 'Session Completed - Ochieng Kiprop',
    initials: 'OK',
    description: 'Individual therapy session #12',
    time: '4 hours ago',
    type: 'Session Notes',
    unread: false
  },
  {
    id: 3,
    title: 'Task Completed: Resource Assignment',
    initials: 'MN',
    description: 'Sent breathing exercises to Muthoni',
    time: '1 day ago',
    type: 'Tasks',
    unread: false
  }
])

const urgentAlerts = ref([
  {
    id: 1,
    title: 'High-Risk Alert: Muthoni Njoroge',
    description: 'Reported increased suicidal thoughts - immediate follow-up required',
    priority: 'critical'
  }
])

const therapistProfile = ref({
  name: 'Dr. Amina Otieno',
  title: 'Dr.',
  lastName: 'Otieno',
  specializations: ['Anxiety', 'Trauma', 'Family Therapy'],
  license: 'KPS #45678'
})

const timeOfDay = computed(() => {
  const hour = new Date().getHours()
  if (hour < 12) return 'morning'
  if (hour < 18) return 'afternoon'
  return 'evening'
})

const therapistInitials = computed(() => {
  const names = therapistProfile.value.name.trim().split(' ')
  return names.length > 1
    ? `${names[0][0]}${names[names.length - 1][0]}`
    : names[0][0] || 'AO'
})

const setActiveComponent = (name: string) => {
  emit('set-active-component', name)
}

const navigateTo = (path: string) => {
  router.push(path)
}

const handleActivityClick = (type: string, id: number) => {
  switch (type) {
    case 'Messaging':
      setActiveComponent('chat')
      break
    case 'Session Notes':
      setActiveComponent('session-notes')
      break
    case 'Tasks':
      setActiveComponent('tasks')
      break
    default:
      viewClientDetails(id)
  }
}

const viewClientDetails = (clientId: number) => {
  setActiveComponent('clients')
  console.log(`Viewing client details for ID: ${clientId}`)
}

const handleAlert = (alertId: number) => {
  setActiveComponent('session-notes') // Or dedicated alerts view
  console.log(`Handling alert ${alertId}`)
}

const saveQuickNotes = async () => {
  if (!quickNotes.value.trim()) return
  try {
    console.log('Saving quick notes:', quickNotes.value)
    quickNotes.value = ''
    // Show success toast in real app
  } catch (error) {
    console.error('Error saving notes:', error)
  }
}

const formatTime = (date: Date) => {
  return date.toLocaleTimeString('en-US', { 
    hour: 'numeric', 
    minute: '2-digit',
    hour12: true 
  })
}

const getStatusClass = (status: string) => {
  switch (status) {
    case 'upcoming': return 'bg-yellow-100 text-yellow-800'
    case 'in-progress': return 'bg-blue-100 text-blue-800'
    case 'completed': return 'bg-green-100 text-green-800'
    case 'cancelled': return 'bg-red-100 text-red-800'
    default: return 'bg-gray-100 text-gray-800'
  }
}

onMounted(async () => {
  console.log('Therapist dashboard mounted')
})
</script>

<style scoped>
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fadeInUp {
  animation: fadeInUp 0.6s ease-out;
}

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

::-webkit-scrollbar {
  width: 6px;
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
</style>