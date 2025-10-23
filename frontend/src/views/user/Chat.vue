<template>
  <div class="h-screen bg-white flex flex-col">
    <div class="max-w-7xl mx-auto px-6 py-8 w-full">
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-5xl font-light text-gray-900 mb-2 tracking-tight">Messages</h1>
        <p class="text-lg text-gray-500">Secure communication with your therapist</p>
      </div>
    </div>
    <!-- Chat Interface - Fixed Height -->
    <div class="flex-1 max-w-7xl mx-auto px-6 pb-6 w-full overflow-hidden">
      <div class="flex gap-0 border border-gray-200 h-full">
        <!-- Conversations List -->
        <div class="w-80 border-r border-gray-200 flex flex-col h-full">
          <!-- Search -->
          <div class="p-6 border-b border-gray-200 flex-shrink-0">
            <div class="relative">
              <Search class="absolute left-0 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search"
                class="w-full pl-8 pr-4 py-2 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 bg-transparent transition-colors"
              />
            </div>
          </div>
          <!-- Conversations - Scrollable -->
          <div class="flex-1 overflow-y-auto">
            <div
              v-for="conversation in filteredConversations"
              :key="conversation.id"
              @click="selectConversation(conversation.id)"
              :class="[
                'p-6 border-b border-gray-100 cursor-pointer transition-all',
                selectedConversation === conversation.id 
                  ? 'bg-emerald-50 border-l-4 border-l-emerald-600' 
                  : 'hover:bg-gray-50 border-l-4 border-l-transparent'
              ]"
            >
              <div class="flex items-start gap-4">
                <img 
                  :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${conversation.name}`"
                  :alt="conversation.name"
                  class="w-12 h-12 rounded-full flex-shrink-0"
                />
                <div class="flex-1 min-w-0">
                  <div class="flex items-baseline justify-between mb-1">
                    <h4 class="font-medium text-gray-900 truncate">{{ conversation.name }}</h4>
                    <span class="text-xs text-gray-400 ml-2">{{ conversation.time }}</span>
                  </div>
                  <p class="text-sm text-gray-500 mb-1">{{ conversation.specialty }}</p>
                  <p class="text-sm text-gray-600 truncate">{{ conversation.lastMessage }}</p>
                  <div v-if="conversation.unread" class="mt-2 inline-block w-2 h-2 bg-emerald-600 rounded-full"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Chat Area -->
        <div class="flex-1 flex flex-col h-full">
          <div v-if="selectedConversation" class="flex flex-col h-full">
            <!-- Chat Header -->
            <div class="p-6 border-b border-gray-200 flex-shrink-0">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                  <img 
                    :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${getCurrentConversation?.name}`"
                    :alt="getCurrentConversation?.name"
                    class="w-12 h-12 rounded-full"
                  />
                  <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ getCurrentConversation?.name }}</h3>
                    <p class="text-sm text-gray-500">{{ getCurrentConversation?.specialty }}</p>
                  </div>
                </div>
                <div class="flex items-center gap-4">
                  <button class="text-gray-400 hover:text-gray-900 transition-colors">
                    <Phone class="w-5 h-5" />
                  </button>
                  <button class="text-gray-400 hover:text-gray-900 transition-colors">
                    <Video class="w-5 h-5" />
                  </button>
                  <button class="text-gray-400 hover:text-gray-900 transition-colors">
                    <MoreVertical class="w-5 h-5" />
                  </button>
                </div>
              </div>
            </div>
            <!-- Messages - Scrollable -->
            <div ref="messagesContainer" class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50">
              <div
                v-for="message in messages"
                :key="message.id"
                :class="[
                  'flex gap-4',
                  message.sender === 'me' ? 'flex-row-reverse' : 'flex-row'
                ]"
              >
                <img
                  v-if="message.sender !== 'me'"
                  :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${getCurrentConversation?.name}`"
                  :alt="getCurrentConversation?.name"
                  class="w-10 h-10 rounded-full flex-shrink-0"
                />
                <div :class="['max-w-lg', message.sender === 'me' ? 'items-end' : 'items-start']">
                  <div
                    :class="[
                      'px-4 py-3',
                      message.sender === 'me'
                        ? 'bg-emerald-600 text-white'
                        : 'bg-white text-gray-900 border border-gray-200'
                    ]"
                  >
                    <p class="text-sm leading-relaxed">{{ message.text }}</p>
                  </div>
                  <span class="text-xs text-gray-400 mt-2 block">{{ message.time }}</span>
                </div>
              </div>
            </div>
            <!-- Message Input -->
            <div class="p-6 border-t border-gray-200 bg-white flex-shrink-0">
              <div class="flex items-end gap-3">
                <button class="p-2 text-gray-400 hover:text-gray-900 transition-colors">
                  <Paperclip class="w-5 h-5" />
                </button>
                <div class="flex-1">
                  <textarea
                    v-model="newMessage"
                    @keydown.enter.prevent="sendMessage"
                    placeholder="Type a message"
                    rows="1"
                    class="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 resize-none bg-transparent transition-colors"
                  ></textarea>
                </div>
                <button
                  @click="sendMessage"
                  :disabled="!newMessage.trim()"
                  :class="[
                    'p-3 transition-colors',
                    newMessage.trim()
                      ? 'bg-emerald-600 text-white hover:bg-emerald-700'
                      : 'bg-gray-100 text-gray-300 cursor-not-allowed'
                  ]"
                >
                  <Send class="w-5 h-5" />
                </button>
              </div>
            </div>
          </div>
          <!-- Empty State -->
          <div v-else class="flex-1 flex items-center justify-center bg-gray-50">
            <div class="text-center">
              <MessageCircle class="w-16 h-16 text-gray-300 mx-auto mb-4" />
              <h3 class="text-xl font-light text-gray-900 mb-2">Select a conversation</h3>
              <p class="text-gray-500">Choose a therapist to start messaging</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, nextTick, watch } from 'vue'
import { Search, Phone, Video, MoreVertical, MessageCircle, Paperclip, Send } from 'lucide-vue-next'

const searchQuery = ref('')
const selectedConversation = ref(1)
const newMessage = ref('')
const messagesContainer = ref<HTMLElement | null>(null)

const conversations = ref([
  {
    id: 1,
    name: 'Dr. Sarah Chen',
    specialty: 'Clinical Psychologist',
    lastMessage: 'Looking forward to our session tomorrow!',
    time: '10:30 AM',
    unread: 2
  },
  {
    id: 2,
    name: 'Dr. Michael Roberts',
    specialty: 'Behavioral Therapist',
    lastMessage: 'Great progress on your anxiety management',
    time: 'Yesterday',
    unread: 0
  },
  {
    id: 3,
    name: 'Dr. Emily Davis',
    specialty: 'Marriage Counselor',
    lastMessage: 'Remember to practice the communication exercises',
    time: '2 days ago',
    unread: 1
  },
  {
    id: 4,
    name: 'Dr. James Wilson',
    specialty: 'Trauma Specialist',
    lastMessage: 'How are you feeling after our last session?',
    time: '3 days ago',
    unread: 0
  },
  {
    id: 5,
    name: 'Dr. Lisa Anderson',
    specialty: 'Family Therapist',
    lastMessage: 'The family meeting went really well',
    time: 'Last week',
    unread: 0
  }
])

const messages = ref([
  {
    id: 1,
    sender: 'therapist',
    text: 'Hi! How have you been feeling this week?',
    time: '10:15 AM'
  },
  {
    id: 2,
    sender: 'me',
    text: 'Hi Dr. Chen! I\'ve been doing better. The breathing exercises are really helping.',
    time: '10:18 AM'
  },
  {
    id: 3,
    sender: 'therapist',
    text: 'That\'s wonderful to hear! Have you been practicing them daily as we discussed?',
    time: '10:20 AM'
  },
  {
    id: 4,
    sender: 'me',
    text: 'Yes, every morning and whenever I feel anxious. It\'s made a big difference.',
    time: '10:22 AM'
  },
  {
    id: 5,
    sender: 'therapist',
    text: 'Excellent! Keep up the great work. Looking forward to our session tomorrow!',
    time: '10:30 AM'
  }
])

const filteredConversations = computed(() => {
  if (!searchQuery.value) return conversations.value
  return conversations.value.filter(c =>
    c.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const getCurrentConversation = computed(() => {
  return conversations.value.find(c => c.id === selectedConversation.value)
})

const selectConversation = (id: number) => {
  selectedConversation.value = id
  const conv = conversations.value.find(c => c.id === id)
  if (conv) conv.unread = 0
}

const sendMessage = () => {
  if (!newMessage.value.trim()) return
  
  messages.value.push({
    id: messages.value.length + 1,
    sender: 'me',
    text: newMessage.value,
    time: new Date().toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })
  })
  
  newMessage.value = ''
  
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
    }
  })
}

watch(selectedConversation, () => {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
    }
  })
})
</script>

<style scoped>
/* Custom scrollbar for messages */
.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}
.overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}
.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 3px;
}
.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}
/* Smooth transitions */
button, input, textarea {
  transition: all 0.2s ease;
}
</style>