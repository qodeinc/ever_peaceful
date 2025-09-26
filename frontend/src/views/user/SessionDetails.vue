<template>
  <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Session Details</h2>
    <p class="text-gray-600 mb-6">View details for your scheduled session.</p>
    <div v-if="session" class="space-y-6">
      <div>
        <h3 class="text-lg font-semibold text-gray-900">Session Information</h3>
        <div class="mt-2 space-y-2">
          <p><span class="font-medium text-gray-700">Therapist:</span> {{ session.therapist }}</p>
          <p><span class="font-medium text-gray-700">Type:</span> {{ session.type }}</p>
          <p><span class="font-medium text-gray-700">Date:</span> {{ formatDate(session.dateTime) }}</p>
          <p><span class="font-medium text-gray-700">Time:</span> {{ formatTime(session.dateTime) }}</p>
          <p><span class="font-medium text-gray-700">Duration:</span> {{ session.duration }} minutes</p>
          <p><span class="font-medium text-gray-700">Cost:</span> {{ formattedCost }}</p>
        </div>
      </div>
      <div>
        <h3 class="text-lg font-semibold text-gray-900">Notes</h3>
        <p class="text-gray-600 mt-2">{{ session.notes || 'No notes available for this session.' }}</p>
      </div>
      <button 
        @click="goBack"
        class="w-full bg-teal-600 text-white py-3 px-4 rounded-xl hover:bg-teal-700 transition-colors font-medium"
      >
        Back to Dashboard
      </button>
    </div>
    <div v-else class="text-center py-8 text-gray-500">
      Session not found
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { defineProps } from 'vue'
import { useDashboardNavigation } from '../../composables/useDashboardNavigation'

const { setActiveComponent } = useDashboardNavigation()

const props = defineProps<{
  sessionId: number | null
}>()

// Mock session data
const session = computed(() => {
  const sessions = [
    {
      id: 1,
      therapist: 'Dr. Emily Chen',
      type: 'Individual Therapy',
      dateTime: new Date('2025-09-27T14:00:00'),
      duration: 60,
      cost: 5000,
      notes: 'Focus on anxiety management techniques.'
    },
    {
      id: 2,
      therapist: 'Dr. Michael Rodriguez',
      type: 'Group Session',
      dateTime: new Date('2025-09-28T10:00:00'),
      duration: 90,
      cost: 3000,
      notes: 'Discussion on group dynamics and support.'
    }
  ]
  return sessions.find(s => s.id === props.sessionId) || null
})

const formattedCost = computed(() => {
  return session.value ? `KSh ${session.value.cost.toLocaleString('en-KE')}` : 'N/A'
})

const formatDate = (date: Date) => {
  return date.toLocaleDateString('en-US', { 
    month: 'short', 
    day: 'numeric', 
    year: 'numeric',
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

const goBack = () => {
  setActiveComponent('dashboard')
}
</script>

<style scoped>
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
</style>