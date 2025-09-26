<template>
  <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Chat</h2>
    <p class="text-gray-600 mb-6">Message your therapist.</p>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Therapist List -->
      <div class="lg:col-span-1 bg-gray-50 rounded-xl p-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Therapists</h3>
        <div v-for="therapist in therapists" :key="therapist.id"
             @click="selectTherapist(therapist.id)"
             class="flex items-center space-x-3 p-3 rounded-xl hover:bg-teal-50 cursor-pointer transition-colors"
             :class="selectedTherapistId === therapist.id ? 'bg-teal-100' : ''">
          <img :src="therapist.avatar" :alt="therapist.name" class="w-10 h-10 rounded-full border-2 border-teal-200">
          <div>
            <p class="font-medium text-gray-900">{{ therapist.name }}</p>
            <p class="text-xs text-gray-600">{{ therapist.lastMessage }}</p>
          </div>
          <span v-if="therapist.unreadCount" class="ml-auto px-2 py-1 text-xs rounded-full bg-red-100 text-red-600">
            {{ therapist.unreadCount }}
          </span>
        </div>
      </div>

      <!-- Chat Area -->
      <div class="lg:col-span-2 bg-gray-50 rounded-xl p-4">
        <div v-if="selectedTherapistId" class="h-[400px] overflow-y-auto mb-4">
          <div v-for="message in messages" :key="message.id" 
               class="mb-4 flex"
               :class="message.isSent ? 'justify-end' : 'justify-start'">
            <div class="max-w-[70%] p-3 rounded-xl"
                 :class="message.isSent ? 'bg-teal-600 text-white' : 'bg-white border border-gray-200'">
              <p class="text-sm">{{ message.text }}</p>
              <p class="text-xs text-gray-500 mt-1">{{ formatTime(message.time) }}</p>
            </div>
          </div>
        </div>
        <div v-else class="text-center text-gray-500 py-8">
          Select a therapist to start chatting
        </div>
        <div v-if="selectedTherapistId" class="flex space-x-2">
          <input 
            v-model="newMessage" 
            placeholder="Type your message..." 
            class="flex-1 p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
          />
          <button 
            @click="sendMessage" 
            :disabled="!newMessage.trim()"
            class="bg-teal-600 text-white py-2 px-4 rounded-xl hover:bg-teal-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
          >
            Send
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { MessageCircle } from 'lucide-vue-next'

const selectedTherapistId = ref<number | null>(null)
const newMessage = ref('')

// Mock therapists
const therapists = ref([
  { id: 1, name: 'Dr. Emily Chen', avatar: '/api/placeholder/40/40', lastMessage: 'Looking forward to our session!', unreadCount: 2 },
  { id: 2, name: 'Dr. Michael Rodriguez', avatar: '/api/placeholder/40/40', lastMessage: 'Please review the group session notes.', unreadCount: 0 },
  { id: 3, name: 'Dr. Aisha Mwangi', avatar: '/api/placeholder/40/40', lastMessage: 'How are you feeling today?', unreadCount: 1 }
])

// Mock messages
const messages = ref([
  { id: 1, text: 'Hi, how are you feeling today?', isSent: false, time: new Date('2025-09-26T14:00:00') },
  { id: 2, text: 'I’m feeling a bit anxious but managing.', isSent: true, time: new Date('2025-09-26T14:05:00') },
  { id: 3, text: 'Let’s discuss some coping strategies in our next session.', isSent: false, time: new Date('2025-09-26T14:10:00') }
])

const selectTherapist = (id: number) => {
  selectedTherapistId.value = id
}

const sendMessage = () => {
  if (!newMessage.value.trim()) return
  messages.value.push({
    id: messages.value.length + 1,
    text: newMessage.value,
    isSent: true,
    time: new Date()
  })
  newMessage.value = ''
}

const formatTime = (date: Date) => {
  return date.toLocaleTimeString('en-US', { 
    hour: 'numeric', 
    minute: '2-digit',
    hour12: true,
    timeZone: 'Africa/Nairobi'
  })
}
</script>

<style scoped>
/* Custom scrollbar for chat */
::-webkit-scrollbar {
  width: 4px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #0d9488;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #0f766e;
}
</style>