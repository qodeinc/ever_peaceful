<template>
  <div class="space-y-6">
    <!-- Header -->
    <div>
      <h2 class="text-3xl font-bold text-gray-900">Progress Tracker</h2>
      <p class="text-gray-600 mt-1">Monitor your wellness journey</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">{{ stat.label }}</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ stat.value }}</p>
            <p :class="['text-xs mt-2 flex items-center gap-1', stat.trend === 'up' ? 'text-green-600' : 'text-red-600']">
              <TrendingUp v-if="stat.trend === 'up'" class="w-3 h-3" />
              <TrendingDown v-else class="w-3 h-3" />
              <span>{{ stat.change }}</span>
            </p>
          </div>
          <div :class="['w-12 h-12 rounded-full flex items-center justify-center', stat.bgColor]">
            <component :is="stat.icon" :class="['w-6 h-6', stat.iconColor]" />
          </div>
        </div>
      </div>
    </div>

    <!-- Mood Tracker -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h3 class="text-xl font-semibold text-gray-900">Mood Tracker</h3>
          <p class="text-sm text-gray-600">Last 7 days</p>
        </div>
        <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors text-sm font-medium">
          Log Mood
        </button>
      </div>

      <div class="grid grid-cols-7 gap-2">
        <div
          v-for="day in moodData"
          :key="day.date"
          class="text-center"
        >
          <p class="text-xs text-gray-500 mb-2">{{ day.day }}</p>
          <div
            :class="[
              'w-full aspect-square rounded-xl flex items-center justify-center cursor-pointer transition-transform hover:scale-110',
              getMoodColor(day.mood)
            ]"
            :title="day.mood"
          >
            <component :is="getMoodIcon(day.mood)" class="w-6 h-6 text-white" />
          </div>
          <p class="text-xs text-gray-600 mt-1">{{ day.mood }}</p>
        </div>
      </div>
    </div>

    <!-- Goals Progress -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h3 class="text-xl font-semibold text-gray-900">Goals Progress</h3>
          <p class="text-sm text-gray-600">Your wellness goals</p>
        </div>
        <button class="px-4 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors text-sm font-medium">
          Add Goal
        </button>
      </div>

      <div class="space-y-4">
        <div
          v-for="goal in goals"
          :key="goal.id"
          class="border border-gray-200 rounded-xl p-4"
        >
          <div class="flex items-start justify-between mb-3">
            <div class="flex-1">
              <h4 class="font-semibold text-gray-900">{{ goal.title }}</h4>
              <p class="text-sm text-gray-600">{{ goal.description }}</p>
            </div>
            <span :class="['px-3 py-1 rounded-full text-xs font-medium', getGoalStatusColor(goal.status)]">
              {{ goal.status }}
            </span>
          </div>

          <div class="space-y-2">
            <div class="flex items-center justify-between text-sm">
              <span class="text-gray-600">Progress</span>
              <span class="font-medium text-gray-900">{{ goal.progress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div
                :class="['h-2 rounded-full transition-all duration-300', getProgressBarColor(goal.progress)]"
                :style="{ width: `${goal.progress}%` }"
              ></div>
            </div>
          </div>

          <div class="flex items-center justify-between mt-3 text-sm text-gray-500">
            <div class="flex items-center gap-1">
              <Calendar class="w-4 h-4" />
              <span>Due: {{ goal.dueDate }}</span>
            </div>
            <div class="flex items-center gap-1">
              <Target class="w-4 h-4" />
              <span>{{ goal.milestones }} milestones</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Wellness Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Sleep Quality -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Sleep Quality</h3>
        <div class="space-y-3">
          <div
            v-for="sleep in sleepData"
            :key="sleep.date"
            class="flex items-center gap-3"
          >
            <span class="text-sm text-gray-600 w-16">{{ sleep.date }}</span>
            <div class="flex-1 bg-gray-200 rounded-full h-2">
              <div
                class="bg-indigo-500 h-2 rounded-full"
                :style="{ width: `${(sleep.hours / 10) * 100}%` }"
              ></div>
            </div>
            <span class="text-sm font-medium text-gray-900 w-16 text-right">{{ sleep.hours }}h</span>
          </div>
        </div>
      </div>

      <!-- Activity Level -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Activity Level</h3>
        <div class="space-y-3">
          <div
            v-for="activity in activityData"
            :key="activity.day"
            class="flex items-center gap-3"
          >
            <span class="text-sm text-gray-600 w-16">{{ activity.day }}</span>
            <div class="flex-1 bg-gray-200 rounded-full h-2">
              <div
                class="bg-green-500 h-2 rounded-full"
                :style="{ width: `${activity.percentage}%` }"
              ></div>
            </div>
            <span class="text-sm font-medium text-gray-900 w-16 text-right">{{ activity.minutes }} min</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Journal Entries -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h3 class="text-xl font-semibold text-gray-900">Recent Journal Entries</h3>
          <p class="text-sm text-gray-600">Your thoughts and reflections</p>
        </div>
        <button class="px-4 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors text-sm font-medium">
          New Entry
        </button>
      </div>

      <div class="space-y-4">
        <div
          v-for="entry in journalEntries"
          :key="entry.id"
          class="border border-gray-200 rounded-xl p-4 hover:border-teal-300 transition-colors cursor-pointer"
        >
          <div class="flex items-start justify-between mb-2">
            <div class="flex items-center gap-2">
              <BookOpen class="w-5 h-5 text-teal-600" />
              <span class="font-medium text-gray-900">{{ entry.title }}</span>
            </div>
            <span class="text-sm text-gray-500">{{ entry.date }}</span>
          </div>
          <p class="text-sm text-gray-600 line-clamp-2">{{ entry.content }}</p>
          <div class="flex items-center gap-2 mt-3">
            <component :is="getMoodIcon(entry.mood)" :class="['w-4 h-4', getMoodTextColor(entry.mood)]" />
            <span class="text-sm text-gray-600">Mood: {{ entry.mood }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { 
  TrendingUp, TrendingDown, Calendar, Target, BookOpen, 
  Heart, Brain, Smile, Frown, Meh, Trophy, Activity
} from 'lucide-vue-next'

const stats = ref([
  {
    label: 'Total Sessions',
    value: '24',
    change: '+3 this month',
    trend: 'up',
    icon: Calendar,
    bgColor: 'bg-blue-100',
    iconColor: 'text-blue-600'
  },
  {
    label: 'Goals Achieved',
    value: '8/12',
    change: '+2 this week',
    trend: 'up',
    icon: Trophy,
    bgColor: 'bg-green-100',
    iconColor: 'text-green-600'
  },
  {
    label: 'Avg Mood Score',
    value: '7.8',
    change: '+0.5 from last week',
    trend: 'up',
    icon: Heart,
    bgColor: 'bg-pink-100',
    iconColor: 'text-pink-600'
  },
  {
    label: 'Streak Days',
    value: '15',
    change: 'Keep it up!',
    trend: 'up',
    icon: Activity,
    bgColor: 'bg-purple-100',
    iconColor: 'text-purple-600'
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
  { day: 'Mon', minutes: 45, percentage: 75 },
  { day: 'Tue', minutes: 60, percentage: 100 },
  { day: 'Wed', minutes: 30, percentage: 50 },
  { day: 'Thu', minutes: 55, percentage: 92 },
  { day: 'Fri', minutes: 40, percentage: 67 }
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
  }
])

const getMoodColor = (mood: string) => {
  const colors: Record<string, string> = {
    'Great': 'bg-green-500',
    'Good': 'bg-blue-500',
    'Okay': 'bg-yellow-500',
    'Bad': 'bg-orange-500',
    'Terrible': 'bg-red-500'
  }
  return colors[mood] || 'bg-gray-500'
}

const getMoodTextColor = (mood: string) => {
  const colors: Record<string, string> = {
    'Great': 'text-green-600',
    'Good': 'text-blue-600',
    'Okay': 'text-yellow-600',
    'Bad': 'text-orange-600',
    'Terrible': 'text-red-600'
  }
  return colors[mood] || 'text-gray-600'
}

const getMoodIcon = (mood: string) => {
  if (mood === 'Great' || mood === 'Good') return Smile
  if (mood === 'Okay') return Meh
  return Frown
}

const getGoalStatusColor = (status: string) => {
  const colors: Record<string, string> = {
    'On Track': 'bg-green-100 text-green-700',
    'In Progress': 'bg-blue-100 text-blue-700',
    'At Risk': 'bg-yellow-100 text-yellow-700',
    'Completed': 'bg-purple-100 text-purple-700'
  }
  return colors[status] || 'bg-gray-100 text-gray-700'
}

const getProgressBarColor = (progress: number) => {
  if (progress >= 75) return 'bg-green-500'
  if (progress >= 50) return 'bg-blue-500'
  if (progress >= 25) return 'bg-yellow-500'
  return 'bg-red-500'
}
</script>