<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white rounded-2xl p-6 shadow-sm border border-teal-200">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">My Therapists</h1>
        <p class="text-gray-600 mt-1">Your assigned therapists and their availability</p>
      </div>
      <button 
        @click="setActiveComponent('book-session')"
        class="px-6 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors"
      >
        Add Therapist
      </button>
    </div>

    <!-- Therapists Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div 
        v-for="therapist in myTherapists" 
        :key="therapist.id"
        class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer"
        @click="viewTherapist(therapist.id)"
      >
        <div class="flex items-start space-x-4">
          <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-medium text-sm relative">
            {{ therapist.initials }}
            <div v-if="therapist.online" class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></div>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-900 mb-1">{{ therapist.name }}</h3>
            <p class="text-sm text-gray-600 mb-3">{{ therapist.specialization }}</p>
            <div class="space-y-1">
              <p class="text-xs text-gray-500">Next Available: {{ therapist.nextAvailable }}</p>
              <p class="text-xs text-gray-500">Sessions: {{ therapist.sessions }}</p>
            </div>
            <button class="mt-3 px-4 py-2 bg-teal-600 text-white rounded-xl text-sm hover:bg-teal-700 transition-colors">
              Message
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- No Therapists Message -->
    <div v-if="myTherapists.length === 0" class="text-center py-12 bg-white rounded-2xl shadow-sm border border-gray-200">
      <Users class="w-16 h-16 text-gray-400 mx-auto mb-4" />
      <h3 class="text-lg font-medium text-gray-900 mb-2">No therapists assigned</h3>
      <p class="text-gray-500 mb-4">Start by booking your first session</p>
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
import { ref } from 'vue'
import { Users, MessageCircle } from 'lucide-vue-next'
import { useDashboardNavigation } from '../../composables/useDashboardNavigation'

const { setActiveComponent } = useDashboardNavigation()

const myTherapists = ref([
  { 
    id: 1, 
    name: 'Dr. Amina Otieno', 
    initials: 'AO', 
    specialization: 'Anxiety & CBT', 
    nextAvailable: 'Today 2PM', 
    sessions: 5, 
    online: true 
  },
  { 
    id: 2, 
    name: 'Dr. Kipchoge Kiptoo', 
    initials: 'KK', 
    specialization: 'Trauma Recovery', 
    nextAvailable: 'Tomorrow 10AM', 
    sessions: 12, 
    online: false 
  }
])

const viewTherapist = (id) => {
  console.log(`View therapist ${id}`)
}

const messageTherapist = (id) => {
  setActiveComponent('chat')
  console.log(`Message therapist ${id}`)
}
</script>

<style scoped>
</style>