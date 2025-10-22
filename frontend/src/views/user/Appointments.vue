<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white rounded-2xl p-6 shadow-sm border border-teal-200">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">My Appointments</h1>
        <p class="text-gray-600 mt-1">Manage your therapy sessions and schedule new ones</p>
      </div>
      <button 
        @click="setActiveComponent('book-session')"
        class="px-6 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors flex items-center gap-2"
      >
        <Plus class="w-4 h-4" />
        Book New
      </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200">
      <div class="flex flex-wrap gap-2">
        <button
          v-for="filter in appointmentFilters"
          :key="filter"
          @click="selectedFilter = selectedFilter === filter ? '' : filter"
          :class="[
            'px-4 py-2 rounded-xl text-sm font-medium transition-all',
            selectedFilter === filter
              ? 'bg-teal-600 text-white shadow-sm'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          ]"
        >
          {{ filter }}
        </button>
        <button
          @click="selectedFilter = ''"
          :class="[
            'px-4 py-2 rounded-xl text-sm font-medium transition-all',
            selectedFilter ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-teal-600 text-white shadow-sm'
          ]"
        >
          All
        </button>
      </div>
    </div>

    <!-- Appointments List -->
    <div class="space-y-4">
      <div 
        v-for="appointment in filteredAppointments" 
        :key="appointment.id"
        class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer"
        @click="viewAppointment(appointment.id)"
      >
        <div class="flex items-start justify-between">
          <div class="flex items-center space-x-4 flex-1">
            <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-medium text-sm">
              {{ appointment.therapist.initials }}
            </div>
            <div>
              <h3 class="font-semibold text-gray-900">{{ appointment.therapist.name }}</h3>
              <p class="text-sm text-gray-600">{{ appointment.type }}</p>
              <p class="text-xs text-gray-500 mt-1">Session #{{ appointment.number }}</p>
            </div>
          </div>
          <div class="text-right">
            <p class="font-medium text-gray-900">{{ formatDate(appointment.dateTime) }}</p>
            <p class="text-sm text-gray-600">{{ formatTime(appointment.dateTime) }}</p>
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mt-2"
                  :class="appointment.status === 'confirmed' ? 'bg-green-100 text-green-800' : appointment.status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'">
              {{ appointment.status }}
            </span>
          </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-200">
          <p class="text-sm text-gray-600 mb-3">{{ appointment.notes }}</p>
          <div class="flex gap-2">
            <button 
              v-if="appointment.status === 'upcoming'" 
              @click.stop="cancelAppointment(appointment.id)"
              class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors"
            >
              Cancel
            </button>
            <button 
              @click.stop="rescheduleAppointment(appointment.id)"
              class="px-3 py-1 text-xs bg-teal-100 text-teal-700 rounded hover:bg-teal-200 transition-colors"
            >
              Reschedule
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- No Appointments Message -->
    <div v-if="filteredAppointments.length === 0" class="text-center py-12 bg-white rounded-2xl shadow-sm border border-gray-200">
      <Calendar class="w-16 h-16 text-gray-400 mx-auto mb-4" />
      <h3 class="text-lg font-medium text-gray-900 mb-2">No appointments found</h3>
      <p class="text-gray-500 mb-4">Ready to schedule your next session?</p>
      <button 
        @click="setActiveComponent('book-session')"
        class="px-6 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors"
      >
        Book Session
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Calendar, Plus, Search, X } from 'lucide-vue-next'
import { useDashboardNavigation } from '../../composables/useDashboardNavigation'

const { setActiveComponent } = useDashboardNavigation()

const searchQuery = ref('')
const selectedFilter = ref('')

const appointmentFilters = ['Upcoming', 'Completed', 'Cancelled']

const appointments = ref([
  { 
    id: 1, 
    therapist: { name: 'Dr. Amina Otieno', initials: 'AO' }, 
    type: 'Individual Therapy', 
    dateTime: new Date('2025-10-15T14:00:00'), 
    status: 'upcoming', 
    number: 5, 
    notes: 'Anxiety management session' 
  },
  { 
    id: 2, 
    therapist: { name: 'Dr. Kipchoge Kiptoo', initials: 'KK' }, 
    type: 'Trauma Recovery', 
    dateTime: new Date('2025-10-10T10:00:00'), 
    status: 'completed', 
    number: 12, 
    notes: 'EMDR session review' 
  },
  { 
    id: 3, 
    therapist: { name: 'Dr. Muthoni Njoroge', initials: 'MN' }, 
    type: 'Family Therapy', 
    dateTime: new Date('2025-10-05T16:00:00'), 
    status: 'cancelled', 
    number: 3, 
    notes: 'Rescheduled due to family emergency' 
  }
])

const filteredAppointments = computed(() => {
  let filtered = appointments.value
  
  if (searchQuery.value) {
    filtered = filtered.filter(appointment => 
      appointment.therapist.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      appointment.type.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }
  
  if (selectedFilter) {
    filtered = filtered.filter(appointment => appointment.status.toLowerCase() === selectedFilter.toLowerCase())
  }
  
  return filtered
})

const viewAppointment = (id) => {
  console.log(`View appointment ${id}`)
}

const cancelAppointment = (id) => {
  if (confirm('Cancel this appointment?')) {
    console.log(`Cancelled appointment ${id}`)
  }
}

const rescheduleAppointment = (id) => {
  console.log(`Reschedule appointment ${id}`)
}

const formatDate = (date) => {
  const today = new Date()
  if (date.toDateString() === today.toDateString()) return 'Today'
  
  const tomorrow = new Date(today)
  tomorrow.setDate(tomorrow.getDate() + 1)
  if (date.toDateString() === tomorrow.toDateString()) return 'Tomorrow'
  
  return date.toLocaleDateString('en-KE', { month: 'short', day: 'numeric' })
}

const formatTime = (date) => {
  return date.toLocaleTimeString('en-KE', { hour: 'numeric', minute: '2-digit', hour12: true })
}
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>