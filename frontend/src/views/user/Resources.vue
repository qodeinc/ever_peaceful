<template>
  <div class="space-y-6">
    <!-- Header -->
    <div>
      <h2 class="text-3xl font-bold text-gray-900">Resources</h2>
      <p class="text-gray-600 mt-1">Access wellness content and materials</p>
    </div>

    <!-- Category Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
      <div class="flex flex-wrap gap-2">
        <button
          v-for="category in categories"
          :key="category"
          @click="activeCategory = category"
          :class="[
            'px-4 py-2 rounded-xl transition-colors text-sm font-medium',
            activeCategory === category
              ? 'bg-teal-600 text-white'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          ]"
        >
          {{ category }}
        </button>
      </div>
    </div>

    <!-- Resources Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="resource in filteredResources"
        :key="resource.id"
        class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group"
      >
        <!-- Thumbnail -->
        <div :class="['h-48 flex items-center justify-center', resource.color]">
          <component :is="resource.icon" class="w-16 h-16 text-white" />
        </div>

        <!-- Content -->
        <div class="p-6 space-y-3">
          <div class="flex items-start justify-between">
            <span :class="['px-3 py-1 rounded-full text-xs font-medium', getCategoryBadge(resource.category)]">
              {{ resource.category }}
            </span>
            <button class="text-gray-400 hover:text-teal-600 transition-colors">
              <Bookmark class="w-5 h-5" />
            </button>
          </div>

          <h3 class="text-lg font-semibold text-gray-900">{{ resource.title }}</h3>
          <p class="text-sm text-gray-600 line-clamp-2">{{ resource.description }}</p>

          <div class="flex items-center gap-4 text-sm text-gray-500 pt-2">
            <div class="flex items-center gap-1">
              <Clock class="w-4 h-4" />
              <span>{{ resource.duration }}</span>
            </div>
            <div class="flex items-center gap-1">
              <Eye class="w-4 h-4" />
              <span>{{ resource.views }}</span>
            </div>
          </div>

          <button class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors text-sm font-medium mt-4">
            <component :is="getActionIcon(resource.type)" class="w-4 h-4" />
            <span>{{ getActionText(resource.type) }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div
      v-if="filteredResources.length === 0"
      class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center"
    >
      <BookOpen class="w-16 h-16 text-gray-300 mx-auto mb-4" />
      <h3 class="text-lg font-semibold text-gray-900 mb-2">No resources found</h3>
      <p class="text-gray-600">Try selecting a different category</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { BookOpen, Video, FileText, Headphones, Clock, Eye, Bookmark, Play, FileDown } from 'lucide-vue-next'

const activeCategory = ref('All')

const categories = ref([
  'All',
  'Articles',
  'Videos',
  'Podcasts',
  'Worksheets',
  'Guides'
])

const resources = ref([
  {
    id: 1,
    title: 'Managing Anxiety in Daily Life',
    description: 'Learn practical techniques to manage anxiety and stress in your everyday routine.',
    category: 'Articles',
    type: 'article',
    duration: '8 min read',
    views: '2.3k',
    icon: FileText,
    color: 'bg-gradient-to-br from-blue-400 to-blue-600'
  },
  {
    id: 2,
    title: 'Mindfulness Meditation Guide',
    description: 'A comprehensive video guide to mindfulness meditation for beginners.',
    category: 'Videos',
    type: 'video',
    duration: '15 min',
    views: '5.1k',
    icon: Video,
    color: 'bg-gradient-to-br from-purple-400 to-purple-600'
  },
  {
    id: 3,
    title: 'Sleep Better Tonight',
    description: 'Podcast episode discussing sleep hygiene and tips for better rest.',
    category: 'Podcasts',
    type: 'audio',
    duration: '42 min',
    views: '1.8k',
    icon: Headphones,
    color: 'bg-gradient-to-br from-green-400 to-green-600'
  },
  {
    id: 4,
    title: 'Cognitive Distortions Worksheet',
    description: 'Identify and challenge negative thought patterns with this interactive worksheet.',
    category: 'Worksheets',
    type: 'pdf',
    duration: '12 pages',
    views: '3.2k',
    icon: FileText,
    color: 'bg-gradient-to-br from-orange-400 to-orange-600'
  },
  {
    id: 5,
    title: 'Building Emotional Resilience',
    description: 'Step-by-step guide to developing emotional strength and resilience.',
    category: 'Guides',
    type: 'pdf',
    duration: '25 pages',
    views: '4.5k',
    icon: BookOpen,
    color: 'bg-gradient-to-br from-teal-400 to-teal-600'
  },
  {
    id: 6,
    title: 'Understanding Depression',
    description: 'Educational video about depression symptoms, causes, and treatments.',
    category: 'Videos',
    type: 'video',
    duration: '22 min',
    views: '6.7k',
    icon: Video,
    color: 'bg-gradient-to-br from-indigo-400 to-indigo-600'
  },
  {
    id: 7,
    title: 'Gratitude Journaling Practice',
    description: 'Learn how to start and maintain a gratitude journal for better mental health.',
    category: 'Articles',
    type: 'article',
    duration: '6 min read',
    views: '2.9k',
    icon: FileText,
    color: 'bg-gradient-to-br from-pink-400 to-pink-600'
  },
  {
    id: 8,
    title: 'Dealing with Grief',
    description: 'Compassionate discussion about processing grief and loss.',
    category: 'Podcasts',
    type: 'audio',
    duration: '38 min',
    views: '1.5k',
    icon: Headphones,
    color: 'bg-gradient-to-br from-gray-400 to-gray-600'
  },
  {
    id: 9,
    title: 'Self-Care Assessment',
    description: 'Evaluate your self-care practices and identify areas for improvement.',
    category: 'Worksheets',
    type: 'pdf',
    duration: '8 pages',
    views: '2.1k',
    icon: FileText,
    color: 'bg-gradient-to-br from-yellow-400 to-yellow-600'
  }
])

const filteredResources = computed(() => {
  if (activeCategory.value === 'All') {
    return resources.value
  }
  return resources.value.filter(r => r.category === activeCategory.value)
})

const getCategoryBadge = (category: string) => {
  const badges: Record<string, string> = {
    'Articles': 'bg-blue-100 text-blue-700',
    'Videos': 'bg-purple-100 text-purple-700',
    'Podcasts': 'bg-green-100 text-green-700',
    'Worksheets': 'bg-orange-100 text-orange-700',
    'Guides': 'bg-teal-100 text-teal-700'
  }
  return badges[category] || 'bg-gray-100 text-gray-700'
}

const getActionIcon = (type: string) => {
  if (type === 'video' || type === 'audio') return Play
  return FileDown
}

const getActionText = (type: string) => {
  if (type === 'video') return 'Watch Video'
  if (type === 'audio') return 'Listen Now'
  if (type === 'pdf') return 'Download'
  return 'Read Article'
}
</script>