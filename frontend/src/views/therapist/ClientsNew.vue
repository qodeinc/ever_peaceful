<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-3xl font-bold text-gray-900">Clients</h2>
        <p class="text-gray-600 mt-1">Manage your client roster</p>
      </div>
      <button class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl hover:shadow-lg transition-all flex items-center gap-2">
        <Plus class="w-5 h-5" />
        New Client
      </button>
    </div>

    <div class="grid grid-cols-4 gap-4">
      <div v-for="stat in clientStats" :key="stat.label" class="bg-white rounded-2xl shadow-lg border border-emerald-100 p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-medium">{{ stat.label }}</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ stat.value }}</p>
            <p :class="['text-sm font-medium mt-1 flex items-center gap-1', stat.trend === 'up' ? 'text-emerald-600' : 'text-gray-600']">
              <TrendingUp class="w-4 h-4" />
              {{ stat.change }}
            </p>
          </div>
          <component :is="stat.icon" :class="['w-12 h-12', stat.color]" />
        </div>
      </div>
    </div>

    <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
      <div class="flex items-center gap-4 mb-6">
        <div class="flex-1 relative">
          <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search clients by name, condition, or status..."
            class="w-full pl-12 pr-4 py-3 rounded-2xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none"
          />
        </div>
        <button class="px-5 py-3 bg-gray-100 rounded-2xl hover:bg-gray-200 transition-colors flex items-center gap-2 font-medium">
          <Filter class="w-5 h-5" />
          Filter
        </button>
        <select v-model="sortBy" class="px-5 py-3 bg-gray-100 rounded-2xl hover:bg-gray-200 transition-colors outline-none font-medium">
          <option value="name">Sort by Name</option>
          <option value="lastSession">Last Session</option>
          <option value="priority">Priority</option>
          <option value="progress">Progress</option>
        </select>
      </div>

      <div class="grid grid-cols-3 gap-5">
        <div
          v-for="client in filteredClients"
          :key="client.id"
          class="bg-gradient-to-br from-emerald-50/50 to-teal-50/50 rounded-2xl p-6 border-2 border-emerald-100 hover:border-emerald-400 hover:shadow-xl transition-all cursor-pointer group"
          @click="viewClient(client.id)"
        >
          <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
              <Mail class="w-5 h-5 text-gray-400" />
              <div>
                <p class="text-xs text-gray-500">Email</p>
                <p class="text-sm font-medium text-gray-900">{{ client.email }}</p>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <Phone class="w-5 h-5 text-gray-400" />
              <div>
                <p class="text-xs text-gray-500">Phone</p>
                <p class="text-sm font-medium text-gray-900">{{ client.phone }}</p>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <User class="w-5 h-5 text-gray-400" />
              <div>
                <p class="text-xs text-gray-500">Emergency Contact</p>
                <p class="text-sm font-medium text-gray-900">{{ client.emergencyContact }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Risk Assessment</h3>
          <div class="flex items-center justify-center p-6 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl border border-emerald-200">
            <Shield class="w-10 h-10 text-emerald-600 mr-3" />
            <div>
              <p class="text-3xl font-bold text-emerald-600">Low Risk</p>
              <p class="text-sm text-gray-600 mt-1">Last assessed: {{ client.lastAssessment }}</p>
            </div>
          </div>
          <button class="w-full mt-4 px-4 py-3 bg-white border-2 border-emerald-200 text-emerald-700 rounded-xl hover:bg-emerald-50 transition-all font-medium">
            Update Assessment
          </button>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Stats</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-emerald-50/30 to-teal-50/30 rounded-xl">
              <span class="text-gray-700 font-medium">Total Sessions</span>
              <span class="font-bold text-gray-900 text-lg">{{ client.totalSessions }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-emerald-50/30 to-teal-50/30 rounded-xl">
              <span class="text-gray-700 font-medium">Attendance Rate</span>
              <span class="font-bold text-emerald-600 text-lg">{{ client.attendanceRate }}%</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-emerald-50/30 to-teal-50/30 rounded-xl">
              <span class="text-gray-700 font-medium">No-Shows</span>
              <span class="font-bold text-gray-900 text-lg">{{ client.noShows }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-emerald-50/30 to-teal-50/30 rounded-xl">
              <span class="text-gray-700 font-medium">Next Session</span>
              <span class="font-bold text-gray-900 text-lg">{{ client.nextSession }}</span>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Recent Activity</h3>
          <div class="space-y-3">
            <div v-for="activity in client.recentActivity" :key="activity.id" class="flex gap-3">
              <div class="w-2 h-2 rounded-full bg-emerald-500 mt-2"></div>
              <div>
                <p class="text-sm font-medium text-gray-900">{{ activity.title }}</p>
                <p class="text-xs text-gray-500">{{ activity.time }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { ArrowLeft, MessageSquare, Phone, Calendar, Mail, User, Shield, Eye, Target, CheckCircle } from 'lucide-vue-next';

const client = ref({
  id: 1,
  name: 'Sarah Johnson',
  initials: 'SJ',
  condition: 'Anxiety & Depression',
  status: 'active',
  clientSince: 'Jan 2024',
  email: 'sarah.j@email.com',
  phone: '+1 (555) 123-4567',
  emergencyContact: 'John Johnson - (555) 987-6543',
  lastAssessment: 'Sep 15, 2025',
  totalSessions: 24,
  attendanceRate: 92,
  noShows: 2,
  nextSession: 'Oct 2, 2025',
  sessions: [
    { id: 1, number: 24, type: 'Individual Therapy', date: 'Sep 27, 2025', duration: '50 min', summary: 'Continued work on anxiety management techniques. Client showed improvement in recognizing triggers and implementing coping strategies.', tags: ['Anxiety', 'CBT', 'Progress'] },
    { id: 2, number: 23, type: 'Individual Therapy', date: 'Sep 20, 2025', duration: '50 min', summary: 'Discussed recent stressors at work. Introduced mindfulness exercises for daily practice.', tags: ['Stress', 'Mindfulness'] },
    { id: 3, number: 22, type: 'Individual Therapy', date: 'Sep 13, 2025', duration: '50 min', summary: 'Review of mood journal entries. Notable reduction in depressive symptoms over past two weeks.', tags: ['Depression', 'Mood Tracking'] }
  ],
  goals: [
    { id: 1, name: 'Reduce Anxiety Symptoms', progress: 75, description: 'Decrease panic attacks from 2-3/week to <1/month' },
    { id: 2, name: 'Improve Sleep Quality', progress: 85, description: 'Achieve 7-8 hours of restful sleep per night' },
    { id: 3, name: 'Develop Coping Strategies', progress: 65, description: 'Build toolkit of 5+ effective coping mechanisms' }
  ],
  treatmentPlan: [
    { id: 1, title: 'Cognitive Behavioral Therapy (CBT)', description: 'Weekly sessions focusing on identifying and challenging negative thought patterns' },
    { id: 2, title: 'Mindfulness Practice', description: 'Daily 10-minute meditation and breathing exercises' },
    { id: 3, title: 'Medication Management', description: 'Coordination with psychiatrist for SSRI monitoring' }
  ],
  recentActivity: [
    { id: 1, title: 'Session completed', time: '2 days ago' },
    { id: 2, title: 'Homework submitted', time: '4 days ago' },
    { id: 3, title: 'Payment received', time: '1 week ago' }
  ]
});

const goBack = () => {
  console.log('Go back');
};
</script>