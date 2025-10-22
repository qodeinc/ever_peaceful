<template>
  <div class="min-h-screen bg-gradient-to-br from-teal-50/50 via-white to-emerald-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Book a Session</h1>
            <p class="text-gray-600">Find the perfect therapist for your needs. All sessions are confidential and secure.</p>
          </div>
          <div class="relative md:w-80">
            <input 
              v-model="searchQuery" 
              type="text" 
              placeholder="Search therapists by name or specialization..." 
              class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm"
            />
            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="mb-8">
        <div class="flex flex-wrap gap-2">
          <button
            v-for="spec in specializations"
            :key="spec"
            @click="selectedSpecialization = selectedSpecialization === spec ? '' : spec"
            :class="[
              'px-4 py-2 rounded-xl text-sm font-medium transition-all',
              selectedSpecialization === spec
                ? 'bg-teal-600 text-white shadow-sm'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]"
          >
            {{ spec }}
          </button>
          <button
            @click="selectedSpecialization = ''"
            :class="[
              'px-4 py-2 rounded-xl text-sm font-medium transition-all',
              selectedSpecialization ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-teal-600 text-white shadow-sm'
            ]"
          >
            All Specializations
          </button>
        </div>
        <p v-if="selectedSpecialization" class="text-xs text-gray-500 mt-2">
          Showing {{ filteredTherapists.length }} therapists specializing in {{ selectedSpecialization }}
        </p>
      </div>

      <!-- Recommended Therapists Section -->
      <section v-if="recommendedTherapists.length > 0" class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Recommended for You</h2>
        <p class="text-gray-600 mb-6">Based on your profile and past sessions, these therapists may be a great match.</p>
        <div class="space-y-4">
          <div 
            v-for="therapist in recommendedTherapists" 
            :key="therapist.id"
            class="flex items-start gap-4 p-4 bg-white rounded-xl border border-gray-200 hover:border-teal-300 transition-all duration-300 cursor-pointer"
            @click="bookSession(therapist.id)"
          >
            <!-- Avatar -->
            <img :src="getAvatar(therapist.id)" class="w-16 h-16 rounded-full flex-shrink-0" alt="Therapist Avatar" />
            
            <!-- Content -->
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between mb-2">
                <h3 class="font-bold text-gray-900 text-lg leading-tight flex-1 pr-4 truncate">{{ therapist.name }}</h3>
                <div class="flex flex-col items-end">
                  <span class="px-2 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-medium">
                    {{ therapist.availability }}
                  </span>
                  <span class="text-xs text-gray-500 mt-1">{{ therapist.experience }} years exp.</span>
                </div>
              </div>
              
              <!-- Specializations -->
              <div class="mb-2">
                <p class="text-xs text-gray-600 mb-1">Specializations:</p>
                <div class="flex flex-wrap gap-1">
                  <span 
                    v-for="spec in therapist.specializations.slice(0, 2)" 
                    :key="spec"
                    class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs"
                  >
                    {{ spec }}
                  </span>
                  <span v-if="therapist.specializations.length > 2" class="text-xs text-gray-500">+{{ therapist.specializations.length - 2 }} more</span>
                </div>
              </div>
              
              <!-- Bio Snippet -->
              <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed mb-3">{{ therapist.bio }}</p>
              
              <!-- Book Button -->
              <button 
                class="text-teal-600 hover:text-teal-700 text-sm font-medium flex items-center gap-1 transition-colors"
              >
                Book Session (KSh {{ therapist.rate }}) <ChevronRight class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- All Therapists Grid with Pagination -->
      <section>
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-gray-900">All Therapists</h2>
          <p class="text-sm text-gray-500">{{ filteredTherapists.length }} available</p>
        </div>
        <div v-if="paginatedTherapists.length > 0" class="space-y-4 mb-6">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div 
              v-for="therapist in paginatedTherapists" 
              :key="therapist.id"
              class="bg-white rounded-xl p-4 border border-gray-200 hover:border-teal-300 hover:shadow-md transition-all duration-300 cursor-pointer compact-card"
              @click="bookSession(therapist.id)"
            >
              <!-- Avatar -->
              <img :src="getAvatar(therapist.id)" class="w-16 h-16 rounded-full mx-auto mb-3 flex-shrink-0" alt="Therapist Avatar" />
              
              <!-- Name -->
              <h3 class="font-bold text-gray-900 text-base text-center mb-2 truncate">{{ therapist.name }}</h3>
              
              <!-- Availability Badge -->
              <span class="block w-full px-2 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-medium text-center mb-2">
                {{ therapist.availability }}
              </span>
              
              <!-- Specializations -->
              <div class="mb-2">
                <div class="flex flex-wrap justify-center gap-1 mb-2">
                  <span 
                    v-for="spec in therapist.specializations.slice(0, 2)" 
                    :key="spec"
                    class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs"
                  >
                    {{ spec }}
                  </span>
                  <span v-if="therapist.specializations.length > 2" class="text-xs text-gray-500">+{{ therapist.specializations.length - 2 }}</span>
                </div>
              </div>
              
              <!-- Rate -->
              <p class="text-center text-sm text-gray-600 mb-3">KSh {{ therapist.rate }} / session</p>
              
              <!-- Book Button -->
              <button 
                class="w-full bg-teal-600 text-white py-2 px-4 rounded-xl hover:bg-teal-700 transition-colors font-medium text-sm"
              >
                Book Now
              </button>
            </div>
          </div>
        </div>

        <!-- No Therapists Message -->
        <div v-else class="text-center py-12 bg-white rounded-2xl shadow-sm border border-gray-200">
          <Users class="w-16 h-16 text-gray-400 mx-auto mb-4" />
          <h3 class="text-lg font-medium text-gray-900 mb-2">No therapists found</h3>
          <p class="text-gray-500 mb-4">Try adjusting your search or specialization filter.</p>
          <button 
            @click="selectedSpecialization = ''"
            class="px-6 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors"
          >
            Clear Filters
          </button>
        </div>

        <!-- Pagination -->
        <div v-if="filteredTherapists.length > 0" class="flex items-center justify-center space-x-2 mt-8">
          <button
            @click="currentPage = Math.max(currentPage - 1, 1)"
            :disabled="currentPage === 1"
            class="px-3 py-2 text-sm font-medium rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
            :class="currentPage === 1 ? 'bg-gray-200 text-gray-500' : 'bg-teal-600 text-white hover:bg-teal-700'"
          >
            Previous
          </button>
          
          <button
            v-for="pageNum in totalPages"
            :key="pageNum"
            @click="currentPage = pageNum"
            class="px-3 py-2 text-sm font-medium rounded-lg"
            :class="currentPage === pageNum ? 'bg-teal-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
          >
            {{ pageNum }}
          </button>
          
          <button
            @click="currentPage = Math.min(currentPage + 1, totalPages)"
            :disabled="currentPage === totalPages"
            class="px-3 py-2 text-sm font-medium rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
            :class="currentPage === totalPages ? 'bg-gray-200 text-gray-500' : 'bg-teal-600 text-white hover:bg-teal-700'"
          >
            Next
          </button>
        </div>
      </section>
    </div>

    <!-- Booking Modal -->
    <div v-if="showBookingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-2xl p-6 max-w-md w-full">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Book Session with {{ selectedTherapist?.name }}</h3>
          <button @click="showBookingModal = false" class="text-gray-400 hover:text-gray-600">
            <X class="w-5 h-5" />
          </button>
        </div>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Date & Time</label>
            <input 
              type="datetime-local" 
              v-model="bookingDateTime"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
              min="2025-10-22T00:00"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Session Type</label>
            <select v-model="bookingType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
              <option value="individual">Individual Therapy (KSh 5,000)</option>
              <option value="group">Group Session (KSh 2,500)</option>
              <option value="family">Family Session (KSh 7,000)</option>
            </select>
          </div>
          <button 
            @click="confirmBooking"
            class="w-full bg-teal-600 text-white py-3 px-4 rounded-xl hover:bg-teal-700 transition-colors font-medium"
          >
            Confirm Booking
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Calendar, Users, Search, X, ChevronRight } from 'lucide-vue-next'

