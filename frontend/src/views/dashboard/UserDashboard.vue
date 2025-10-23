<template>
  <div class="min-h-screen bg-neutral-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Welcome Section -->
      <div class="mb-12">
        <div class="flex items-start justify-between">
          <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-3">Welcome back, {{ userProfile.firstName }}</h1>
            <p class="text-gray-600 text-lg max-w-2xl">Track your sessions, log your mood, and continue your wellness journey.</p>
          </div>
          <div class="w-14 h-14 bg-teal-600 rounded-full flex items-center justify-center text-white font-semibold text-lg">
            {{ userInitials }}
          </div>
        </div>
      </div>

      <!-- Main Content Layout -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-x-16 gap-y-12">
        <!-- Primary Column -->
        <div class="lg:col-span-8">
          <!-- Upcoming Sessions -->
          <div class="mb-16">
            <div class="flex items-end justify-between mb-8 border-b border-gray-200 pb-4">
              <div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-1">Upcoming Sessions</h2>
                <p class="text-sm text-gray-500">Your scheduled appointments</p>
              </div>
              <button @click="setActiveComponent('book-session')" class="text-sm font-medium text-teal-600 hover:text-teal-700 flex items-center gap-1.5">
                <Plus class="w-4 h-4" />
                Book Session
              </button>
            </div>

            <div v-if="upcomingSessions.length > 0" class="space-y-6">
              <div v-for="session in upcomingSessions" :key="session.id" 
                   class="flex items-center justify-between py-5 border-b border-gray-100 last:border-0 hover:bg-neutral-50 -mx-4 px-4 transition-colors cursor-pointer"
                   @click="viewSession(session.id)">
                <div class="flex items-center gap-4 flex-1">
                  <div class="w-12 h-12 bg-teal-600 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                    {{ session.therapist.initials }}
                  </div>
                  <div>
                    <h3 class="font-semibold text-gray-900 text-base mb-0.5">{{ session.therapist.name }}</h3>
                    <p class="text-sm text-gray-500">{{ session.type }}</p>
                  </div>
                </div>
                <div class="text-right flex items-center gap-6">
                  <div>
                    <p class="font-medium text-gray-900 text-sm">{{ formatDate(session.dateTime) }}</p>
                    <p class="text-sm text-gray-500">{{ formatTime(session.dateTime) }}</p>
                  </div>
                  <span class="text-xs font-medium px-3 py-1.5 rounded-full border"
                        :class="session.status === 'confirmed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200'">
                    {{ session.status }}
                  </span>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-16">
              <Calendar class="w-12 h-12 text-gray-300 mx-auto mb-4" />
              <h3 class="text-lg font-semibold text-gray-900 mb-2">No upcoming sessions</h3>
              <p class="text-gray-500 mb-6">Schedule your next appointment</p>
              <button @click="setActiveComponent('book-session')" class="px-6 py-2.5 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-medium text-sm">
                Book Session
              </button>
            </div>
          </div>

          <!-- Recent Activity -->
          <div>
            <div class="flex items-end justify-between mb-8 border-b border-gray-200 pb-4">
              <div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-1">Recent Activity</h2>
                <p class="text-sm text-gray-500">Your latest updates</p>
              </div>
            </div>
            <div v-if="recentActivities.length > 0" class="space-y-1">
              <div v-for="activity in recentActivities" :key="activity.id" 
                   class="flex items-start gap-4 py-5 hover:bg-neutral-50 -mx-4 px-4 transition-colors cursor-pointer border-b border-gray-100 last:border-0"
                   @click="handleActivityClick(activity.type, activity.id)">
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-teal-100">
                  <component :is="activity.icon" class="w-5 h-5 text-teal-700" />
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex items-start justify-between gap-4 mb-1">
                    <h3 class="font-medium text-gray-900 text-sm">{{ activity.title }}</h3>
                    <span class="text-xs text-gray-400 whitespace-nowrap">{{ activity.time }}</span>
                  </div>
                  <p class="text-sm text-gray-500 mb-2">{{ activity.description }}</p>
                  <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">{{ activity.type }}</span>
                    <span v-if="activity.unread" class="w-1.5 h-1.5 bg-teal-600 rounded-full"></span>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-16">
              <Activity class="w-12 h-12 text-gray-300 mx-auto mb-4" />
              <h3 class="text-lg font-semibold text-gray-900 mb-2">No recent activity</h3>
              <p class="text-gray-500">Your activity will appear here</p>
            </div>
          </div>
        </div>

        <!-- Sidebar Column -->
        <div class="lg:col-span-4 space-y-12">
          <!-- Mood Tracker -->
          <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-6">How are you feeling?</h3>
            <div class="grid grid-cols-3 gap-3 mb-5">
              <button v-for="mood in moods" :key="mood.value"
                      @click="selectMood(mood.value)"
                      class="p-4 rounded-lg border transition-all"
                      :class="selectedMood === mood.value ? 'border-teal-600 bg-teal-50' : 'border-gray-200 hover:border-gray-300'">
                <div class="text-3xl mb-2">{{ mood.emoji }}</div>
                <div class="text-xs font-medium text-gray-600">{{ mood.label }}</div>
              </button>
            </div>
            <button v-if="selectedMood" 
                    @click="saveMood"
                    class="w-full bg-teal-600 text-white py-2.5 px-4 rounded-lg hover:bg-teal-700 transition-colors font-medium text-sm">
              Save Mood
            </button>
            <p v-else class="text-center text-sm text-gray-400">Select your current mood</p>
          </div>

          <!-- Progress Stats -->
          <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Your Progress</h3>
            <div class="space-y-6">
              <div v-for="stat in progressStats" :key="stat.label">
                <div class="flex justify-between text-sm mb-2.5">
                  <span class="text-gray-600 font-medium">{{ stat.label }}</span>
                  <span class="font-semibold text-gray-900">{{ stat.value }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                  <div class="bg-teal-600 h-1.5 rounded-full transition-all duration-700" 
                       :style="`width: ${stat.progress}%`"></div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Quick Journal -->
          <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Journal</h3>
            <textarea 
              v-model="journalEntry"
              placeholder="What's on your mind today?"
              class="w-full p-4 border border-gray-200 rounded-lg resize-none focus:ring-2 focus:ring-teal-600 focus:border-teal-600 text-sm placeholder:text-gray-400"
              rows="5"
            ></textarea>
            <button @click="saveJournalEntry" 
                    :disabled="!journalEntry.trim()"
                    class="mt-4 w-full bg-teal-600 text-white py-2.5 px-4 rounded-lg hover:bg-teal-700 transition-colors font-medium text-sm disabled:bg-gray-300 disabled:cursor-not-allowed">
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
    dateTime: new Date('2025-10-25T14:00:00'),
    status: 'confirmed'
  },
  {
    id: 2,
    therapist: { name: 'Dr. Kipchoge Kiptoo', initials: 'KK' },
    type: 'Group Session',
    dateTime: new Date('2025-10-28T10:00:00'),
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
    type: 'Session',
    unread: false
  },
  {
    id: 2,
    title: 'New wellness resource viewed',
    time: '1 day ago',
    description: 'Mindfulness breathing exercises',
    icon: BookOpen,
    type: 'Resource',
    unread: true
  },
  {
    id: 3,
    title: 'Mood tracking streak: 7 days',
    time: '2 days ago',
    description: 'Consistent daily mood logging',
    icon: Heart,
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
    selectedMood.value = ''
  } catch (error) {
    console.error('Error saving mood:', error)
  }
}

const saveJournalEntry = async () => {
  if (!journalEntry.value.trim()) return
  try {
    console.log('Saving journal entry:', journalEntry.value)
    alert('Journal entry saved!')
    journalEntry.value = ''
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
/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: transparent;
}

::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}

/* Smooth transitions */
button, a {
  transition: all 0.15s ease;
}
</style>