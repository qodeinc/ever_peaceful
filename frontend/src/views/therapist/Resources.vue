<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Resources Library</h1>
        <p class="text-gray-600 mt-1">Curated materials for client support and professional development</p>
      </div>
      <div class="flex gap-3">
        <div class="relative">
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Search resources..." 
            class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 w-64"
          />
          <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
        </div>
        <button 
          @click="showAddModal = true"
          class="px-4 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors flex items-center gap-2 text-sm font-medium"
        >
          <Plus class="w-4 h-4" />
          Add Resource
        </button>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200">
      <div class="flex flex-wrap gap-2">
        <button
          v-for="filter in resourceTypes"
          :key="filter"
          @click="selectedType = selectedType === filter ? '' : filter"
          :class="[
            'px-4 py-2 rounded-xl text-sm font-medium transition-all',
            selectedType === filter
              ? 'bg-teal-600 text-white shadow-sm'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          ]"
        >
          {{ filter }}
        </button>
        <button
          @click="selectedType = ''"
          :class="[
            'px-4 py-2 rounded-xl text-sm font-medium transition-all',
            selectedType ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-teal-600 text-white shadow-sm'
          ]"
        >
          All
        </button>
      </div>
    </div>

    <!-- Resources Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="resource in filteredResources" 
        :key="resource.id"
        class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow cursor-pointer group"
        @click="openResource(resource.id)"
      >
        <!-- Icon Section with Solid Color -->
        <div 
          :class="[
            'h-40 flex items-center justify-center',
            resource.type === 'PDF' ? 'bg-teal-100' :
            resource.type === 'Video' ? 'bg-blue-100' :
            resource.type === 'Audio' ? 'bg-green-100' :
            resource.type === 'Guide' ? 'bg-purple-100' : 'bg-indigo-100'
          ]"
        >
          <component 
            :is="resource.icon" 
            :class="[
              'w-12 h-12 opacity-80 group-hover:opacity-100 transition-opacity',
              resource.type === 'PDF' ? 'text-teal-600' :
              resource.type === 'Video' ? 'text-blue-600' :
              resource.type === 'Audio' ? 'text-green-600' :
              resource.type === 'Guide' ? 'text-purple-600' : 'text-indigo-600'
            ]" 
          />
        </div>
        
        <!-- Content -->
        <div class="p-6">
          <div class="flex items-center justify-between mb-2">
            <h3 class="font-semibold text-gray-900 text-lg leading-tight">{{ resource.title }}</h3>
            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
              {{ resource.type }}
            </span>
          </div>
          <p class="text-sm text-gray-600 mb-4 line-clamp-3 leading-relaxed">{{ resource.description }}</p>
          <div class="flex items-center justify-between text-xs text-gray-500">
            <span>{{ resource.format }}</span>
            <button 
              class="text-teal-600 hover:text-teal-800 font-medium flex items-center gap-1 transition-colors"
              @click.stop="handleAction(resource)"
            >
              <Download class="w-3 h-3" />
              {{ resource.action }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- No Resources Message -->
    <div v-if="filteredResources.length === 0" class="text-center py-12 bg-white rounded-2xl shadow-sm border border-gray-200">
      <BookOpen class="w-16 h-16 text-gray-400 mx-auto mb-4" />
      <h3 class="text-lg font-medium text-gray-900 mb-2">No resources found</h3>
      <p class="text-gray-500 mb-4">Try adjusting your search or type filter.</p>
      <button 
        @click="showAddModal = true"
        class="px-6 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors"
      >
        Add a Resource
      </button>
    </div>

    <!-- Add Resource Modal -->
    <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-2xl p-6 max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Add New Resource</h3>
          <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600">
            <X class="w-5 h-5" />
          </button>
        </div>
        <form @submit.prevent="createResource">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
              <input 
                v-model="newResource.title" 
                type="text" 
                placeholder="Resource title..."
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
              <textarea 
                v-model="newResource.description" 
                placeholder="Brief description..."
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                required
              ></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
              <select 
                v-model="newResource.type" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                required
              >
                <option value="">Select type...</option>
                <option value="PDF">PDF</option>
                <option value="Video">Video</option>
                <option value="Audio">Audio</option>
                <option value="Guide">Guide</option>
              </select>
            </div>
            <div v-if="newResource.type === 'Video'">
              <label class="block text-sm font-medium text-gray-700 mb-1">Video URL (YouTube/Vimeo)</label>
              <input 
                v-model="newResource.url" 
                type="url" 
                placeholder="https://youtube.com/watch?v=..."
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
              />
            </div>
            <div v-else>
              <label class="block text-sm font-medium text-gray-700 mb-1">File Upload</label>
              <input 
                ref="fileInput"
                type="file" 
                @change="handleFileUpload"
                :accept="newResource.type === 'PDF' ? '.pdf' : newResource.type === 'Audio' ? 'audio/*' : newResource.type === 'Guide' ? '.doc,.docx,.pdf' : '*/*'"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
              />
              <div v-if="newResource.file" class="mt-2 p-2 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-900">{{ newResource.file.name }}</p>
                <p class="text-xs text-gray-600">{{ formatFileSize(newResource.file.size) }}</p>
              </div>
            </div>
            <div class="flex gap-2 pt-2">
              <button 
                type="submit"
                :disabled="!newResource.title || !newResource.description || !newResource.type || (newResource.type === 'Video' ? !newResource.url : !newResource.file)"
                class="flex-1 py-2 px-4 bg-teal-600 text-white rounded-lg hover:bg-teal-700 disabled:bg-gray-400 transition-colors"
              >
                Add Resource
              </button>
              <button 
                type="button"
                @click="showAddModal = false"
                class="flex-1 py-2 px-4 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors"
              >
                Cancel
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { 
  BookOpen, PlayCircle, Headphones, GraduationCap, AlertTriangle, 
  Download, Search, Plus, X 
} from 'lucide-vue-next'

const searchQuery = ref('')
const selectedType = ref('')
const showAddModal = ref(false)
const newResource = ref({
  title: '',
  description: '',
  type: '',
  url: '',
  file: null
})
const fileInput = ref(null)

const resourceTypes = ['PDF', 'Video', 'Audio', 'Guide']

const resources = ref([
  { 
    id: 1, 
    title: 'Mindfulness Guide', 
    description: 'A comprehensive guide to mindfulness practices for stress reduction and emotional regulation.', 
    type: 'PDF', 
    icon: BookOpen, 
    format: 'PDF • 12 pages', 
    action: 'Download',
    file: { name: 'mindfulness.pdf', size: 1024000, type: 'application/pdf' },
    url: null 
  },
  { 
    id: 2, 
    title: 'Breathing Exercises Video', 
    description: 'Guided breathing exercises for anxiety management (10 minutes).', 
    type: 'Video', 
    icon: PlayCircle, 
    format: 'Video • 10 min', 
    action: 'Watch',
    file: null,
    url: 'https://www.youtube.com/watch?v=example' 
  },
  { 
    id: 3, 
    title: 'CBT Workbook', 
    description: 'Cognitive Behavioral Therapy exercises for client homework.', 
    type: 'PDF', 
    icon: GraduationCap, 
    format: 'PDF • 24 pages', 
    action: 'Download',
    file: { name: 'cbt-workbook.pdf', size: 2048000, type: 'application/pdf' },
    url: null 
  },
  { 
    id: 4, 
    title: 'Crisis Resources', 
    description: 'Emergency contacts and safety planning templates.', 
    type: 'Guide', 
    icon: AlertTriangle, 
    format: 'Guide • Quick Access', 
    action: 'View',
    file: null,
    url: 'https://example.com/crisis-guide' 
  },
  { 
    id: 5, 
    title: 'Journaling Template', 
    description: 'Daily reflection prompts to help clients track emotions and patterns.', 
    type: 'PDF', 
    icon: BookOpen, 
    format: 'PDF • 8 pages', 
    action: 'Download',
    file: { name: 'journal-template.pdf', size: 819200, type: 'application/pdf' },
    url: null 
  },
  { 
    id: 6, 
    title: 'Guided Relaxation Audio', 
    description: '30-minute progressive muscle relaxation for bedtime routine.', 
    type: 'Audio', 
    icon: Headphones, 
    format: 'Audio • 30 min', 
    action: 'Listen',
    file: { name: 'relaxation.mp3', size: 5120000, type: 'audio/mpeg' },
    url: null 
  },
  { 
    id: 7, 
    title: 'Self-Care Planner', 
    description: 'Weekly planner with self-care activities and wellness check-ins.', 
    type: 'Guide', 
    icon: GraduationCap, 
    format: 'Guide • Printable', 
    action: 'View',
    file: { name: 'self-care-planner.docx', size: 1536000, type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' },
    url: null 
  }
])

const filteredResources = computed(() => {
  let filtered = resources.value
  
  if (searchQuery.value) {
    filtered = filtered.filter(resource => 
      resource.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      resource.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }
  
  if (selectedType) {
    filtered = filtered.filter(resource => resource.type === selectedType)
  }
  
  return filtered
})

const handleFileUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    // Validate size (e.g., 50MB max)
    if (file.size > 50 * 1024 * 1024) {
      alert('File too large. Max 50MB.')
      return
    }
    newResource.value.file = file
  }
}

const createResource = () => {
  if (!newResource.value.title || !newResource.value.description || !newResource.value.type) return
  
  let resourceData = {
    id: Date.now(),
    title: newResource.value.title,
    description: newResource.value.description,
    type: newResource.value.type,
    icon: getIconForType(newResource.value.type),
    format: getFormat(newResource.value),
    action: getAction(newResource.value.type),
    file: newResource.value.file,
    url: newResource.value.url
  }
  
  resources.value.push(resourceData)
  showAddModal.value = false
  newResource.value = { title: '', description: '', type: '', url: '', file: null }
  fileInput.value.value = '' // Reset file input
}

const getIconForType = (type) => {
  switch (type) {
    case 'PDF': return BookOpen
    case 'Video': return PlayCircle
    case 'Audio': return Headphones
    case 'Guide': return GraduationCap
    default: return BookOpen
  }
}

const getFormat = (resource) => {
  if (resource.type === 'Video') return 'Video • URL'
  if (resource.file) return `${resource.type} • ${resource.file.name}`
  return `${resource.type} • Link`
}

const getAction = (type) => {
  switch (type) {
    case 'PDF': return 'Download'
    case 'Video': return 'Watch'
    case 'Audio': return 'Listen'
    case 'Guide': return 'View'
    default: return 'Open'
  }
}

const handleAction = (resource) => {
  if (resource.file) {
    // Download file
    const url = URL.createObjectURL(resource.file)
    const a = document.createElement('a')
    a.href = url
    a.download = resource.file.name
    a.click()
    URL.revokeObjectURL(url)
  } else if (resource.url) {
    // Open URL
    window.open(resource.url, '_blank')
  }
}

const openResource = (id) => {
  const resource = resources.value.find(r => r.id === id)
  if (resource) {
    handleAction(resource)
  }
}

const addResource = () => {
  showAddModal.value = true
  newResource.value = { title: '', description: '', type: '', url: '', file: null }
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