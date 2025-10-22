<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-3xl font-bold text-gray-900">Clients</h1>
      <button 
        @click="setActiveComponent('clients-new')"
        class="px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors flex items-center space-x-2"
      >
        <UserPlus class="w-4 h-4" />
        <span>Add New Client</span>
      </button>
    </div>
    
    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
      <div class="flex flex-col sm:flex-row gap-4 items-center">
        <div class="relative flex-1">
          <input 
            v-model="searchQuery"
            type="text" 
            placeholder="Search clients..." 
            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
          />
          <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
        </div>
        <div class="flex space-x-2">
          <button 
            @click="setFilter('all')"
            :class="[
              'px-3 py-1 rounded-lg text-sm font-medium transition-colors',
              activeFilter === 'all' ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:text-emerald-600'
            ]"
          >
            All
          </button>
          <button 
            @click="setFilter('active')"
            :class="[
              'px-3 py-1 rounded-lg text-sm font-medium transition-colors',
              activeFilter === 'active' ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:text-emerald-600'
            ]"
          >
            Active
          </button>
          <button 
            @click="setFilter('inactive')"
            :class="[
              'px-3 py-1 rounded-lg text-sm font-medium transition-colors',
              activeFilter === 'inactive' ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:text-emerald-600'
            ]"
          >
            Inactive
          </button>
        </div>
      </div>
    </div>
    
    <!-- Clients Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Next Session</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sessions</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Note</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr 
              v-for="client in paginatedClients" 
              :key="client.id"
              class="hover:bg-gray-50"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-white font-medium text-sm flex-shrink-0">
                    {{ client.initials }}
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ client.name }}</div>
                    <div class="text-sm text-gray-500">{{ client.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="client.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                >
                  {{ client.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ client.nextSession }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ client.sessions }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ client.lastNote }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                <button 
                  @click="viewClientDetails(client.id)"
                  class="text-emerald-600 hover:text-emerald-900 p-1 rounded"
                  title="View Details"
                >
                  <Eye class="w-4 h-4" />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div v-if="filteredClients.length > 0" class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
          <button
            @click="currentPage = Math.max(currentPage - 1, 1)"
            :disabled="currentPage === 1"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
          >
            Previous
          </button>
          <button
            @click="currentPage = Math.min(currentPage + 1, totalPages)"
            :disabled="currentPage === totalPages"
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
          >
            Next
          </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Showing
              <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
              to
              <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredClients.length) }}</span>
              of
              <span class="font-medium">{{ filteredClients.length }}</span>
              results
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
              <button
                @click="currentPage = Math.max(currentPage - 1, 1)"
                :disabled="currentPage === 1"
                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
              >
                <span class="sr-only">Previous</span>
                <ChevronLeft class="h-5 w-5" />
              </button>
              <button
                v-for="page in totalPages"
                :key="page"
                @click="currentPage = page"
                :class="[
                  'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium',
                  currentPage === page
                    ? 'z-10 bg-emerald-50 border-emerald-500 text-emerald-600'
                    : 'bg-white text-gray-500 hover:bg-gray-50'
                ]"
              >
                {{ page }}
              </button>
              <button
                @click="currentPage = Math.min(currentPage + 1, totalPages)"
                :disabled="currentPage === totalPages"
                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
              >
                <span class="sr-only">Next</span>
                <ChevronRight class="h-5 w-5" />
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>
    
    <!-- No Clients Message -->
    <div v-if="filteredClients.length === 0" class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-200">
      <Users class="w-16 h-16 text-gray-400 mx-auto mb-4" />
      <h3 class="text-lg font-medium text-gray-900 mb-2">No clients found</h3>
      <p class="text-gray-500 mb-4">Try adjusting your search or filters.</p>
      <button 
        @click="setActiveComponent('clients-new')"
        class="px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors"
      >
        Add Your First Client
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { UserPlus, Search, Users, ChevronLeft, ChevronRight, Eye } from 'lucide-vue-next'

const emit = defineEmits(['set-active-component'])

// Sample clients data
const clients = ref([
  { 
    id: 1, 
    name: 'Wanjiku Mwangi', 
    initials: 'WM', 
    email: 'wanjiku.mwangi@email.co.ke', 
    status: 'active', 
    nextSession: 'Oct 15, 2025', 
    sessions: 8, 
    lastNote: 'Sep 25, 2025' 
  },
  { 
    id: 2, 
    name: 'Ochieng Kiprop', 
    initials: 'OK', 
    email: 'ochieng.kiprop@email.co.ke', 
    status: 'active', 
    nextSession: 'Oct 10, 2025', 
    sessions: 5, 
    lastNote: 'Sep 30, 2025' 
  },
  { 
    id: 3, 
    name: 'Muthoni Njoroge', 
    initials: 'MN', 
    email: 'muthoni.njoroge@email.co.ke', 
    status: 'inactive', 
    nextSession: 'TBD', 
    sessions: 12, 
    lastNote: 'Aug 15, 2025' 
  },
  { 
    id: 4, 
    name: 'Achieng Omondi', 
    initials: 'AO', 
    email: 'achieng.omondi@email.co.ke', 
    status: 'active', 
    nextSession: 'Oct 5, 2025', 
    sessions: 3, 
    lastNote: 'Sep 28, 2025' 
  }
])

const searchQuery = ref('')
const activeFilter = ref('all')
const currentPage = ref(1)
const itemsPerPage = 5

const filteredClients = computed(() => {
  let filtered = clients.value
  
  // Search filter
  if (searchQuery.value) {
    filtered = filtered.filter(client => 
      client.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      client.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }
  
  // Status filter
  if (activeFilter.value !== 'all') {
    filtered = filtered.filter(client => client.status === activeFilter.value)
  }
  
  return filtered
})

const totalPages = computed(() => {
  return Math.ceil(filteredClients.value.length / itemsPerPage)
})

const paginatedClients = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredClients.value.slice(start, end)
})

const setFilter = (filter: string) => {
  activeFilter.value = filter
  currentPage.value = 1
}

const viewClientDetails = (id: number) => {
  // Navigate to client details
  emit('set-active-component', 'client-details')
  console.log(`Viewing client details for ID: ${id}`)
}

const setActiveComponent = (name: string) => {
  emit('set-active-component', name)
}
</script>

<style scoped>
</style>