<template>
  <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Book a Session</h2>
    <p class="text-gray-600 mb-6">Schedule a therapy session with a licensed therapist.</p>
    <form @submit.prevent="bookSession">
      <div class="space-y-6">
        <!-- Therapist Selection -->
        <div>
          <label for="therapist" class="block text-sm font-medium text-gray-700">Select Therapist</label>
          <select 
            v-model="form.therapistId" 
            id="therapist" 
            class="mt-1 w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
          >
            <option value="" disabled>Select a therapist</option>
            <option v-for="therapist in therapists" :key="therapist.id" :value="therapist.id">
              {{ therapist.name }} ({{ therapist.specialty }})
            </option>
          </select>
        </div>

        <!-- Session Type -->
        <div>
          <label for="sessionType" class="block text-sm font-medium text-gray-700">Session Type</label>
          <select 
            v-model="form.sessionType" 
            id="sessionType" 
            class="mt-1 w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
          >
            <option value="" disabled>Select session type</option>
            <option value="individual">Individual Therapy</option>
            <option value="group">Group Session</option>
            <option value="consultation">Initial Consultation</option>
          </select>
        </div>

        <!-- Date and Time -->
        <div>
          <label for="dateTime" class="block text-sm font-medium text-gray-700">Date & Time</label>
          <input 
            type="datetime-local" 
            v-model="form.dateTime" 
            id="dateTime" 
            class="mt-1 w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
            :min="minDateTime"
          />
        </div>

        <!-- Submit Button -->
        <button 
          type="submit" 
          :disabled="!form.therapistId || !form.sessionType || !form.dateTime"
          class="w-full bg-teal-600 text-white py-3 px-4 rounded-xl hover:bg-teal-700 transition-colors font-medium disabled:bg-gray-300 disabled:cursor-not-allowed"
        >
          Book Session
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useDashboardNavigation } from '../../composables/useDashboardNavigation'

const { setActiveComponent } = useDashboardNavigation()

// Form state
const form = ref({
  therapistId: '',
  sessionType: '',
  dateTime: ''
})

// Mock therapists
const therapists = ref([
  { id: 1, name: 'Dr. Emily Chen', specialty: 'Cognitive Behavioral Therapy' },
  { id: 2, name: 'Dr. Michael Rodriguez', specialty: 'Family Therapy' },
  { id: 3, name: 'Dr. Aisha Mwangi', specialty: 'Anxiety Management' }
])

// Minimum date (today)
const minDateTime = computed(() => {
  const today = new Date()
  return today.toISOString().slice(0, 16)
})

// Methods
const bookSession = async () => {
  try {
    console.log('Booking session:', form.value)
    alert('Session booked successfully!')
    setActiveComponent('dashboard')
  } catch (error) {
    console.error('Error booking session:', error)
    alert('Failed to book session.')
  }
}
</script>

<style scoped>
/* Button and input styling */
button, input, select {
  position: relative;
  overflow: hidden;
}

button::before, input::before, select::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s;
}

button:hover::before, input:focus::before, select:focus::before {
  left: 100%;
}
</style>