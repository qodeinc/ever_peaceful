<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
      <!-- Header -->
      <div class="mb-16">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-8">
          <div class="max-w-2xl">
            <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">Therapists</h1>
            <p class="text-lg text-gray-500 leading-relaxed">Professional support when you need it. Confidential, secure, personalized.</p>
          </div>
          <div class="relative md:w-96">
            <input 
              v-model="searchQuery" 
              type="text" 
              placeholder="Search by name or specialty" 
              class="w-full pl-11 pr-4 py-3.5 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 text-base bg-transparent transition-colors"
            />
            <Search class="absolute left-0 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="mb-12 border-b border-gray-200">
        <div class="flex flex-wrap gap-6 pb-6">
          <button
            @click="selectedSpecialization = ''"
            :class="[
              'text-sm font-medium transition-colors pb-2 border-b-2',
              !selectedSpecialization
                ? 'text-emerald-600 border-emerald-600'
                : 'text-gray-500 border-transparent hover:text-gray-900'
            ]"
          >
            All
          </button>
          <button
            v-for="spec in specializations"
            :key="spec"
            @click="selectedSpecialization = selectedSpecialization === spec ? '' : spec"
            :class="[
              'text-sm font-medium transition-colors pb-2 border-b-2',
              selectedSpecialization === spec
                ? 'text-emerald-600 border-emerald-600'
                : 'text-gray-500 border-transparent hover:text-gray-900'
            ]"
          >
            {{ spec }}
          </button>
        </div>
      </div>

      <!-- All Therapists Grid -->
      <section class="mb-20">
        <div class="flex items-baseline justify-between mb-8">
          <h2 class="text-sm uppercase tracking-wider text-gray-500">All Therapists</h2>
          <p class="text-sm text-gray-500">{{ filteredTherapists.length }} available</p>
        </div>
        
        <div v-if="paginatedTherapists.length > 0">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div 
              v-for="therapist in paginatedTherapists" 
              :key="therapist.id"
              class="group bg-white border border-gray-200 hover:border-emerald-500 transition-all duration-300 cursor-pointer"
              @click="bookSession(therapist.id)"
            >
              <!-- Content -->
              <div class="p-6 text-center">
                <!-- Avatar -->
                <div class="mb-4">
                  <img :src="getAvatar(therapist.id)" class="w-24 h-24 rounded-full object-cover mx-auto border-4 border-gray-100 group-hover:border-emerald-100 transition-all" alt="" />
                </div>
                
                <div class="mb-3">
                  <h3 class="text-xl font-light text-gray-900 mb-1">{{ therapist.name }}</h3>
                  <p class="text-sm text-gray-500">{{ therapist.experience }} years experience</p>
                </div>
                
                <p class="text-sm text-gray-600 mb-4 line-clamp-2 leading-relaxed">{{ therapist.bio }}</p>
                
                <!-- Specializations -->
                <div class="flex flex-wrap gap-2 mb-4 justify-center">
                  <span 
                    v-for="spec in therapist.specializations.slice(0, 2)" 
                    :key="spec"
                    class="text-xs text-emerald-700 bg-emerald-50 px-2 py-1"
                  >
                    {{ spec }}
                  </span>
                  <span v-if="therapist.specializations.length > 2" class="text-xs text-gray-500 px-2 py-1">
                    +{{ therapist.specializations.length - 2 }}
                  </span>
                </div>
                
                <!-- Rate & CTA -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                  <span class="text-lg font-light text-gray-900">KSh {{ therapist.rate.toLocaleString() }}</span>
                  <ChevronRight class="w-5 h-5 text-gray-400 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all" />
                </div>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div class="flex items-center justify-center gap-2 pt-8 border-t border-gray-200">
            <button
              @click="currentPage = Math.max(currentPage - 1, 1)"
              :disabled="currentPage === 1"
              class="px-4 py-2 text-sm font-medium disabled:opacity-30 disabled:cursor-not-allowed transition-all"
              :class="currentPage === 1 ? 'text-gray-400' : 'text-gray-900 hover:text-emerald-600'"
            >
              Previous
            </button>
            
            <div class="flex gap-1">
              <button
                v-for="pageNum in totalPages"
                :key="pageNum"
                @click="currentPage = pageNum"
                class="w-10 h-10 text-sm font-medium transition-colors"
                :class="currentPage === pageNum ? 'text-emerald-600 bg-emerald-50' : 'text-gray-500 hover:text-gray-900'"
              >
                {{ pageNum }}
              </button>
            </div>
            
            <button
              @click="currentPage = Math.min(currentPage + 1, totalPages)"
              :disabled="currentPage === totalPages"
              class="px-4 py-2 text-sm font-medium disabled:opacity-30 disabled:cursor-not-allowed transition-all"
              :class="currentPage === totalPages ? 'text-gray-400' : 'text-gray-900 hover:text-emerald-600'"
            >
              Next
            </button>
          </div>
        </div>

        <!-- No Therapists Message -->
        <div v-else class="text-center py-24">
          <Users class="w-12 h-12 text-gray-300 mx-auto mb-4" />
          <h3 class="text-xl font-light text-gray-900 mb-2">No therapists found</h3>
          <p class="text-gray-500 mb-6">Try adjusting your search or filter</p>
          <button 
            @click="selectedSpecialization = ''; searchQuery = ''"
            class="px-6 py-3 bg-emerald-600 text-white hover:bg-emerald-700 transition-colors text-sm font-medium"
          >
            Clear filters
          </button>
        </div>
      </section>

      <!-- Recommended Therapists Section -->
      <section v-if="recommendedTherapists.length > 0" class="border-t border-gray-200 pt-20">
        <h2 class="text-sm uppercase tracking-wider text-gray-500 mb-2">Recommended for You</h2>
        <p class="text-base text-gray-600 mb-8">Based on your profile and preferences</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div 
            v-for="therapist in recommendedTherapists" 
            :key="therapist.id"
            class="group bg-emerald-50/50 border border-emerald-200 hover:border-emerald-500 transition-all duration-300 cursor-pointer"
            @click="bookSession(therapist.id)"
          >
            <div class="p-6">
              <div class="flex items-start gap-4 mb-4">
                <img :src="getAvatar(therapist.id)" class="w-16 h-16 object-cover flex-shrink-0" alt="" />
                <div class="flex-1 min-w-0">
                  <h3 class="text-lg font-light text-gray-900 mb-1">{{ therapist.name }}</h3>
                  <p class="text-sm text-gray-600">{{ therapist.experience }} years</p>
                </div>
              </div>
              
              <p class="text-sm text-gray-700 mb-4 leading-relaxed line-clamp-3">{{ therapist.bio }}</p>
              
              <div class="flex flex-wrap gap-2 mb-4">
                <span 
                  v-for="spec in therapist.specializations.slice(0, 3)" 
                  :key="spec"
                  class="text-xs text-emerald-700 bg-white px-2 py-1"
                >
                  {{ spec }}
                </span>
              </div>
              
              <div class="flex items-center justify-between pt-4 border-t border-emerald-200">
                <span class="text-base font-light text-gray-900">KSh {{ therapist.rate.toLocaleString() }}</span>
                <span class="text-sm text-emerald-700 group-hover:text-emerald-800 font-medium">Book now →</span>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- Booking Modal -->
    <div v-if="showBookingModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-6">
      <div class="bg-white max-w-lg w-full">
        <div class="p-8 border-b border-gray-200">
          <div class="flex items-start justify-between">
            <div>
              <h3 class="text-2xl font-light text-gray-900 mb-1">Book Session</h3>
              <p class="text-sm text-gray-500">{{ selectedTherapist?.name }}</p>
            </div>
            <button @click="showBookingModal = false" class="text-gray-400 hover:text-gray-900 transition-colors">
              <X class="w-6 h-6" />
            </button>
          </div>
        </div>
        
        <div class="p-8 space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-900 mb-3">Date & Time</label>
            <input 
              type="datetime-local" 
              v-model="bookingDateTime"
              class="w-full px-4 py-3 border border-gray-200 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition-colors"
              min="2025-10-22T00:00"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-900 mb-3">Session Type</label>
            <select v-model="bookingType" class="w-full px-4 py-3 border border-gray-200 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition-colors">
              <option value="individual">Individual Therapy — KSh 5,000</option>
              <option value="group">Group Session — KSh 2,500</option>
              <option value="family">Family Session — KSh 7,000</option>
            </select>
          </div>
          <button 
            @click="confirmBooking"
            class="w-full bg-emerald-600 text-white py-4 hover:bg-emerald-700 transition-colors font-medium"
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
import { Users, Search, X, ChevronRight } from 'lucide-vue-next'

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
    bio: 'Experienced in cognitive behavioral therapy with a focus on anxiety and stress management. Licensed for 12 years.', 
    specializations: ['Anxiety', 'Stress Management', 'CBT'], 
    experience: 12, 
    availability: 'Available', 
    rate: 5000 
  },
  { 
    id: 2, 
    name: 'Dr. Kipchoge Kiptoo',
    bio: 'Specialist in trauma recovery and PTSD treatment using EMDR and somatic approaches.', 
    specializations: ['Trauma', 'PTSD', 'EMDR'], 
    experience: 8, 
    availability: 'Available', 
    rate: 6000 
  },
  { 
    id: 3, 
    name: 'Dr. Muthoni Njoroge',
    bio: 'Relationship and family therapist helping couples navigate communication and intimacy challenges.', 
    specializations: ['Relationships', 'Family Therapy', 'Couples'], 
    experience: 15, 
    availability: 'Available', 
    rate: 7000 
  },
  { 
    id: 4, 
    name: 'Dr. Omondi Achieng',
    bio: 'Holistic wellness coach specializing in self-esteem building and positive psychology techniques.', 
    specializations: ['Self-Esteem', 'Positive Psychology', 'Mindfulness'], 
    experience: 10, 
    availability: 'Available', 
    rate: 4500 
  },
  { 
    id: 5, 
    name: 'Dr. Wangari Mwangi',
    bio: 'Depression specialist using integrative approaches including medication management and therapy.', 
    specializations: ['Depression', 'Integrative Therapy', 'Medication'], 
    experience: 14, 
    availability: 'Available', 
    rate: 5500 
  },
  { 
    id: 6, 
    name: 'Dr. Juma Kiprop',
    bio: 'Adolescent therapist focusing on identity, peer pressure, and academic stress issues.', 
    specializations: ['Adolescents', 'Identity Issues', 'Academic Stress'], 
    experience: 9, 
    availability: 'Available', 
    rate: 5000 
  },
  { 
    id: 7, 
    name: 'Dr. Njeri Kamau',
    bio: 'Grief counselor specializing in loss, bereavement, and life transitions with compassionate support.', 
    specializations: ['Grief', 'Loss', 'Life Transitions'], 
    experience: 11, 
    availability: 'Available', 
    rate: 5500 
  },
  { 
    id: 8, 
    name: 'Dr. Hassan Mohamed',
    bio: 'Addiction specialist helping individuals overcome substance abuse and behavioral addictions.', 
    specializations: ['Addiction', 'Substance Abuse', 'Recovery'], 
    experience: 13, 
    availability: 'Available', 
    rate: 6500 
  },
  { 
    id: 9, 
    name: 'Dr. Chebet Korir',
    bio: 'Women\'s mental health specialist addressing postpartum depression, anxiety, and hormonal changes.', 
    specializations: ['Women\'s Health', 'Postpartum', 'Hormonal Changes'], 
    experience: 10, 
    availability: 'Available', 
    rate: 5800 
  },
  { 
    id: 10, 
    name: 'Dr. Peter Karanja',
    bio: 'Career and workplace stress counselor helping professionals manage burnout and work-life balance.', 
    specializations: ['Career Stress', 'Burnout', 'Work-Life Balance'], 
    experience: 7, 
    availability: 'Available', 
    rate: 4800 
  },
  { 
    id: 11, 
    name: 'Dr. Akinyi Odhiambo',
    bio: 'Child psychologist specializing in developmental issues, behavioral problems, and family dynamics.', 
    specializations: ['Child Psychology', 'Behavioral Issues', 'Family Dynamics'], 
    experience: 16, 
    availability: 'Available', 
    rate: 6200 
  },
  { 
    id: 12, 
    name: 'Dr. Samuel Maina',
    bio: 'Mindfulness-based therapist integrating meditation and stress reduction techniques into treatment.', 
    specializations: ['Mindfulness', 'Meditation', 'Stress Reduction'], 
    experience: 9, 
    availability: 'Available', 
    rate: 5200 
  }
])

// Function to get avatar image
const getAvatar = (id) => {
  const avatars = [av1, av2, av3, av4, av5, av6, av7]
  return avatars[(id - 1) % avatars.length]
}

// Simulate user profile-based recommendations
const recommendedTherapists = computed(() => {
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

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

input[type="datetime-local"]::-webkit-calendar-picker-indicator {
  cursor: pointer;
}
</style>