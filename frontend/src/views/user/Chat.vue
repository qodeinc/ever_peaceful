<template>
  <div class="h-[calc(100vh-12rem)] flex gap-6">
    <!-- Conversations List -->
    <div class="w-80 bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col">
      <!-- Search -->
      <div class="p-4 border-b border-gray-200">
        <div class="relative">
          <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search conversations..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent"
          />
        </div>
      </div>

      <!-- Conversations -->
      <div class="flex-1 overflow-y-auto">
        <div
          v-for="conversation in filteredConversations"
          :key="conversation.id"
          @click="selectConversation(conversation.id)"
          :class="[
            'p-4 border-b border-gray-200 cursor-pointer transition-colors',
            selectedConversation === conversation.id ? 'bg-teal-50' : 'hover:bg-gray-50'
          ]"
        >
          <div class="flex items-start gap-3">
            <div class="w-12 h-12 rounded-full bg-teal-600 text-white flex items-center justify-center text-sm font-bold flex-shrink-0">
              {{ conversation.avatar }}
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between">
                <h4 class="font-semibold text-gray-900 truncate">{{ conversation.name }}</h4>
                <span class="text-xs text-gray-500 ml-2">{{ conversation.time }}</span>
              </div>
              <p class="text-sm text-gray-600 truncate mt-1">{{ conversation.lastMessage }}</p>
              <span v-if="conversation.unread" class="inline-block mt-1 px-2 py-0.5 bg-teal-600 text-white text-xs rounded-full">
                {{ conversation.unread }} new
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col">
      <div v-if="selectedConversation">
        <!-- Chat Header -->
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-teal-600 text-white flex items-center justify-center text-sm font-bold">
              {{ getCurrentConversation?.avatar }}
            </div>
            <div>
              <h3 class="font-semibold text-gray-900">{{ getCurrentConversation?.name }}</h3>
              <p class="text-sm text-gray-600">{{ getCurrentConversation?.specialty }}</p>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <button class="p-2 text-gray-400 hover:text-teal-600 transition-colors">
              <Phone class="w-5 h-5" />
            </button>
            <button class="p-2 text-gray-400 hover:text-teal-600 transition-colors">
              <Video class="w-5 h-5" />
            </button>
            <button class="p-2 text-gray-400 hover:text-teal-600 transition-colors">
              <MoreVertical class="w-5 h-5" />
            </button>
          </div>
        </div>

        <!-- Messages -->
        <div ref="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-4">
          <div
            v-for="message in messages"
            :key="message.id"
            :class="[
              'flex gap-3',
              message.sender === 'me' ? 'flex-row-reverse' : 'flex-row'
            ]"
          >
            <div
              v-if="message.sender !== 'me'"
              class="w-8 h-8 rounded-full bg-teal-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0"
            >
              {{ getCurrentConversation?.avatar }}
            </div>
            <div :class="['max-w-md', message.sender === 'me' ? 'items-end' : 'items-start']">
              <div
                :class="[
                  'rounded-2xl px-4 py-2',
                  message.sender === 'me'
                    ? 'bg-teal-600 text-white'
                    : 'bg-gray-100 text-gray-900'
                ]"
              >
                <p class="text-sm">{{ message.text }}</p>
              </div>
              <span class="text-xs text-gray-500 mt-1 block">{{ message.time }}</span>
            </div>
          </div>
        </div>

        <!-- Message Input -->
        <div class="p-4 border-t border-gray-200">
          <div class="flex items-end gap-2">
            <button class="p-2 text-gray-400 hover:text-teal-600 transition-colors">
              <Paperclip class="w-5 h-5" />
            </button>
            <div class="flex-1 relative">
              <textarea
                v-model="newMessage"
                @keydown.enter.prevent="sendMessage"
                placeholder="Type your message..."
                rows="1"
                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent resize-none"
              ></textarea>
            </div>
            <button
              @click="sendMessage"
              :disabled="!newMessage.trim()"
              :class="[
                'p-2 rounded-xl transition-colors',
                newMessage.trim()
                  ? 'bg-teal-600 text-white hover:bg-teal-700'
                  : 'bg-gray-200 text-gray-400 cursor-not-allowed'
              ]"
            >
              <Send class="w-5 h-5" />
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="flex-1 flex items-center justify-center">
        <div class="text-center">
          <MessageCircle class="w-16 h-16 text-gray-300 mx-auto mb-4" />
          <h3 class="text-lg font-semibold text-gray-900 mb-2">No conversation selected</h3>
          <p class="text-gray-600">Choose a conversation to start messaging</p>
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
    avatar: 'SC',
    lastMessage: 'Looking forward to our session tomorrow!',
    time: '10:30 AM',
    unread: 2
  },
  {
    id: 2,
    name: 'Dr. Michael Roberts',
    specialty: 'Behavioral Therapist',
    avatar: 'MR',
    lastMessage: 'Great progress on your anxiety management',
    time: 'Yesterday',
    unread: 0
  },
  {
    id: 3,
    name: 'Dr. Emily Davis',
    specialty: 'Marriage Counselor',
    avatar: 'ED',
    lastMessage: 'Remember to practice the communication exercises',
    time: '2 days ago',
    unread: 1
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
  // Mark as read
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