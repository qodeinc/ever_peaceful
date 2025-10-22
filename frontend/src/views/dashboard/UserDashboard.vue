<template>
  <div class="min-h-screen bg-gradient-to-br from-teal-50/50 via-white to-emerald-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Welcome Section -->
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-teal-200 mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back, {{ userProfile.firstName }}! 👋</h2>
            <p class="text-gray-600 text-lg">How are you feeling today? Let's check in on your wellness journey.</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-medium text-lg">
            {{ userInitials }}
          </div>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Upcoming Sessions -->
          <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-xl font-semibold text-gray-900">Upcoming Sessions</h3>
              <button @click="setActiveComponent('book-session')" class="text-teal-600 hover:text-teal-700 font-medium text-sm flex items-center gap-1">
                <Plus class="w-4 h-4" />
                Book New
              </button>
            </div>
            <div v-if="upcomingSessions.length > 0" class="space-y-4 max-h-80 overflow-y-auto">
              <div v-for="session in upcomingSessions" :key="session.id" 
                   class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-teal-50 transition-colors cursor-pointer border border-teal-100"
                   @click="viewSession(session.id)">
                <div class="flex items-center space-x-4 flex-1">
                  <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-medium text-sm">
                    {{ session.therapist.initials }}
                  </div>
                  <div>
                    <h4 class="font-semibold text-gray-900">{{ session.therapist.name }}</h4>
                    <p class="text-sm text-gray-600">{{ session.type }}</p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="font-medium text-gray-900">{{ formatDate(session.dateTime) }}</p>
                  <p class="text-sm text-gray-600">{{ formatTime(session.dateTime) }}</p>
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mt-1"
                        :class="session.status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                    {{ session.status }}
                  </span>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-8">
              <Calendar class="w-12 h-12 text-gray-400 mx-auto mb-3" />
              <h4 class="text-lg font-medium text-gray-900 mb-2">No upcoming sessions</h4>
              <p class="text-gray-500 mb-4">Ready to schedule your next step?</p>
              <button @click="setActiveComponent('book-session')" class="px-6 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors font-medium">
                Schedule Session
              </button>
            </div>
          </div>

          <!-- Recent Activity -->
          <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-xl font-semibold text-gray-900">Recent Activity</h3>
              <button class="text-sm text-gray-500 hover:text-gray-700">See All</button>
            </div>
            <div v-if="recentActivities.length > 0" class="space-y-4 max-h-72 overflow-y-auto">
              <div v-for="activity in recentActivities" :key="activity.id" 
                   class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-xl transition-colors cursor-pointer"
                   @click="handleActivityClick(activity.type, activity.id)">
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                     :class="activity.bgColor">
                  <component :is="activity.icon" class="w-5 h-5" :class="activity.iconColor" />
                </div>
                <div class="flex-1">
                  <div class="flex items-center justify-between">
                    <h4 class="font-medium text-gray-900">{{ activity.title }}</h4>
                    <span class="text-sm text-gray-500">{{ activity.time }}</span>
                  </div>
                  <p class="text-sm text-gray-600 mt-1">{{ activity.description }}</p>
                  <div class="flex items-center mt-2 text-xs">
                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full mr-2">{{ activity.type }}</span>
                    <span v-if="activity.unread" class="px-2 py-1 bg-teal-100 text-teal-700 rounded-full">New</span>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-8">
              <Activity class="w-12 h-12 text-gray-400 mx-auto mb-3" />
              <h4 class="text-lg font-medium text-gray-900 mb-2">No recent activity</h4>
              <p class="text-gray-500">Your journey starts here</p>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
          <!-- Mood Tracker -->
          <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Today's Mood</h3>
            <div class="grid grid-cols-3 gap-3 mb-4">
              <button v-for="mood in moods" :key="mood.value"
                      @click="selectMood(mood.value)"
                      class="p-4 rounded-xl border-2 transition-all duration-200 hover:scale-105"
                      :class="selectedMood === mood.value ? 'border-teal-500 bg-teal-50 shadow-sm' : 'border-gray-200 hover:border-teal-300'">
                <div class="text-2xl mb-2">{{ mood.emoji }}</div>
                <div class="text-xs text-gray-600">{{ mood.label }}</div>
              </button>
            </div>
            <button v-if="selectedMood" 
                    @click="saveMood"
                    class="w-full bg-teal-600 text-white py-2 px-4 rounded-xl hover:bg-teal-700 transition-colors font-medium">
              Save Mood
            </button>
            <p v-else class="text-center text-sm text-gray-500 mt-2">Tap to select your mood</p>
          </div>

          <!-- Progress Stats -->
          <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Your Progress</h3>
            <div class="space-y-4">
              <div v-for="stat in progressStats" :key="stat.label">
                <div class="flex justify-between text-sm mb-2">
                  <span class="text-gray-600">{{ stat.label }}</span>
                  <span class="font-medium text-gray-900">{{ stat.value }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div class="bg-teal-600 h-2 rounded-full transition-all duration-500" 
                       :style="`width: ${stat.progress}%`"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Journal -->
          <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Quick Journal</h3>
            <textarea 
              v-model="journalEntry"
              placeholder="How are you feeling today? Write a few thoughts..."
              class="w-full p-3 border border-gray-200 rounded-xl resize-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm"
              rows="4"
            ></textarea>
            <button @click="saveJournalEntry" 
                    :disabled="!journalEntry.trim()"
                    class="mt-3 w-full bg-teal-600 text-white py-2 px-4 rounded-xl hover:bg-teal-700 transition-colors font-medium disabled:bg-gray-300 disabled:cursor-not-allowed">
              Save Entry
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Calendar, BookOpen, Heart, Activity, FileText, Plus } from 'lucide-vue-next'
import { useDashboardNavigation } from '../../composables/useDashboardNavigation'