// Import local avatar images
import av1 from '@/assets/images/av1.jpg'
import av2 from '@/assets/images/av2.jpg'
import av3 from '@/assets/images/av3.jpg'
import av4 from '@/assets/images/av4.jpg'
import av5 from '@/assets/images/av5.jpg'
import av6 from '@/assets/images/av6.jpg'
import av7 from '@/assets/images/av7.jpg'

const searchQuery = ref('')
const selectedSpecialization = ref('')
const currentPage = ref(1)
const showBookingModal = ref(false)
const selectedTherapist = ref(null)
const bookingDateTime = ref('')
const bookingType = ref('individual')

const specializations = ['Anxiety', 'Depression', 'Trauma', 'Relationships', 'Stress Management', 'Self-Esteem']

const therapists = ref([
  { 
    id: 1, 
    name: 'Dr. Amina Otieno', 
    initials: 'AO', 
    bio: 'Experienced in cognitive behavioral therapy with a focus on anxiety and stress management. Licensed for 12 years.', 
    specializations: ['Anxiety', 'Stress Management', 'CBT'], 
    experience: 12, 
    availability: 'Available', 
    rate: 5000 
  },
  { 
    id: 2, 
    name: 'Dr. Kipchoge Kiptoo', 
    initials: 'KK', 
    bio: 'Specialist in trauma recovery and PTSD treatment using EMDR and somatic approaches.', 
    specializations: ['Trauma', 'PTSD', 'EMDR'], 
    experience: 8, 
    availability: 'Available', 
    rate: 6000 
  },
  { 
    id: 3, 
    name: 'Dr. Muthoni Njoroge', 
    initials: 'MN', 
    bio: 'Relationship and family therapist helping couples navigate communication and intimacy challenges.', 
    specializations: ['Relationships', 'Family Therapy', 'Couples'], 
    experience: 15, 
    availability: 'Available', 
    rate: 7000 
  },
  { 
    id: 4, 
    name: 'Dr. Omondi Achieng', 
    initials: 'OA', 
    bio: 'Holistic wellness coach specializing in self-esteem building and positive psychology techniques.', 
    specializations: ['Self-Esteem', 'Positive Psychology', 'Mindfulness'], 
    experience: 10, 
    availability: 'Available', 
    rate: 4500 
  },
  { 
    id: 5, 
    name: 'Dr. Wangari Mwangi', 
    initials: 'WM', 
    bio: 'Depression specialist using integrative approaches including medication management and therapy.', 
    specializations: ['Depression', 'Integrative Therapy', 'Medication'], 
    experience: 14, 
    availability: 'Available', 
    rate: 5500 
  },
  { 
    id: 6, 
    name: 'Dr. Juma Kiprop', 
    initials: 'JK', 
    bio: 'Adolescent therapist focusing on identity, peer pressure, and academic stress issues.', 
    specializations: ['Adolescents', 'Identity Issues', 'Academic Stress'], 
    experience: 9, 
    availability: 'Available', 
    rate: 5000 
  }
])

