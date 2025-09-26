<template>
  <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Progress</h2>
    <p class="text-gray-600 mb-6">Track your wellness journey.</p>
    
    <!-- Session History -->
    <div class="mb-8">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Session History</h3>
      <div v-if="sessionHistory.length > 0" class="space-y-4">
        <div v-for="session in sessionHistory" :key="session.id" 
             class="p-4 bg-gray-50 rounded-xl hover:bg-teal-50 transition-colors">
          <div class="flex justify-between">
            <div>
              <p class="font-medium text-gray-900">{{ session.therapist }}</p>
              <p class="text-sm text-gray-600">{{ session.type }}</p>
            </div>
            <p class="text-sm text-gray-600">{{ formatDate(session.date) }}</p>
          </div>
        </div>
      </div>
      <div v-else class="text-center py-8 text-gray-500">
        No session history available
      </div>
    </div>

    <!-- Wellness Goals -->
    <div class="mb-8">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Wellness Goals</h3>
      <div class="space-y-4">
        <div v-for="goal in goals" :key="goal.id" class="flex items-center space-x-3">
          <input type="checkbox" v-model="goal.completed" class="h-5 w-5 text-teal-600 rounded">
          <p :class="goal.completed ? 'line-through text-gray-500' : 'text-gray-900'">{{ goal.title }}</p>
        </div>
      </div>
    </div>

    <!-- Billing -->
    <div>
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Billing</h3>
      <div class="p-4 bg-gray-50 rounded-xl">
        <div class="flex justify-between mb-2">
          <span class="text-gray-600">Total Spent</span>
          <span class="font-medium text-gray-900">{{ formattedTotalSpent }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-gray-600">Pending Invoices</span>
          <span class="font-medium text-gray-900">{{ formattedPendingAmount }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

const sessionHistory = ref([
  { id: 1, therapist: 'Dr. Emily Chen', type: 'Individual Therapy', date: new Date('2025-09-20T14:00:00') },
  { id: 2, therapist: 'Dr. Michael Rodriguez', type: 'Group Session', date: new Date('2025-09-15T10:00:00') }
])

const goals = ref([
  { id: 1, title: 'Practice mindfulness daily', completed: true },
  { id: 2, title: 'Complete 5 therapy sessions', completed: false },
  { id: 3, title: 'Journal twice a week', completed: true }
])

const totalSpent = ref(15000)
const pendingAmount = ref(5000)

const formattedTotalSpent = computed(() => `KSh ${totalSpent.value.toLocaleString('en-KE')}`)
const formattedPendingAmount = computed(() => `KSh ${pendingAmount.value.toLocaleString('en-KE')}`)

const formatDate = (date: Date) => {
  return date.toLocaleDateString('en-US', { 
    month: 'short', 
    day: 'numeric', 
    year: 'numeric',
    timeZone: 'Africa/Nairobi'
  })
}
</script>

<style scoped>
/* Checkbox styling */
input[type="checkbox"] {
  accent-color: #0d9488;
}
</style>