const { setActiveComponent } = useDashboardNavigation()

// Reactive state
const selectedMood = ref('')
const journalEntry = ref('')
const userProfile = ref({
  name: 'Wanjiku Mwangi',
  firstName: 'Wanjiku',
  email: 'wanjiku.mwangi@mindwell.co.ke',
  avatar: '/api/placeholder/32/32'
})

const userInitials = computed(() => {
  const names = userProfile.value.name.split(' ')
  return names.length > 1 ? `${names[0][0]}${names[1][0]}` : names[0][0]
})

// Mock data
const upcomingSessions = ref([
  {
    id: 1,
    therapist: { name: 'Dr. Amina Otieno', initials: 'AO' },
    type: 'Individual Therapy',
    dateTime: new Date('2025-10-15T14:00:00'),
    status: 'confirmed'
  },
  {
    id: 2,
    therapist: { name: 'Dr. Kipchoge Kiptoo', initials: 'KK' },
    type: 'Group Session',
    dateTime: new Date('2025-10-10T10:00:00'),
    status: 'pending'
  }
])

const recentActivities = ref([
  {
    id: 1,
    title: 'Completed therapy session with Dr. Otieno',
    time: '2 hours ago',
    description: 'Individual therapy - anxiety management',
    icon: Calendar,
    bgColor: 'bg-teal-100',
    iconColor: 'text-teal-600',
    type: 'Session',
    unread: false
  },
  {
    id: 2,
    title: 'New wellness resource viewed',
    time: '1 day ago',
    description: 'Mindfulness breathing exercises',
    icon: BookOpen,
    bgColor: 'bg-emerald-100',
    iconColor: 'text-emerald-600',
    type: 'Resource',
    unread: true
  },
  {
    id: 3,
    title: 'Mood tracking streak: 7 days',
    time: '2 days ago',
    description: 'Consistent daily mood logging',
    icon: Heart,
    bgColor: 'bg-yellow-100',
    iconColor: 'text-yellow-600',
    type: 'Mood',
    unread: false
  }
])

const progressStats = ref([
  { label: 'Sessions Completed', value: '12 / 15', progress: 80 },
  { label: 'Goals Achieved', value: '7 / 10', progress: 70 },
  { label: 'Journal Entries', value: '18 this month', progress: 60 }
])

const moods = [
  { emoji: '😊', label: 'Happy', value: 'happy' },
  { emoji: '😐', label: 'Neutral', value: 'neutral' },
  { emoji: '😔', label: 'Sad', value: 'sad' },
  { emoji: '😰', label: 'Anxious', value: 'anxious' },
  { emoji: '😡', label: 'Angry', value: 'angry' },
  { emoji: '🤔', label: 'Thoughtful', value: 'thoughtful' }
]

// Methods
const selectMood = (mood) => {
  selectedMood.value = mood
}

const saveMood = async () => {
  try {
    console.log('Saving mood:', selectedMood.value)
    alert('Mood saved successfully!')
  } catch (error) {
    console.error('Error saving mood:', error)
  }
}

const saveJournalEntry = async () => {
  if (!journalEntry.value.trim()) return
  try {
    console.log('Saving journal entry:', journalEntry.value)
    journalEntry.value = ''
    alert('Journal entry saved!')
  } catch (error) {
    console.error('Error saving journal entry:', error)
  }
}

const viewSession = (sessionId) => {
  setActiveComponent('session-details', { sessionId })
}

const handleActivityClick = (type, id) => {
  console.log(`Viewing ${type} activity ${id}`)
  // Navigate based on type
}

const formatDate = (date) => {
  const today = new Date()
  const tomorrow = new Date(today)
  tomorrow.setDate(tomorrow.getDate() + 1)
  
  if (date.toDateString() === today.toDateString()) return 'Today'
  if (date.toDateString() === tomorrow.toDateString()) return 'Tomorrow'
  
  return date.toLocaleDateString('en-KE', { 
    month: 'short', 
    day: 'numeric',
    timeZone: 'Africa/Nairobi'
  })
}

const formatTime = (date) => {
  return date.toLocaleTimeString('en-KE', { 
    hour: 'numeric', 
    minute: '2-digit',
    hour12: true,
    timeZone: 'Africa/Nairobi'
  })
}
</script>

<style scoped>
/* Animations */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fadeInUp {
  animation: fadeInUp 0.6s ease-out;
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
</style>