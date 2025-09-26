<template>
  <div class="min-h-screen bg-gradient-to-br from-emerald-50/30 via-white to-teal-50/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Welcome Section -->
      <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-3xl p-8 text-white mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-3xl font-bold mb-2">Good {{ timeOfDay }}, {{ therapistProfile.title }} {{ therapistProfile.lastName }}! 👨‍⚕️</h2>
            <p class="text-emerald-100 text-lg">
              You have {{ todaysStats.sessions }} sessions scheduled today. 
              {{ todaysStats.sessions > 0 ? "Ready to make a difference?" : "Enjoy your lighter day!" }}
            </p>
          </div>
          <div class="hidden lg:block">
            <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center animate-pulse">
              <Users class="w-12 h-12 text-white" />
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Today's Sessions</p>
              <p class="text-2xl font-bold text-gray-900">{{ todaysStats.sessions }}</p>
              <p class="text-xs mt-1" :class="todaysStats.sessionsChange >= 0 ? 'text-green-600' : 'text-red-600'">
                {{ todaysStats.sessionsChange >= 0 ? '↑' : '↓' }} {{ Math.abs(todaysStats.sessionsChange) }} vs yesterday
              </p>
            </div>
            <div class="w-12 h-12 bg-teal-100 rounded-2xl flex items-center justify-center">
              <Calendar class="w-6 h-6 text-teal-600" />
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Active Clients</p>
              <p class="text-2xl font-bold text-gray-900">{{ todaysStats.activeClients }}</p>
              <p class="text-xs text-green-600 mt-1">↑ {{ todaysStats.newClients }} new this month</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
              <Users class="w-6 h-6 text-emerald-600" />
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Avg. Rating</p>
              <p class="text-2xl font-bold text-gray-900">{{ todaysStats.rating.toFixed(1) }}</p>
              <div class="flex items-center mt-1">
                <div class="flex">
                  <Star v-for="n in 5" :key="n" 
                        class="w-3 h-3" 
                        :class="n <= todaysStats.rating ? 'text-yellow-400 fill-current' : 'text-gray-300'" />
                </div>
              </div>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-2xl flex items-center justify-center">
              <Star class="w-6 h-6 text-yellow-600" />
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Unread Messages</p>
              <p class="text-2xl font-bold text-gray-900">{{ todaysStats.messages }}</p>
              <p class="text-xs text-blue-600 mt-1">{{ todaysStats.urgentMessages }} urgent</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center">
              <MessageCircle class="w-6 h-6 text-purple-600" />
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Today's Schedule -->
          <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-xl font-bold text-gray-900">Today's Schedule</h3>
              <div class="flex space-x-2">
                <button @click="refreshSchedule" class="p-2 text-gray-400 hover:text-emerald-600 transition-colors">
                  <RefreshCw class="w-4 h-4" />
                </button>
                <button @click="navigateTo('/therapist/calendar')" class="text-emerald-600 hover:text-emerald-700 font-medium text-sm">
                  View Calendar
                </button>
              </div>
            </div>
            <div v-if="todaysSchedule.length > 0" class="space-y-4">
              <div v-for="appointment in todaysSchedule" :key="appointment.id" 
                   class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-emerald-50 transition-colors cursor-pointer"
                   @click="viewClientDetails(appointment.client.id)">
                <div class="flex items-center space-x-4">
                  <img :src="appointment.client.avatar" :alt="appointment.client.name" 
                       class="w-12 h-12 rounded-full border-2 border-emerald-200">
                  <div>
                    <h4 class="font-semibold text-gray-900">{{ appointment.client.name }}</h4>
                    <p class="text-sm text-gray-600">{{ appointment.type }}</p>
                    <p class="text-xs text-gray-500">Session #{{ appointment.sessionNumber }}</p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="font-medium text-gray-900">{{ formatTime(appointment.startTime) }}</p>
                  <span class="px-3 py-1 text-xs rounded-full font-medium"
                        :class="getStatusClass(appointment.status)">
                    {{ appointment.status }}
                  </span>
                  <div class="flex items-center mt-1 space-x-1">
                    <button v-if="appointment.status === 'upcoming'" 
                            @click.stop="startSession(appointment.id)"
                            class="px-2 py-1 text-xs bg-emerald-100 text-emerald-700 rounded hover:bg-emerald-200">
                      Start
                    </button>
                    <button @click.stop="viewNotes(appointment.id)"
                            class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                      Notes
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-8">
              <Calendar class="w-12 h-12 text-gray-300 mx-auto mb-3" />
              <p class="text-gray-500">No sessions scheduled for today</p>
              <p class="text-sm text-gray-400 mt-1">Enjoy your day off!</p>
            </div>
          </div>

          <!-- Recent Client Updates -->
          <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Recent Client Updates</h3>
            <div v-if="clientUpdates.length > 0" class="space-y-4">
              <div v-for="update in clientUpdates" :key="update.id" 
                   class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-xl transition-colors cursor-pointer"
                   @click="viewClientDetails(update.clientId)">
                <img :src="update.avatar" :alt="update.clientName" 
                     class="w-10 h-10 rounded-full border-2 border-gray-200 flex-shrink-0">
                <div class="flex-1">
                  <div class="flex items-center justify-between">
                    <h4 class="font-medium text-gray-900">{{ update.clientName }}</h4>
                    <span class="text-xs text-gray-500">{{ update.time }}</span>
                  </div>
                  <p class="text-sm text-gray-600 mt-1">{{ update.message }}</p>
                  <div class="flex items-center mt-2 space-x-2">
                    <span class="px-2 py-1 text-xs rounded-full"
                          :class="getPriorityClass(update.priority)">
                      {{ update.priority }}
                    </span>
                    <span v-if="update.requiresResponse" 
                          class="px-2 py-1 text-xs bg-orange-100 text-orange-700 rounded-full">
                      Response needed
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-8">
              <MessageCircle class="w-12 h-12 text-gray-300 mx-auto mb-3" />
              <p class="text-gray-500">No recent updates</p>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
          <!-- Client Overview -->
          <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Client Overview</h3>
            <div class="space-y-4">
              <div class="text-center">
                <div class="text-3xl font-bold text-emerald-600">{{ todaysStats.activeClients }}</div>
                <div class="text-sm text-gray-600">Active Clients</div>
              </div>
              <div class="grid grid-cols-2 gap-4 text-center">
                <div>
                  <div class="text-xl font-bold text-teal-600">{{ clientStats.new }}</div>
                  <div class="text-xs text-gray-600">New This Month</div>
                </div>
                <div>
                  <div class="text-xl font-bold text-blue-600">{{ clientStats.continuing }}</div>
                  <div class="text-xs text-gray-600">Continuing</div>
                </div>
              </div>
              <div class="pt-4 border-t border-gray-100">
                <div class="flex justify-between text-sm mb-2">
                  <span class="text-gray-600">Session Completion Rate</span>
                  <span class="font-medium">{{ clientStats.completionRate }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div class="bg-emerald-600 h-2 rounded-full transition-all duration-500" 
                       :style="`width: ${clientStats.completionRate}%`"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Urgent Alerts -->
          <div v-if="urgentAlerts.length > 0" class="bg-white rounded-2xl p-6 shadow-lg border border-red-200">
            <h3 class="text-xl font-bold text-red-900 mb-4 flex items-center">
              <AlertTriangle class="w-5 h-5 mr-2" />
              Urgent Alerts
            </h3>
            <div class="space-y-3">
              <div v-for="alert in urgentAlerts" :key="alert.id" 
                   class="p-3 bg-red-50 rounded-xl border border-red-100">
                <p class="font-medium text-red-900">{{ alert.title }}</p>
                <p class="text-sm text-red-700 mt-1">{{ alert.description }}</p>
                <div class="flex justify-end mt-2">
                  <button @click="handleAlert(alert.id)" 
                          class="px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                    Review
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Professional Notes -->
          <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Session Notes</h3>
            <textarea 
              v-model="sessionNotes"
              placeholder="Quick notes or reminders for upcoming sessions..."
              class="w-full p-3 border border-gray-200 rounded-xl resize-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
              rows="4"
            ></textarea>
            <button @click="saveNotes" 
                    :disabled="!sessionNotes.trim()"
                    class="mt-3 w-full bg-emerald-600 text-white py-2 px-4 rounded-xl hover:bg-emerald-700 transition-colors font-medium disabled:bg-gray-300 disabled:cursor-not-allowed">
              Save Notes
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
import { Calendar, Users, Star, MessageCircle, RefreshCw, AlertTriangle } from 'lucide-vue-next'

const router = useRouter()

// Reactive state
const sessionNotes = ref('')
const todaysStats = ref({
  sessions: 6,
  sessionsChange: 2,
  activeClients: 24,
  newClients: 8,
  rating: 4.8,
  messages: 12,
  urgentMessages: 2
})

const clientStats = ref({
  new: 8,
  continuing: 16,
  completionRate: 87
})

const todaysSchedule = ref([
  {
    id: 1,
    client: { 
      id: 1,
      name: 'Sarah Johnson', 
      avatar: '/api/placeholder/48/48' 
    },
    type: 'Individual Therapy',
    startTime: new Date('2024-12-09T14:00:00'),
    status: 'upcoming',
    sessionNumber: 5
  },
  {
    id: 2,
    client: { 
      id: 2,
      name: 'Mike Chen', 
      avatar: '/api/placeholder/48/48' 
    },
    type: 'Follow-up Session',
    startTime: new Date('2024-12-09T15:30:00'),
    status: 'upcoming',
    sessionNumber: 12
  },
  {
    id: 3,
    client: { 
      id: 3,
      name: 'Lisa Rodriguez', 
      avatar: '/api/placeholder/48/48' 
    },
    type: 'Group Therapy',
    startTime: new Date('2024-12-09T17:00:00'),
    status: 'completed',
    sessionNumber: 3
  }
])

const clientUpdates = ref([
  {
    id: 1,
    clientId: 1,
    clientName: 'Sarah Johnson',
    avatar: '/api/placeholder/40/40',
    message: 'Completed homework assignment and mood tracking for the week',
    time: '2 hours ago',
    priority: 'low',
    requiresResponse: false
  },
  {
    id: 2,
    clientId: 4,
    clientName: 'David Park',
    avatar: '/api/placeholder/40/40',
    message: 'Reported increased anxiety levels - requesting earlier appointment',
    time: '4 hours ago',
    priority: 'high',
    requiresResponse: true
  },
  {
    id: 3,
    clientId: 2,
    clientName: 'Mike Chen',
    avatar: '/api/placeholder/40/40',
    message: 'Shared journal entry about recent breakthrough moment',
    time: '1 day ago',
    priority: 'medium',
    requiresResponse: false
  }
])

const urgentAlerts = ref([
  {
    id: 1,
    title: 'Crisis Assessment Required',
    description: 'Client David Park indicated suicidal ideation in recent message',
    priority: 'critical'
  }
])

const therapistProfile = ref({
  name: 'Dr. Emily Johnson',
  title: 'Dr.',
  lastName: 'Johnson',
  avatar: '/api/placeholder/32/32',
  specializations: ['Anxiety', 'Depression', 'PTSD'],
  license: 'LCSW #12345'
})

const timeOfDay = computed(() => {
  const hour = new Date().getHours()
  if (hour < 12) return 'morning'
  if (hour < 18) return 'afternoon'
  return 'evening'
})

const navigateTo = (path: string) => {
  router.push(path)
}

const refreshSchedule = async () => {
  console.log('Refreshing schedule...')
}

const viewClientDetails = (clientId: number) => {
  router.push(`/therapist/clients/${clientId}`)
}

const startSession = (appointmentId: number) => {
  router.push(`/therapist/sessions/${appointmentId}/start`)
}

const viewNotes = (appointmentId: number) => {
  router.push(`/therapist/sessions/${appointmentId}/notes`)
}

const handleAlert = (alertId: number) => {
  router.push(`/therapist/alerts/${alertId}`)
}

const saveNotes = async () => {
  if (!sessionNotes.value.trim()) return
  try {
    console.log('Saving session notes:', sessionNotes.value)
    sessionNotes.value = ''
    alert('Notes saved successfully!')
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

const getPriorityClass = (priority: string) => {
  switch (priority) {
    case 'high': return 'bg-red-100 text-red-700'
    case 'medium': return 'bg-yellow-100 text-yellow-700'
    case 'low': return 'bg-green-100 text-green-700'
    default: return 'bg-gray-100 text-gray-700'
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

.urgent-alert {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.8; }
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