// Function to get avatar image
const getAvatar = (id) => {
  const avatars = [av1, av2, av3, av4, av5, av6, av7]
  return avatars[(id - 1) % avatars.length]
}

// Simulate user profile-based recommendations (in real app, this would come from user data)
const recommendedTherapists = computed(() => {
  // For demo, recommend first 2-3 based on 'user profile' (e.g., anxiety focus)
  return therapists.value.filter(t => t.specializations.includes('Anxiety') || t.specializations.includes('Stress Management')).slice(0, 3)
})

const filteredTherapists = computed(() => {
  let filtered = therapists.value
  
  if (searchQuery.value) {
    filtered = filtered.filter(therapist => 
      therapist.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      therapist.bio.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      therapist.specializations.some(spec => spec.toLowerCase().includes(searchQuery.value.toLowerCase()))
    )
  }
  
  if (selectedSpecialization.value) {
    filtered = filtered.filter(therapist => therapist.specializations.includes(selectedSpecialization.value))
  }
  
  return filtered
})

const therapistsPerPage = 8
const paginatedTherapists = computed(() => {
  const start = (currentPage.value - 1) * therapistsPerPage
  const end = start + therapistsPerPage
  return filteredTherapists.value.slice(start, end)
})

const totalPages = computed(() => Math.ceil(filteredTherapists.value.length / therapistsPerPage))

const bookSession = (therapistId) => {
  selectedTherapist.value = therapists.value.find(t => t.id === therapistId)
  showBookingModal.value = true
  bookingDateTime.value = ''
}

const confirmBooking = async () => {
  if (!bookingDateTime.value) return
  try {
    console.log('Booking confirmed:', {
      therapist: selectedTherapist.value.name,
      dateTime: bookingDateTime.value,
      type: bookingType.value
    })
    alert('Session booked successfully! Confirmation email sent.')
    showBookingModal.value = false
  } catch (error) {
    console.error('Booking error:', error)
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.compact-card {
  height: 300px;
  display: flex;
  flex-direction: column;
}

.compact-card > div {
  flex: 1;
}
</style>