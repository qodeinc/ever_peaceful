<template>
  <div class="h-full flex bg-white">
    <!-- Sidebar: Conversations List -->
    <div class="w-80 bg-white border-r border-gray-200 flex flex-col shadow-sm">
      <!-- Header -->
      <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900">Messaging</h2>
        <div class="flex items-center space-x-2">
          <button @click="showNewChat = true" class="p-2 text-gray-400 hover:text-teal-600 rounded-lg transition-colors">
            <Plus class="w-5 h-5" />
          </button>
          <button @click="toggleSearch" class="p-2 text-gray-400 hover:text-teal-600 rounded-lg transition-colors">
            <Search class="w-5 h-5" />
          </button>
        </div>
      </div>

      <!-- Search Bar -->
      <div v-if="showSearch" class="p-4 border-b border-gray-200">
        <input 
          v-model="searchQuery" 
          type="text" 
          placeholder="Search conversations..." 
          class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm"
        />
      </div>

      <!-- Conversations List -->
      <div class="flex-1 overflow-y-auto">
        <div 
          v-for="conversation in filteredConversations" 
          :key="conversation.id"
          class="p-4 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors"
          @click="selectConversation(conversation.id)"
        >
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-full flex items-center justify-center text-white font-medium relative">
              {{ conversation.initials }}
              <div v-if="conversation.online" class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></div>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-gray-900 truncate">{{ conversation.name }}</p>
              <p class="text-xs text-gray-500 truncate">{{ conversation.preview }}</p>
            </div>
            <div v-if="conversation.unread > 0" class="w-5 h-5 bg-teal-600 rounded-full text-white text-xs flex items-center justify-center font-medium">
              {{ conversation.unread > 9 ? '9+' : conversation.unread }}
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Main Chat Area -->
    <div class="flex-1 flex flex-col h-full">
      <div v-if="selectedConversation">
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 bg-white flex items-center justify-between shrink-0">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-full flex items-center justify-center text-white font-medium relative">
              {{ selectedConversation.initials }}
              <div v-if="selectedConversation.online" class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></div>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900">{{ selectedConversation.name }}</h3>
              <p class="text-sm text-gray-500">
                {{ selectedConversation.typing ? 'Typing...' : `Last seen ${selectedConversation.lastSeen}` }}
              </p>
            </div>
          </div>
          <div class="flex items-center space-x-2">
            <button @click="toggleEmojiPicker" class="p-2 text-gray-400 hover:text-teal-600 rounded-lg transition-colors">
              😀
            </button>
            <button @click="attachFile" class="p-2 text-gray-400 hover:text-teal-600 rounded-lg transition-colors">
              <Paperclip class="w-5 h-5" />
            </button>
            <button @click="toggleInfo" class="p-2 text-gray-400 hover:text-teal-600 rounded-lg transition-colors">
              <Info class="w-5 h-5" />
            </button>
          </div>
        </div>
        
        <!-- Messages Area -->
        <div 
          ref="messagesContainer" 
          class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50"
          @scroll="handleScroll"
        >
          <!-- Messages -->
          <div 
            v-for="(message, index) in selectedConversation.messages" 
            :key="message.id"
            :class="['flex', message.isOwn ? 'justify-end' : 'justify-start']"
          >
            <div 
              :class="[
                'max-w-xs lg:max-w-md px-4 py-2 rounded-2xl text-sm relative group',
                message.isOwn 
                  ? 'bg-teal-600 text-white rounded-br-sm' 
                  : 'bg-white text-gray-900 rounded-bl-sm border border-gray-200'
              ]"
            >
              <!-- Message Reactions -->
              <div v-if="message.reactions && message.reactions.length > 0" class="flex gap-1 mb-1">
                <span 
                  v-for="reaction in message.reactions" 
                  :key="reaction.emoji"
                  class="text-xs px-1 py-0.5 bg-white/20 rounded"
                >
                  {{ reaction.emoji }} {{ reaction.count }}
                </span>
              </div>
              
              <p class="break-words">{{ message.text }}</p>
              
              <!-- File Attachment -->
              <div v-if="message.file" class="mt-2 p-2 bg-white/20 rounded-lg">
                <a :href="message.file.url" target="_blank" class="text-xs underline flex items-center gap-1">
                  <FileText class="w-3 h-3" />
                  {{ message.file.name }}
                </a>
              </div>
              
              <!-- Message Time & Status -->
              <div class="flex items-center justify-end gap-1 mt-2 text-xs opacity-75">
                <span>{{ message.time }}</span>
                <div v-if="message.isOwn" class="flex items-center gap-1 ml-1">
                  <span v-if="message.status === 'sent'" class="text-blue-300">✓</span>
                  <span v-if="message.status === 'delivered'" class="text-blue-300">✓✓</span>
                  <span v-if="message.status === 'read'" class="text-blue-300">✓✓</span>
                </div>
              </div>
              
              <!-- Reactions Picker -->
              <div v-if="showReactionPicker === message.id" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 flex gap-1 bg-white rounded-lg p-1 shadow-lg border border-gray-200">
                <button 
                  v-for="emoji in emojis" 
                  :key="emoji" 
                  @click="addReaction(message.id, emoji)"
                  class="text-xl p-1 hover:bg-gray-100 rounded transition-colors"
                >
                  {{ emoji }}
                </button>
              </div>
            </div>
          </div>

          <!-- Typing Indicator -->
          <div v-if="selectedConversation.typing && selectedConversation.messages.length === 0" class="flex justify-start">
            <div class="bg-white px-4 py-2 rounded-2xl text-sm text-gray-500 border border-gray-200 flex items-center gap-2">
              <div class="flex space-x-1">
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
              </div>
              <span>{{ selectedConversation.name }} is typing...</span>
            </div>
          </div>
        </div>
        
        <!-- Input Area -->
        <div class="p-4 border-t border-gray-200 bg-white shrink-0">
          <div class="flex space-x-2">
            <!-- Emoji Picker -->
            <div v-if="showEmojiPicker" class="absolute bottom-16 left-4 bg-white rounded-lg p-2 shadow-lg border border-gray-200 max-h-48 overflow-y-auto z-10">
              <div class="grid grid-cols-8 gap-1">
                <button 
                  v-for="emoji in emojis" 
                  :key="emoji" 
                  @click="addEmoji"
                  class="text-2xl p-1 hover:bg-gray-100 rounded transition-colors"
                >
                  {{ emoji }}
                </button>
              </div>
            </div>

            <input 
              v-model="newMessage" 
              @keyup.enter="sendMessage"
              type="text" 
              placeholder="Type a message..." 
              class="flex-1 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white text-sm"
              :disabled="!socket.connected"
            />
            <button 
              @click="sendMessage"
              :disabled="!newMessage.trim() || !socket.connected"
              class="px-4 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 disabled:bg-gray-400 transition-colors flex items-center gap-2"
            >
              <Send class="w-4 h-4" />
            </button>
          </div>
          <p v-if="!socket.connected" class="text-xs text-gray-500 mt-1 flex items-center gap-1">
            <WifiOff class="w-3 h-3" />
            Reconnecting...
          </p>
        </div>
      </div>
      <div v-else class="flex-1 flex items-center justify-center bg-gray-50">
        <div class="text-center">
          <MessageCircle class="w-16 h-16 text-gray-400 mx-auto mb-4" />
          <h3 class="text-lg font-medium text-gray-900 mb-2">Select a conversation</h3>
          <p class="text-gray-500">Choose a client to start messaging</p>
        </div>
      </div>

      <!-- New Chat Modal -->
      <div v-if="showNewChat" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Start New Chat</h3>
          <select v-model="newChatClient" class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-4">
            <option value="">Select client...</option>
            <option v-for="client in clients" :key="client.id" :value="client.id">
              {{ client.name }}
            </option>
          </select>
          <div class="flex gap-2">
            <button @click="createNewChat" :disabled="!newChatClient" class="flex-1 py-2 px-4 bg-teal-600 text-white rounded-lg hover:bg-teal-700 disabled:bg-gray-400">
              Start Chat
            </button>
            <button @click="showNewChat = false" class="flex-1 py-2 px-4 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, nextTick, computed } from 'vue'
