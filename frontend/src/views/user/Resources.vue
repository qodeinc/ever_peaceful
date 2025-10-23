<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-7xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-16">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">Resources</h1>
        <p class="text-lg text-gray-500">Curated wellness content and materials</p>
      </div>

      <!-- Category Filter -->
      <div class="mb-12 border-b border-gray-200">
        <div class="flex flex-wrap gap-6 pb-6">
          <button
            v-for="category in categories"
            :key="category"
            @click="activeCategory = category"
            :class="[
              'text-sm font-medium transition-colors pb-2 border-b-2',
              activeCategory === category
                ? 'text-emerald-600 border-emerald-600'
                : 'text-gray-500 border-transparent hover:text-gray-900'
            ]"
          >
            {{ category }}
          </button>
        </div>
      </div>

      <!-- Resources List -->
      <div class="space-y-px border-t border-gray-200">
        <div
          v-for="resource in filteredResources"
          :key="resource.id"
          class="group py-8 border-b border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer"
        >
          <div class="flex items-start gap-8">
            <!-- Icon -->
            <div :class="['w-16 h-16 flex items-center justify-center flex-shrink-0', resource.iconBg]">
              <component :is="resource.icon" :class="['w-8 h-8', resource.iconColor]" />
            </div>
            
            <!-- Content -->
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                  <div class="flex items-center gap-3 mb-2">
                    <h3 class="text-2xl font-light text-gray-900">{{ resource.title }}</h3>
                    <span :class="['text-xs px-2 py-1', getCategoryStyle(resource.category)]">
                      {{ resource.category }}
                    </span>
                  </div>
                  <p class="text-base text-gray-600 leading-relaxed max-w-3xl">{{ resource.description }}</p>
                </div>
              </div>
              
              <div class="flex items-center justify-between mt-4">
                <div class="flex items-center gap-6 text-sm text-gray-500">
                  <div class="flex items-center gap-2">
                    <Clock class="w-4 h-4" />
                    <span>{{ resource.duration }}</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <Eye class="w-4 h-4" />
                    <span>{{ resource.views }} views</span>
                  </div>
                </div>
                <div class="flex items-center gap-4">
                  <button class="text-gray-400 hover:text-emerald-600 transition-colors">
                    <Bookmark class="w-5 h-5" />
                  </button>
                  <button class="flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-medium">
                    <span>{{ getActionText(resource.type) }}</span>
                    <component :is="getActionIcon(resource.type)" class="w-5 h-5" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div
        v-if="filteredResources.length === 0"
        class="text-center py-24"
      >
        <BookOpen class="w-12 h-12 text-gray-300 mx-auto mb-4" />
        <h3 class="text-xl font-light text-gray-900 mb-2">No resources found</h3>
        <p class="text-gray-500 mb-6">Try selecting a different category</p>
        <button 
          @click="activeCategory = 'All'"
          class="px-6 py-3 border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white transition-colors text-sm font-medium"
        >
          View all resources
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { BookOpen, Video, FileText, Headphones, Clock, Eye, Bookmark, Play, FileDown, ChevronRight } from 'lucide-vue-next'

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
    description: 'Learn practical techniques to manage anxiety and stress in your everyday routine. Evidence-based strategies for immediate relief.',
    category: 'Articles',
    type: 'article',
    duration: '8 min read',
    views: '2.3k',
    icon: FileText,
    iconBg: 'bg-blue-50',
    iconColor: 'text-blue-600'
  },
  {
    id: 2,
    title: 'Mindfulness Meditation Guide',
    description: 'A comprehensive video guide to mindfulness meditation for beginners. Step-by-step instructions for daily practice.',
    category: 'Videos',
    type: 'video',
    duration: '15 min',
    views: '5.1k',
    icon: Video,
    iconBg: 'bg-purple-50',
    iconColor: 'text-purple-600'
  },
  {
    id: 3,
    title: 'Sleep Better Tonight',
    description: 'Podcast episode discussing sleep hygiene and tips for better rest. Expert advice for improving sleep quality.',
    category: 'Podcasts',
    type: 'audio',
    duration: '42 min',
    views: '1.8k',
    icon: Headphones,
    iconBg: 'bg-green-50',
    iconColor: 'text-green-600'
  },
  {
    id: 4,
    title: 'Cognitive Distortions Worksheet',
    description: 'Identify and challenge negative thought patterns with this interactive worksheet. Tools for reframing thoughts.',
    category: 'Worksheets',
    type: 'pdf',
    duration: '12 pages',
    views: '3.2k',
    icon: FileText,
    iconBg: 'bg-orange-50',
    iconColor: 'text-orange-600'
  },
  {
    id: 5,
    title: 'Building Emotional Resilience',
    description: 'Step-by-step guide to developing emotional strength and resilience. Proven techniques for lasting change.',
    category: 'Guides',
    type: 'pdf',
    duration: '25 pages',
    views: '4.5k',
    icon: BookOpen,
    iconBg: 'bg-emerald-50',
    iconColor: 'text-emerald-600'
  },
  {
    id: 6,
    title: 'Understanding Depression',
    description: 'Educational video about depression symptoms, causes, and treatments. Comprehensive overview for patients and families.',
    category: 'Videos',
    type: 'video',
    duration: '22 min',
    views: '6.7k',
    icon: Video,
    iconBg: 'bg-indigo-50',
    iconColor: 'text-indigo-600'
  },
  {
    id: 7,
    title: 'Gratitude Journaling Practice',
    description: 'Learn how to start and maintain a gratitude journal for better mental health. Transform your mindset daily.',
    category: 'Articles',
    type: 'article',
    duration: '6 min read',
    views: '2.9k',
    icon: FileText,
    iconBg: 'bg-pink-50',
    iconColor: 'text-pink-600'
  },
  {
    id: 8,
    title: 'Dealing with Grief',
    description: 'Compassionate discussion about processing grief and loss. Support for navigating difficult emotions.',
    category: 'Podcasts',
    type: 'audio',
    duration: '38 min',
    views: '1.5k',
    icon: Headphones,
    iconBg: 'bg-gray-50',
    iconColor: 'text-gray-600'
  },
  {
    id: 9,
    title: 'Self-Care Assessment',
    description: 'Evaluate your self-care practices and identify areas for improvement. Comprehensive personal wellness audit.',
    category: 'Worksheets',
    type: 'pdf',
    duration: '8 pages',
    views: '2.1k',
    icon: FileText,
    iconBg: 'bg-yellow-50',
    iconColor: 'text-yellow-600'
  },
  {
    id: 10,
    title: 'Stress Management Techniques',
    description: 'Quick and effective methods to reduce stress in high-pressure situations. Practical tools for daily use.',
    category: 'Articles',
    type: 'article',
    duration: '10 min read',
    views: '3.8k',
    icon: FileText,
    iconBg: 'bg-red-50',
    iconColor: 'text-red-600'
  },
  {
    id: 11,
    title: 'Introduction to CBT',
    description: 'Video series introducing cognitive behavioral therapy principles. Professional guidance for self-help.',
    category: 'Videos',
    type: 'video',
    duration: '18 min',
    views: '4.2k',
    icon: Video,
    iconBg: 'bg-cyan-50',
    iconColor: 'text-cyan-600'
  },
  {
    id: 12,
    title: 'Relationship Communication',
    description: 'Podcast exploring healthy communication patterns in relationships. Expert insights and real-world examples.',
    category: 'Podcasts',
    type: 'audio',
    duration: '45 min',
    views: '2.7k',
    icon: Headphones,
    iconBg: 'bg-rose-50',
    iconColor: 'text-rose-600'
  }
])

const filteredResources = computed(() => {
  if (activeCategory.value === 'All') {
    return resources.value
  }
  return resources.value.filter(r => r.category === activeCategory.value)
})

const getCategoryStyle = (category: string) => {
  const styles: Record<string, string> = {
    'Articles': 'bg-blue-50 text-blue-700',
    'Videos': 'bg-purple-50 text-purple-700',
    'Podcasts': 'bg-green-50 text-green-700',
    'Worksheets': 'bg-orange-50 text-orange-700',
    'Guides': 'bg-emerald-50 text-emerald-700'
  }
  return styles[category] || 'bg-gray-50 text-gray-700'
}

const getActionIcon = (type: string) => {
  if (type === 'video' || type === 'audio') return Play
  if (type === 'pdf') return FileDown
  return ChevronRight
}

const getActionText = (type: string) => {
  if (type === 'video') return 'Watch'
  if (type === 'audio') return 'Listen'
  if (type === 'pdf') return 'Download'
  return 'Read'
}
</script>

<style scoped>
/* Smooth transitions */
button, a {
  transition: all 0.2s ease;
}
</style>