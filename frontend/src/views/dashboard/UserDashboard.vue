<template>
  <div>
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-teal-600 to-emerald-600 rounded-3xl p-8 text-white mb-8">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-3xl font-bold mb-2">Welcome back, {{ userProfile.firstName }}! 👋</h2>
          <p class="text-teal-100 text-lg">How are you feeling today? Let's check in on your wellness journey.</p>
        </div>
        <div class="hidden lg:block">
          <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center animate-pulse">
            <Heart class="w-12 h-12 text-white" />
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Left Column -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Upcoming Sessions -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Upcoming Sessions</h3>
            <button @click="setActiveComponent('book-session')" class="text-teal-600 hover:text-teal-700 font-medium text-sm">Book New Session</button>
          </div>
          <div v-if="upcomingSessions.length > 0" class="space-y-4">
            <div v-for="session in upcomingSessions" :key="session.id" 
                 class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-teal-50 transition-colors cursor-pointer"
                 @click="viewSession(session.id)">
              <div class="flex items-center space-x-4">
                <img :src="session.therapist.avatar" :alt="session.therapist.name" 
                     class="w-12 h-12 rounded-full border-2 border-teal-200">
                <div>
                  <h4 class="font-semibold text-gray-900">{{ session.therapist.name }}</h4>
                  <p class="text-sm text-gray-600">{{ session.type }}</p>
                </div>
              </div>
              <div class="text-right">
                <p class="font-medium text-gray-900">{{ formatDate(session.dateTime) }}</p>
                <p class="text-sm text-gray-600">{{ formatTime(session.dateTime) }}</p>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-8">
            <Calendar class="w-12 h-12 text-gray-300 mx-auto mb-3" />
            <p class="text-gray-500">No upcoming sessions</p>
            <button @click="setActiveComponent('book-session')" class="mt-3 text-teal-600 hover:text-teal-700 font-medium">
              Schedule your first session
            </button>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
          <h3 class="text-xl font-bold text-gray-900 mb-6">Recent Activity</h3>
          <div v-if="recentActivities.length > 0" class="space-y-4">
            <div v-for="activity in recentActivities" :key="activity.id" 
                 class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-xl transition-colors">
              <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                   :class="activity.bgColor">
                <component :is="activity.icon" class="w-5 h-5" :class="activity.iconColor" />
              </div>
              <div class="flex-1">
                <p class="text-gray-900">{{ activity.title }}</p>
                <p class="text-sm text-gray-600">{{ activity.time }}</p>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-8">
            <Activity class="w-12 h-12 text-gray-300 mx-auto mb-3" />
            <p class="text-gray-500">No recent activity</p>
          </div>
        </div>
      </div>

      <!-- Right Column -->
      <div class="space-y-6">
        <!-- Mood Tracker -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
          <h3 class="text-xl font-bold text-gray-900 mb-6">Today's Mood</h3>
          <div class="grid grid-cols-3 gap-3 mb-4">
            <button v-for="mood in moods" :key="mood.value"
                    @click="selectMood(mood.value)"
                    class="p-4 rounded-xl border-2 transition-all duration-200 hover:scale-105"
                    :class="selectedMood === mood.value ? 'border-teal-500 bg-teal-50' : 'border-gray-200 hover:border-teal-300'">
              <div class="text-2xl mb-2">{{ mood.emoji }}</div>
              <div class="text-xs text-gray-600">{{ mood.label }}</div>
            </button>
          </div>
          <button v-if="selectedMood" 
                  @click="saveMood"
                  class="w-full bg-teal-600 text-white py-2 px-4 rounded-xl hover:bg-teal-700 transition-colors font-medium">
            Save Mood
          </button>
        </div>

        <!-- Progress Stats -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
          <h3 class="text-xl font-bold text-gray-900 mb-6">Your Progress</h3>
          <div class="space-y-4">
            <div>
              <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600">Sessions Completed</span>
                <span class="font-medium text-gray-900">{{ progressStats.sessionsCompleted }}/{{ progressStats.totalSessions }}</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-teal-600 h-2 rounded-full transition-all duration-500" 
                     :style="`width: ${(progressStats.sessionsCompleted / progressStats.totalSessions) * 100}%`"></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600">Wellness Goals</span>
                <span class="font-medium text-gray-900">{{ progressStats.goalsCompleted }}/{{ progressStats.totalGoals }}</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-emerald-600 h-2 rounded-full transition-all duration-500" 
                     :style="`width: ${(progressStats.goalsCompleted / progressStats.totalGoals) * 100}%`"></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600">Journal Entries</span>
                <span class="font-medium text-gray-900">{{ progressStats.journalEntries }} this month</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-yellow-600 h-2 rounded-full transition-all duration-500" 
                     :style="`width: ${Math.min((progressStats.journalEntries / 30) * 100, 100)}%`"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Journal -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Journal</h3>
          <textarea 
            v-model="journalEntry"
            placeholder="How are you feeling today? Write a few thoughts..."
            class="w-full p-3 border border-gray-200 rounded-xl resize-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
            rows="3"
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
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Calendar, BookOpen, Heart, Activity, FileText } from 'lucide-vue-next'
import { useDashboardNavigation } from '../../composables/useDashboardNavigation'