import { Plus, Send, MessageCircle, Search, Paperclip, Info, WifiOff, ChevronDown, FileText, X } from 'lucide-vue-next'
import { io, Socket } from 'socket.io-client'

const socket: Socket = io('http://localhost:3000')

// State
const conversations = ref([
  { 
    id: 1, 
    name: 'Wanjiku Mwangi', 
    initials: 'WM', 
    preview: 'How are you feeling today?', 
    unread: 2,
    online: true,
    lastSeen: 'now',
    typing: false,
    messages: [
      { id: 1, text: 'Hello, how are you feeling today?', time: '2:30 PM', isOwn: false, status: 'read', reactions: [{ emoji: '👍', count: 1 }] },
      { id: 2, text: 'I\'m feeling a bit anxious.', time: '2:32 PM', isOwn: false, status: 'read' },
      { id: 3, text: 'That sounds challenging. Let\'s talk about it.', time: '2:35 PM', isOwn: true, status: 'read' }
    ]
  },
  { 
    id: 2, 
    name: 'Ochieng Kiprop', 
    initials: 'OK', 
    preview: 'Thanks for the resources!', 
    unread: 0,
    online: false,
    lastSeen: '5 min ago',
    typing: false,
    messages: [
      { id: 4, text: 'Thanks for the resources!', time: '1:45 PM', isOwn: false, status: 'delivered' }
    ]
  }
])

