<template>
  <div class="space-y-6">
    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Total Users</p>
            <p class="text-2xl font-bold text-gray-900">{{ formatNumber(platformStats.totalUsers) }}</p>
            <p class="text-xs mt-1" :class="platformStats.userGrowth >= 0 ? 'text-green-600' : 'text-red-600'">
              {{ platformStats.userGrowth >= 0 ? '↑' : '↓' }} {{ Math.abs(platformStats.userGrowth) }}% this month
            </p>
          </div>
          <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
            <Users class="w-6 h-6 text-blue-600" />
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Active Therapists</p>
            <p class="text-2xl font-bold text-gray-900">{{ platformStats.activeTherapists }}</p>
            <p class="text-xs text-green-600 mt-1">↑ {{ platformStats.newTherapists }} new this month</p>
          </div>
          <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
            <Heart class="w-6 h-6 text-emerald-600" />
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Sessions Today</p>
            <p class="text-2xl font-bold text-gray-900">{{ platformStats.sessionsToday }}</p>
            <p class="text-xs mt-1" :class="platformStats.sessionGrowth >= 0 ? 'text-green-600' : 'text-red-600'">
              {{ platformStats.sessionGrowth >= 0 ? '↑' : '↓' }} {{ Math.abs(platformStats.sessionGrowth) }}% vs yesterday
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
            <p class="text-sm text-gray-600">Revenue (MTD)</p>
            <p class="text-2xl font-bold text-gray-900">{{ formatCurrency(revenueStats.thisMonth) }}</p>
            <p class="text-xs text-green-600 mt-1">↑ {{ revenueStats.growth }}% vs last month</p>
          </div>
          <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center">
            <Banknote class="w-6 h-6 text-green-600" />
          </div>
        </div>
      </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">User Growth Trend</h3>
        <div class="h-64 bg-gradient-to-r from-teal-100 to-cyan-100 rounded-xl flex items-center justify-center">
          <div class="text-center">
            <TrendingUp class="w-16 h-16 text-teal-400 mx-auto mb-4" />
            <p class="text-gray-600">Chart Integration</p>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Activity</h3>
        <div class="space-y-4 max-h-64 overflow-y-auto">
          <div v-for="activity in recentActivity" :key="activity.id" 
               class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                 :class="activity.bgColor">
              <component :is="activity.icon" class="w-4 h-4" :class="activity.iconColor" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-gray-900">{{ activity.title }}</p>
              <p class="text-xs text-gray-600">{{ activity.time }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Users, Heart, Calendar, TrendingUp, Banknote } from 'lucide-vue-next'

// Platform statistics
const platformStats = ref({
  totalUsers: 2847,
  userGrowth: 12,
  activeTherapists: 156,
  newTherapists: 5,
  sessionsToday: 342,
  sessionGrowth: 8
})

// Revenue stats
const revenueStats = ref({
  thisMonth: 4725000, // Adjusted to KSh (e.g., 47,250 USD * 100 for KSh)
  growth: 12.2
})

// Recent activity
const recentActivity = ref([
  {
    id: 1,
    title: 'New therapist registered',
    time: '5 min ago',
    icon: Heart,
    bgColor: 'bg-emerald-100',
    iconColor: 'text-emerald-600'
  },
  {
    id: 2,
    title: 'User account suspended',
    time: '15 min ago',
    icon: Users,
    bgColor: 'bg-red-100',
    iconColor: 'text-red-600'
  },
  {
    id: 3,
    title: 'System backup completed',
    time: '1 hour ago',
    icon: Calendar,
    bgColor: 'bg-blue-100',
    iconColor: 'text-blue-600'
  }
])

// Methods
const formatNumber = (num: number) => {
  return new Intl.NumberFormat('en-KE').format(num)
}

const formatCurrency = (amount: number) => {
  return `KSh ${amount.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
}

onMounted(() => {
  console.log('Admin dashboard content mounted')
})
</script>