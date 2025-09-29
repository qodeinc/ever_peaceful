<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-3xl font-bold text-gray-900">Session in Progress</h2>
        <p class="text-gray-600 mt-1">{{ selectedSession.client }} • {{ selectedSession.type }}</p>
      </div>
      <div class="flex gap-3">
        <button class="px-5 py-3 bg-white rounded-2xl border-2 border-gray-200 hover:border-red-500 hover:bg-red-50 transition-all font-medium text-gray-700 hover:text-red-700">
          Pause Session
        </button>
        <button class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-2xl hover:shadow-lg transition-all flex items-center gap-2 font-medium">
          <X class="w-5 h-5" />
          End Session
        </button>
      </div>
    </div>

    <div class="grid grid-cols-3 gap-6">
      <div class="col-span-2 space-y-6">
        <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-gray-900">Session Timer</h3>
            <div class="flex items-center gap-4">
              <Clock class="w-6 h-6 text-emerald-600" />
              <span class="text-4xl font-bold text-gray-900">{{ sessionTimer }}</span>
            </div>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-4">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-4 rounded-full transition-all" :style="`width: ${timerProgress}%`"></div>
          </div>
          <p class="text-center text-sm text-gray-600 mt-2">{{ timeRemaining }} remaining</p>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Notes</h3>
          <textarea
            v-model="quickNotes"
            rows="8"
            placeholder="Take quick notes during the session..."
            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none resize-none"
          ></textarea>
          <p class="text-xs text-gray-500 mt-2">Notes are auto-saved</p>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">In-Session Tools</h3>
          <div class="grid grid-cols-3 gap-3">
            <button 
              v-for="tool in sessionTools"
              :key="tool.name"
              class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl border-2 border-emerald-100 hover:border-emerald-400 hover:shadow-md transition-all"
            >
              <component :is="tool.icon" class="w-8 h-8 text-emerald-600 mx-auto mb-2" />
              <p class="text-sm font-semibold text-gray-900 text-center">{{ tool.name }}</p>
            </button>
          </div>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Share Resources</h3>
          <div class="space-y-3">
            <div 
              v-for="resource in availableResources"
              :key="resource.id"
              class="flex items-center justify-between p-4 bg-gradient-to-r from-emerald-50/30 to-teal-50/30 rounded-xl border border-emerald-100 hover:border-emerald-300 transition-all"
            >
              <div class="flex items-center gap-3">
                <FileText class="w-5 h-5 text-emerald-600" />
                <div>
                  <p class="font-semibold text-gray-900">{{ resource.name }}</p>
                  <p class="text-xs text-gray-600">{{ resource.type }}</p>
                </div>
              </div>
              <button class="px-4 py-2 bg-white rounded-xl hover:bg-emerald-50 transition-colors flex items-center gap-2 text-sm font-medium border border-emerald-200">
                <Share2 class="w-4 h-4" />
                Share
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="space-y-6">
        <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
          <h3 class="font-bold text-gray-900 mb-4">Client Summary</h3>
          <div class="flex items-center gap-3 mb-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-xl">
              {{ selectedSession.initials }}
            </div>
            <div>
              <p class="font-bold text-gray-900">{{ selectedSession.client }}</p>
              <p class="text-sm text-gray-600">{{ selectedSession.condition }}</p>
            </div>
          </div>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="text-gray-600">Session #</span>
              <span class="font-semibold">{{ selectedSession.sessionNumber }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Last Session</span>
              <span class="font-semibold">{{ selectedSession.lastSession }}</span>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
          <h3 class="font-bold text-gray-900 mb-4">Previous Session Notes</h3>
          <div class="p-4 bg-gradient-to-r from-emerald-50/50 to-teal-50/50 rounded-xl border border-emerald-100">
            <p class="text-sm text-gray-700 leading-relaxed">{{ selectedSession.previousNotes }}</p>
          </div>
          <button class="w-full mt-3 px-4 py-2 bg-white border-2 border-emerald-200 text-emerald-700 rounded-xl hover:bg-emerald-50 transition-all font-medium text-sm">
            View All Notes
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Clock, X, FileText, Share2, Clipboard, Activity, Brain, Heart, Target } from 'lucide-vue-next';

const quickNotes = ref('');
const sessionTime = ref(0);
let timerInterval: number | null = null;

const selectedSession = ref({
  client: 'Sarah Johnson',
  type: 'Individual Therapy',
  initials: 'SJ',
  condition: 'Anxiety & Depression',
  sessionNumber: 12,
  lastSession: '1 week ago',
  previousNotes: 'Client showed significant improvement in managing anxiety triggers. Discussed coping strategies and mindfulness techniques. Homework assigned: daily journaling.',
  duration: 60 // minutes
});

const sessionTools = ref([
  { name: 'Assessments', icon: Clipboard },
  { name: 'Exercises', icon: Activity },
  { name: 'Mindfulness', icon: Brain },
  { name: 'Mood Tracker', icon: Heart },
  { name: 'Goals', icon: Target },
  { name: 'Resources', icon: FileText }
]);

const availableResources = ref([
  { id: 1, name: 'Anxiety Management Guide', type: 'PDF Document' },
  { id: 2, name: 'Breathing Exercises', type: 'Video' },
  { id: 3, name: 'Cognitive Restructuring Worksheet', type: 'Worksheet' },
  { id: 4, name: 'Sleep Hygiene Tips', type: 'PDF Document' }
]);

const formatTime = (seconds: number): string => {
  const mins = Math.floor(seconds / 60);
  const secs = seconds % 60;
  return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
};

const sessionTimer = computed(() => formatTime(sessionTime.value));

const timerProgress = computed(() => {
  const progress = (sessionTime.value / (selectedSession.value.duration * 60)) * 100;
  return Math.min(progress, 100);
});

const timeRemaining = computed(() => {
  const remaining = (selectedSession.value.duration * 60) - sessionTime.value;
  return formatTime(Math.max(remaining, 0));
});

onMounted(() => {
  timerInterval = window.setInterval(() => {
    sessionTime.value += 1;
  }, 1000);
});

onUnmounted(() => {
  if (timerInterval) {
    clearInterval(timerInterval);
  }
});
</script>