const clients = ref([
  { id: 1, name: 'Wanjiku Mwangi' },
  { id: 2, name: 'Ochieng Kiprop' },
  { id: 3, name: 'Muthoni Njoroge' }
])

const selectedConversation = ref(null)
const newMessage = ref('')
const showEmojiPicker = ref(false)
const showReactionPicker = ref(null)
const showNewChat = ref(false)
const newChatClient = ref('')
const searchQuery = ref('')
const showSearch = ref(false)
const messagesContainer = ref(null)

const emojis = ['😀', '😂', '❤️', '👍', '🙌', '🔥', '🎉', '🤔', '😢', '😡']

const filteredConversations = computed(() => {
  if (!searchQuery.value) return conversations.value
  return conversations.value.filter(conv => 
    conv.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    conv.preview.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const selectConversation = (id) => {
  selectedConversation.value = conversations.value.find(c => c.id === id)
  socket.emit('joinRoom', { room: id })
  const conv = conversations.value.find(c => c.id === id)
  if (conv) conv.unread = 0
  showSearch.value = false
  searchQuery.value = ''
  nextTick(() => scrollToBottom())
}

const sendMessage = () => {
  if (!newMessage.value.trim() || !selectedConversation.value || !socket.connected) return
  
  const message = {
    id: Date.now(),
    text: newMessage.value,
    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
    isOwn: true,
    status: 'sent',
    reactions: []
  }
  
  socket.emit('sendMessage', {
    room: selectedConversation.value.id,
    message: { ...message, sender: 'therapist' }
  })
  
  selectedConversation.value.messages.push(message)
  newMessage.value = ''
  nextTick(() => scrollToBottom())
}

const receiveMessage = (data) => {
  const { room, message } = data
  const conv = conversations.value.find(c => c.id === room)
  if (conv) {
    if (selectedConversation.value?.id === room) {
      conv.messages.push({ ...message, isOwn: false, status: 'read' })
      nextTick(() => scrollToBottom())
    } else {
      conv.unread++
      conv.preview = message.text
    }
  }
}

const addReaction = (messageId, emoji) => {
  const message = selectedConversation.value.messages.find(m => m.id === messageId)
  if (message) {
    const reaction = message.reactions.find(r => r.emoji === emoji)
    if (reaction) {
      reaction.count++
    } else {
      message.reactions.push({ emoji, count: 1 })
    }
  }
  showReactionPicker.value = null
}

const addEmoji = (emoji) => {
  newMessage.value += emoji
  showEmojiPicker.value = false
}

const toggleEmojiPicker = () => {
  showEmojiPicker.value = !showEmojiPicker.value
}

const attachFile = () => {
  // File input logic
  console.log('Attach file')
}

const toggleInfo = () => {
  // Chat info modal
  console.log('Toggle info')
}

const createNewChat = () => {
  if (!newChatClient.value) return
  // Create new conversation logic
  console.log(`Starting chat with ${newChatClient.value}`)
  showNewChat.value = false
}

const scrollToBottom = () => {
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
  }
}

const handleScroll = () => {
  // Infinite scroll for loading older messages
}

const toggleSearch = () => {
  showSearch.value = !showSearch.value
}

onMounted(() => {
  socket.on('receiveMessage', receiveMessage)
  socket.on('userTyping', (data) => {
    const conv = conversations.value.find(c => c.id === data.room)
    if (conv) conv.typing = data.typing
  })
  
  socket.on('connect', () => console.log('Connected'))
  socket.on('disconnect', () => console.log('Disconnected'))
  
  if (conversations.value.length > 0) {
    selectConversation(conversations.value[0].id)
  }
})

onUnmounted(() => {
  socket.off('receiveMessage', receiveMessage)
  socket.off('userTyping')
  socket.disconnect()
})
</script>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.message-enter-active {
  animation: fade-in 0.3s ease-out;
}
</style>