const { setActiveComponent } = useDashboardNavigation()

// Reactive state
const selectedMood = ref('')
const journalEntry = ref('')
const userProfile = ref({
  name: 'Sarah Johnson',
  firstName: 'Sarah',
  email: 'sarah.johnson@mindwell.com',
  avatar: '/api/placeholder/32/32'
})

// Mock data
const upcomingSessions = ref([
  {
    id: 1,
    therapist: { name: 'Dr. Emily Chen', avatar: '/api/placeholder/48/48' },
    type: 'Individual Therapy',
    dateTime: new Date('2025-09-27T14:00:00')
  },
  {
    id: 2,
    therapist: { name: 'Dr. Michael Rodriguez', avatar: '/api/placeholder/48/48' },
    type: 'Group Session',
    dateTime: new Date('2025-09-28T10:00:00')
  }
])

const recentActivities = ref([
  {
    id: 1,
    title: 'Completed therapy session with Dr. Chen',
    time: '2 hours ago',
    icon: Calendar,
    bgColor: 'bg-teal-100',
    iconColor: 'text-teal-600'
  },
  {
    id: 2,
    title: 'New wellness resource viewed',
    time: '1 day ago',
    icon: BookOpen,
    bgColor: 'bg-emerald-100',
    iconColor: 'text-emerald-600'
  },
  {
    id: 3,
    title: 'Mood tracking streak: 7 days',
    time: '2 days ago',
    icon: Heart,
    bgColor: 'bg-yellow-100',
    iconColor: 'text-yellow-600'
  }
])

const progressStats = ref({
  sessionsCompleted: 12,
  totalSessions: 15,
  goalsCompleted: 7,
  totalGoals: 10,
  journalEntries: 18
})

const moods = [
  { emoji: '😊', label: 'Happy', value: 'happy' },
  { emoji: '😐', label: 'Neutral', value: 'neutral' },
  { emoji: '😔', label: 'Sad', value: 'sad' },
  { emoji: '😰', label: 'Anxious', value: 'anxious' },
  { emoji: '😡', label: 'Angry', value: 'angry' },
  { emoji: '🤔', label: 'Thoughtful', value: 'thoughtful' }
]

// Methods
const selectMood = (mood: string) => {
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
    progressStats.value.journalEntries++
    alert('Journal entry saved!')
  } catch (error) {
    console.error('Error saving journal entry:', error)
  }
}

const viewSession = (sessionId: number) => {
  setActiveComponent('session-details', { sessionId })
}

const formatDate = (date: Date) => {
  const today = new Date()
  const tomorrow = new Date(today)
  tomorrow.setDate(tomorrow.getDate() + 1)
  
  if (date.toDateString() === today.toDateString()) return 'Today'
  if (date.toDateString() === tomorrow.toDateString()) return 'Tomorrow'
  
  return date.toLocaleDateString('en-US', { 
    month: 'short', 
    day: 'numeric',
    timeZone: 'Africa/Nairobi'
  })
}

const formatTime = (date: Date) => {
  return date.toLocaleTimeString('en-US', { 
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