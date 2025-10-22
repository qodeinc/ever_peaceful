<!-- src/views/admin/Resources.vue -->
<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-3xl font-bold text-gray-900">Resource Library</h1>
      <button class="px-4 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors flex items-center gap-2">
        <Plus class="w-4 h-4" />
        Add Resource
      </button>
    </div>
    
    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200">
      <div class="flex flex-col sm:flex-row gap-4 items-center">
        <div class="relative flex-1">
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Search resources..." 
            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
          />
          <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
        </div>
        <div class="flex space-x-2">
          <button 
            @click="setResourceFilter('all')"
            :class="[
              'px-3 py-1 rounded-lg text-sm font-medium transition-colors',
              resourceFilter === 'all' ? 'bg-teal-600 text-white' : 'text-gray-600 hover:text-teal-600'
            ]"
          >
            All
          </button>
          <button 
            @click="setResourceFilter('pdf')"
            :class="[
              'px-3 py-1 rounded-lg text-sm font-medium transition-colors',
              resourceFilter === 'pdf' ? 'bg-teal-600 text-white' : 'text-gray-600 hover:text-teal-600'
            ]"
          >
            PDFs
          </button>
          <button 
            @click="setResourceFilter('video')"
            :class="[
              'px-3 py-1 rounded-lg text-sm font-medium transition-colors',
              resourceFilter === 'video' ? 'bg-teal-600 text-white' : 'text-gray-600 hover:text-teal-600'
            ]"
          >
            Videos
          </button>
        </div>
      </div>
    </div>
    
    <!-- Resources Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="resource in paginatedResources" :key="resource.id" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-6">
          <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-900 text-lg">{{ resource.title }}</h3>
            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
              {{ resource.type }}
            </span>
          </div>
          <p class="text-sm text-gray-600 mb-4">{{ resource.description }}</p>
          <div class="flex items-center justify-between text-xs text-gray-500">
            <span>{{ resource.format }}</span>
            <button @click="editResource(resource.id)" class="text-teal-600 hover:text-teal-800 font-medium">
              Edit
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Pagination -->
    <div v-if="filteredResources.length > 0" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200">
      <div class="flex items-center justify-between">
        <p class="text-sm text-gray-700">
          Showing {{ (currentPage - 1) * itemsPerPage + 1 }} to {{ Math.min(currentPage * itemsPerPage, filteredResources.length) }} of {{ filteredResources.length }} resources
        </p>
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
          <button
            @click="currentPage = Math.max(currentPage - 1, 1)"
            :disabled="currentPage === 1"
            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
          >
            <ChevronLeft class="h-5 w-5" />
          </button>
          <button
            v-for="page in totalPages"
            :key="page"
            @click="currentPage = page"
            :class="[
              'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
              currentPage === page
                ? 'z-10 bg-teal-50 border-teal-500 text-teal-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
            ]"
          >
            {{ page }}
          </button>
          <button
            @click="currentPage = Math.min(currentPage + 1, totalPages)"
            :disabled="currentPage === totalPages"
            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
          >
            <ChevronRight class="h-5 w-5" />
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { BookOpen, Search, Edit, ChevronLeft, ChevronRight } from 'lucide-vue-next'

const searchQuery = ref('')
const resourceFilter = ref('all')
const currentPage = ref(1)
const itemsPerPage = 9

const resources = ref([
  { id: 1, title: 'Mindfulness Guide', description: 'Guide to daily mindfulness practices', type: 'PDF', format: 'PDF • 12 pages' },
  { id: 2, title: 'Breathing Exercises', description: 'Video tutorial for anxiety relief', type: 'Video', format: 'Video • 10 min' },
  { id: 3, title: 'CBT Workbook', description: 'Cognitive behavioral therapy exercises', type: 'PDF', format: 'PDF • 24 pages' },
  { id: 4, title: 'Crisis Resources', description: 'Emergency contacts and safety plan', type: 'Guide', format: 'Guide • Quick Access' },
  { id: 5, title: 'Journal Template', description: 'Daily reflection prompts', type: 'PDF', format: 'PDF • 8 pages' },
  { id: 6, title: 'Relaxation Audio', description: 'Guided meditation for sleep', type: 'Audio', format: 'Audio • 30 min' },
  { id: 7, title: 'Self-Care Planner', description: 'Weekly wellness planning tool', type: 'Guide', format: 'Guide • Printable' },
  { id: 8, title: 'Stress Management', description: 'Techniques for workplace stress', type: 'Video', format: 'Video • 15 min' },
  { id: 9, title: 'Emotional Intelligence', description: 'Building EQ workbook', type: 'PDF', format: 'PDF • 16 pages' },
  { id: 10, title: 'Grief Support', description: 'Coping with loss guide', type: 'Audio', format: 'Audio • 20 min' }
])

const filteredResources = computed(() => {
  let filtered = resources.value
  
  if (searchQuery.value) {
    filtered = filtered.filter(resource => 
      resource.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      resource.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }
  
  if (resourceFilter.value !== 'all') {
    filtered = filtered.filter(resource => resource.type.toLowerCase() === resourceFilter.value)
  }
  
  return filtered
})

const totalPages = computed(() => Math.ceil(filteredResources.value.length / itemsPerPage))

const paginatedResources = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredResources.value.slice(start, end)
})

const setResourceFilter = (filter) => {
  resourceFilter.value = filter
  currentPage.value = 1
}

const editResource = (id) => {
  console.log(`Edit resource ${id}`)
}
</script>

<style scoped>
</style>