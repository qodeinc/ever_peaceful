<template>
  <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Resources</h2>
    <p class="text-gray-600 mb-6">Access wellness content and resources to support your journey.</p>
    <div class="space-y-4">
      <div v-for="resource in resources" :key="resource.id" 
           class="p-4 bg-gray-50 rounded-xl hover:bg-teal-50 transition-colors">
        <div class="flex items-center space-x-4">
          <div class="w-10 h-10 rounded-full flex items-center justify-center bg-teal-100">
            <component :is="resource.icon" class="w-5 h-5 text-teal-600" />
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-gray-900">{{ resource.title }}</h3>
            <p class="text-sm text-gray-600">{{ resource.description }}</p>
            <a :href="resource.link" target="_blank" class="text-teal-600 hover:text-teal-700 text-sm font-medium">
              {{ resource.type === 'article' ? 'Read Article' : 'Watch Video' }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { BookOpen, Video } from 'lucide-vue-next'

const resources = ref([
  {
    id: 1,
    title: 'Managing Stress in Daily Life',
    description: 'Learn practical techniques to reduce stress and improve mental clarity.',
    type: 'article',
    link: 'https://example.com/stress-management',
    icon: BookOpen
  },
  {
    id: 2,
    title: 'Mindfulness Meditation Guide',
    description: 'A guided video to help you practice mindfulness and relaxation.',
    type: 'video',
    link: 'https://example.com/mindfulness-video',
    icon: Video
  },
  {
    id: 3,
    title: 'Coping with Anxiety',
    description: 'Strategies to manage anxiety and build resilience.',
    type: 'article',
    link: 'https://example.com/anxiety-coping',
    icon: BookOpen
  }
])
</script>

<style scoped>
/* Hover effects */
a {
  position: relative;
  overflow: hidden;
}

a::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s;
}

a:hover::before {
  left: 100%;
}
</style>