<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-7xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-16">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">Progress</h1>
        <p class="text-lg text-gray-500">Track your wellness journey</p>
      </div>

      <!-- Stats Overview -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-20">
        <div
          v-for="stat in stats"
          :key="stat.label"
          class="border-b-2 border-gray-200 pb-6"
        >
          <div class="flex items-start justify-between mb-4">
            <component :is="stat.icon" class="w-8 h-8 text-gray-400" />
            <span :class="['text-xs font-medium', stat.trend === 'up' ? 'text-emerald-600' : 'text-red-600']">
              {{ stat.change }}
            </span>
          </div>
          <p class="text-4xl font-light text-gray-900 mb-2">{{ stat.value }}</p>
          <p class="text-sm text-gray-500">{{ stat.label }}</p>
        </div>
      </div>

      <!-- Mood Tracker -->
      <div class="mb-20 pb-20 border-b border-gray-200">
        <div class="flex items-baseline justify-between mb-12">
          <div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">Mood Tracker</h2>
            <p class="text-gray-500">Last 7 days</p>
          </div>
          <button class="px-6 py-3 border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white transition-colors text-sm font-medium">
            Log Mood
          </button>
        </div>
        <div class="grid grid-cols-7 gap-4">
          <div
            v-for="day in moodData"
            :key="day.date"
            class="text-center"
          >
            <p class="text-xs text-gray-400 mb-4 uppercase tracking-wider">{{ day.day }}</p>
            <div
              :class="[
                'w-full aspect-square border-2 flex items-center justify-center cursor-pointer transition-all hover:scale-105',
                getMoodBorderColor(day.mood)
              ]"
            >
              <component :is="getMoodIcon(day.mood)" :class="['w-8 h-8', getMoodColor(day.mood)]" />
            </div>
            <p class="text-sm text-gray-600 mt-3">{{ day.mood }}</p>
          </div>
        </div>
      </div>

      <!-- Goals Progress -->
      <div class="mb-20 pb-20 border-b border-gray-200">
        <div class="flex items-baseline justify-between mb-12">
          <div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">Goals</h2>
            <p class="text-gray-500">Your wellness objectives</p>
          </div>
          <button class="px-6 py-3 bg-emerald-600 text-white hover:bg-emerald-700 transition-colors text-sm font-medium">
            Add Goal
          </button>
        </div>
        <div class="space-y-8">
          <div
            v-for="goal in goals"
            :key="goal.id"
            class="border-l-4 pl-6 py-4"
            :class="getGoalBorderColor(goal.progress)"
          >
            <div class="flex items-start justify-between mb-4">
              <div class="flex-1">
                <h3 class="text-xl font-light text-gray-900 mb-1">{{ goal.title }}</h3>
                <p class="text-sm text-gray-600">{{ goal.description }}</p>
              </div>
              <span class="text-sm text-gray-500 ml-4">{{ goal.status }}</span>
            </div>
            <div class="space-y-3">
              <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">Progress</span>
                <span class="font-medium text-gray-900">{{ goal.progress }}%</span>
              </div>
              <div class="w-full bg-gray-100 h-2">
                <div
                  :class="['h-2 transition-all duration-300', getProgressBarColor(goal.progress)]"
                  :style="{ width: `${goal.progress}%` }"
                ></div>
              </div>
            </div>
            <div class="flex items-center gap-6 mt-4 text-sm text-gray-500">
              <div class="flex items-center gap-2">
                <Calendar class="w-4 h-4" />
                <span>{{ goal.dueDate }}</span>
              </div>
              <div class="flex items-center gap-2">
                <Target class="w-4 h-4" />
                <span>{{ goal.milestones }} milestones</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Wellness Metrics -->
      <div class="mb-20 pb-20 border-b border-gray-200">
        <h2 class="text-3xl font-light text-gray-900 mb-12">Wellness Metrics</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
          <!-- Sleep Quality -->
          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-6">Sleep Quality</h3>
            <div class="space-y-4">
              <div
                v-for="sleep in sleepData"
                :key="sleep.date"
                class="flex items-center gap-4"
              >
                <span class="text-sm text-gray-500 w-20">{{ sleep.date }}</span>
                <div class="flex-1 bg-gray-100 h-2">
                  <div
                    class="bg-indigo-600 h-2"
                    :style="{ width: `${(sleep.hours / 10) * 100}%` }"
                  ></div>
                </div>
                <span class="text-sm font-medium text-gray-900 w-16 text-right">{{ sleep.hours }}h</span>
              </div>
            </div>
          </div>
          <!-- Activity Level -->
          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-6">Activity Level</h3>
            <div class="space-y-4">
              <div
                v-for="activity in activityData"
                :key="activity.day"
                class="flex items-center gap-4"
              >
                <span class="text-sm text-gray-500 w-20">{{ activity.day }}</span>
                <div class="flex-1 bg-gray-100 h-2">
                  <div
                    class="bg-emerald-600 h-2"
                    :style="{ width: `${activity.percentage}%` }"
                  ></div>
                </div>
                <span class="text-sm font-medium text-gray-900 w-16 text-right">{{ activity.minutes }} min</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Journal Entries -->
      <div>
        <div class="flex items-baseline justify-between mb-12">
          <div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">Journal</h2>
            <p class="text-gray-500">Your thoughts and reflections</p>
          </div>
          <button class="px-6 py-3 bg-emerald-600 text-white hover:bg-emerald-700 transition-colors text-sm font-medium">
            New Entry
          </button>
        </div>
        <div class="space-y-px border-t border-gray-200">
          <div
            v-for="entry in journalEntries"
            :key="entry.id"
            class="py-8 border-b border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer"
          >
            <div class="flex items-start justify-between mb-3">
              <div class="flex items-center gap-3">
                <BookOpen class="w-5 h-5 text-gray-400" />
                <h3 class="text-lg font-light text-gray-900">{{ entry.title }}</h3>
              </div>
              <span class="text-sm text-gray-400">{{ entry.date }}</span>
            </div>
            <p class="text-gray-600 leading-relaxed mb-4">{{ entry.content }}</p>
            <div class="flex items-center gap-2">
              <component :is="getMoodIcon(entry.mood)" :class="['w-4 h-4', getMoodColor(entry.mood)]" />
              <span class="text-sm text-gray-500">{{ entry.mood }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { 
  Calendar, Target, BookOpen, 
  Heart, Brain, Smile, Frown, Meh, Trophy, Activity
} from 'lucide-vue-next'

const stats = ref([
  {
    label: 'Total Sessions',
    value: '24',
    change: '+3 this month',
    trend: 'up',
    icon: Calendar
  },
  {
    label: 'Goals Achieved',
    value: '8/12',
    change: '+2 this week',
    trend: 'up',
    icon: Trophy
  },
  {
    label: 'Avg Mood Score',
    value: '7.8',
    change: '+0.5 from last week',
    trend: 'up',
    icon: Heart
  },
  {
    label: 'Streak Days',
    value: '15',
    change: 'Keep it up!',
    trend: 'up',
    icon: Activity
  }
])

const moodData = ref([
  { day: 'Mon', date: 'Sep 30', mood: 'Great' },
  { day: 'Tue', date: 'Oct 1', mood: 'Good' },
  { day: 'Wed', date: 'Oct 2', mood: 'Okay' },
  { day: 'Thu', date: 'Oct 3', mood: 'Good' },
  { day: 'Fri', date: 'Oct 4', mood: 'Great' },
  { day: 'Sat', date: 'Oct 5', mood: 'Great' },
  { day: 'Sun', date: 'Oct 6', mood: 'Good' }
])

const goals = ref([
  {
    id: 1,
    title: 'Practice Mindfulness Daily',
    description: '10 minutes of meditation each day',
    progress: 75,
    status: 'On Track',
    dueDate: 'Oct 31, 2025',
    milestones: 4
  },
  {
    id: 2,
    title: 'Improve Sleep Quality',
    description: 'Get 8 hours of sleep consistently',
    progress: 60,
    status: 'In Progress',
    dueDate: 'Nov 15, 2025',
    milestones: 3
  },
  {
    id: 3,
    title: 'Reduce Anxiety Levels',
    description: 'Apply CBT techniques regularly',
    progress: 85,
    status: 'On Track',
    dueDate: 'Oct 20, 2025',
    milestones: 5
  }
])

const sleepData = ref([
  { date: 'Oct 1', hours: 7.5 },
  { date: 'Oct 2', hours: 8.2 },
  { date: 'Oct 3', hours: 6.8 },
  { date: 'Oct 4', hours: 7.9 },
  { date: 'Oct 5', hours: 8.5 }
])

const activityData = ref([
  { day: 'Monday', minutes: 45, percentage: 75 },
  { day: 'Tuesday', minutes: 60, percentage: 100 },
  { day: 'Wednesday', minutes: 30, percentage: 50 },
  { day: 'Thursday', minutes: 55, percentage: 92 },
  { day: 'Friday', minutes: 40, percentage: 67 }
])

const journalEntries = ref([
  {
    id: 1,
    title: 'Feeling accomplished today',
    content: 'Had a productive therapy session. Learned new coping strategies for managing work stress. Feeling more in control of my emotions.',
    date: 'Oct 2, 2025',
    mood: 'Great'
  },
  {
    id: 2,
    title: 'Challenging day but managing',
    content: 'Work was stressful but I used the breathing techniques Dr. Chen taught me. They really helped calm me down.',
    date: 'Sep 30, 2025',
    mood: 'Okay'
  },
  {
    id: 3,
    title: 'Grateful for progress',
    content: 'Looking back at where I started, I can see so much growth. The anxiety exercises are becoming second nature now.',
    date: 'Sep 28, 2025',
    mood: 'Good'
  }
])

const getMoodColor = (mood: string) => {
  const colors: Record<string, string> = {
    'Great': 'text-emerald-600',
    'Good': 'text-blue-600',
    'Okay': 'text-yellow-600',
    'Bad': 'text-orange-600',
    'Terrible': 'text-red-600'
  }
  return colors[mood] || 'text-gray-600'
}

const getMoodBorderColor = (mood: string) => {
  const colors: Record<string, string> = {
    'Great': 'border-emerald-600',
    'Good': 'border-blue-600',
    'Okay': 'border-yellow-600',
    'Bad': 'border-orange-600',
    'Terrible': 'border-red-600'
  }
  return colors[mood] || 'border-gray-600'
}

const getMoodIcon = (mood: string) => {
  if (mood === 'Great' || mood === 'Good') return Smile
  if (mood === 'Okay') return Meh
  return Frown
}

const getGoalBorderColor = (progress: number) => {
  if (progress >= 75) return 'border-emerald-600'
  if (progress >= 50) return 'border-blue-600'
  if (progress >= 25) return 'border-yellow-600'
  return 'border-red-600'
}

const getProgressBarColor = (progress: number) => {
  if (progress >= 75) return 'bg-emerald-600'
  if (progress >= 50) return 'bg-blue-600'
  if (progress >= 25) return 'bg-yellow-600'
  return 'bg-red-600'
}
</script>

<style scoped>
/* Smooth transitions */
button, div {
  transition: all 0.2s ease;
}
</style>