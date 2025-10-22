<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-3xl font-bold text-gray-900">Tasks & Reminders</h1>
      <button 
        @click="addNewTask"
        class="px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors flex items-center space-x-2"
      >
        <Plus class="w-4 h-4" />
        <span>Add New Task</span>
      </button>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
      <div class="flex flex-col sm:flex-row gap-4 items-center">
        <div class="relative flex-1">
          <input 
            v-model="searchQuery"
            type="text" 
            placeholder="Search tasks..." 
            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
          />
          <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
        </div>
        <div class="flex space-x-2">
          <button 
            @click="setFilter('all')"
            :class="[
              'px-3 py-1 rounded-lg text-sm font-medium transition-colors',
              activeFilter === 'all' ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:text-emerald-600'
            ]"
          >
            All
          </button>
          <button 
            @click="setFilter('pending')"
            :class="[
              'px-3 py-1 rounded-lg text-sm font-medium transition-colors',
              activeFilter === 'pending' ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:text-emerald-600'
            ]"
          >
            Pending
          </button>
          <button 
            @click="setFilter('completed')"
            :class="[
              'px-3 py-1 rounded-lg text-sm font-medium transition-colors',
              activeFilter === 'completed' ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:text-emerald-600'
            ]"
          >
            Completed
          </button>
        </div>
      </div>
    </div>

    <!-- Tasks Grid as Sticky Notes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <div 
        v-for="task in filteredTasks" 
        :key="task.id"
        class="group relative"
      >
        <!-- Sticky Note Background -->
        <div 
          :class="[
            'relative p-6 rounded-2xl shadow-md border border-gray-300 transform transition-all duration-300 group-hover:shadow-lg group-hover:-translate-y-1 cursor-pointer',
            task.status === 'completed' 
              ? 'bg-gray-100 opacity-60' 
              : task.priority === 'high' 
                ? 'bg-red-50 border-red-200' 
                : task.priority === 'medium' 
                  ? 'bg-yellow-50 border-yellow-200' 
                  : 'bg-yellow-50 border-yellow-200'
          ]"
          @click="toggleTaskStatus(task.id)"
        >
          <!-- Curled Corner Effect -->
          <div class="absolute -top-2 -right-2 w-8 h-8 bg-white rounded-br-lg shadow-lg rotate-12 opacity-80"></div>
          
          <!-- Task Content -->
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-3">
              <h3 class="font-bold text-lg text-gray-800 truncate">{{ task.title }}</h3>
              <span 
                class="px-2 py-1 rounded-full text-xs font-semibold"
                :class="[
                  task.status === 'completed' ? 'bg-green-100 text-green-800' : 
                  task.priority === 'high' ? 'bg-red-100 text-red-800' : 
                  task.priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 
                  'bg-blue-100 text-blue-800'
                ]"
              >
                {{ task.status === 'completed' ? 'Done' : task.priority }}
              </span>
            </div>
            <p class="text-sm text-gray-700 mb-4 leading-relaxed">{{ task.description }}</p>
            
            <!-- Due Date -->
            <div class="flex items-center justify-between text-xs text-gray-600 mb-3">
              <span class="flex items-center gap-1">
                <Clock class="w-3 h-3" />
                {{ task.due }}
              </span>
              <span v-if="task.client" class="flex items-center gap-1">
                <Users class="w-3 h-3" />
                {{ task.client }}
              </span>
            </div>
            
            <!-- Progress Bar for Incomplete Tasks -->
            <div v-if="task.status !== 'completed'" class="w-full bg-gray-200 rounded-full h-1.5 mb-3">
              <div 
                class="bg-emerald-500 h-1.5 rounded-full transition-all duration-300"
                :style="`width: ${task.progress}%`"
              ></div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              <button 
                @click.stop="editTask(task.id)"
                class="p-1 text-gray-500 hover:text-gray-700 rounded"
                title="Edit"
              >
                <Edit class="w-3 h-3" />
              </button>
              <button 
                @click.stop="deleteTask(task.id)"
                class="p-1 text-red-500 hover:text-red-700 rounded"
                title="Delete"
              >
                <Trash2 class="w-3 h-3" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- No Tasks Message -->
    <div v-if="filteredTasks.length === 0" class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-200">
      <ClipboardList class="w-16 h-16 text-gray-400 mx-auto mb-4" />
      <h3 class="text-lg font-medium text-gray-900 mb-2">No tasks found</h3>
      <p class="text-gray-500 mb-4">Your to-do list is clear! Time to celebrate or add something new.</p>
      <button 
        @click="addNewTask"
        class="px-6 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors"
      >
        Add Your First Task
      </button>
    </div>

    <!-- Add Task Modal (Hidden by default) -->
    <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-2xl p-6 max-w-md w-full max-h-[80vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-gray-900">New Task</h3>
          <button @click="closeAddModal" class="text-gray-400 hover:text-gray-600">
            <X class="w-5 h-5" />
          </button>
        </div>
        <form @submit.prevent="createTask">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
              <input 
                v-model="newTask.title" 
                type="text" 
                placeholder="Task title..."
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
              <textarea 
                v-model="newTask.description" 
                placeholder="Task details..."
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
              ></textarea>
            </div>
            <div class="grid grid-cols-2 gap-2">
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Priority</label>
                <select 
                  v-model="newTask.priority" 
                  class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >
                  <option value="low">Low</option>
                  <option value="medium">Medium</option>
                  <option value="high">High</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Due Date</label>
                <input 
                  v-model="newTask.due" 
                  type="date" 
                  class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Client (Optional)</label>
              <select 
                v-model="newTask.client" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
              >
                <option value="">No client</option>
                <option v-for="client in clients" :key="client.id" :value="client.name">
                  {{ client.name }}
                </option>
              </select>
            </div>
            <div class="flex gap-2 pt-2">
              <button 
                type="submit"
                class="flex-1 py-2 px-4 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-medium"
              >
                Create Task
              </button>
              <button 
                type="button"
                @click="closeAddModal"
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
import { Plus, Search, Clock, Users, ClipboardList, Edit, Trash2, X, FileText, Save } from 'lucide-vue-next'

const tasks = ref([
  { 
    id: 1, 
    title: 'Follow-up call with Wanjiku', 
    description: 'Discuss progress on anxiety management techniques and review homework.', 
    priority: 'medium', 
    status: 'pending', 
    due: 'Oct 2, 2025', 
    client: 'Wanjiku Mwangi', 
    progress: 60 
  },
  { 
    id: 2, 
    title: 'Review Ochieng\'s session notes', 
    description: 'Update progress notes and plan next session interventions.', 
    priority: 'high', 
    status: 'pending', 
    due: 'Oct 1, 2025', 
    client: 'Ochieng Kiprop', 
    progress: 20 
  },
  { 
    id: 3, 
    title: 'Assign resources to Muthoni', 
    description: 'Send mindfulness exercises and breathing techniques PDF.', 
    priority: 'low', 
    status: 'completed', 
    due: 'Sep 30, 2025', 
    client: 'Muthoni Njoroge', 
    progress: 100 
  },
  { 
    id: 4, 
    title: 'Prepare for Achieng\'s family session', 
    description: 'Review family dynamics notes and prepare discussion points.', 
    priority: 'medium', 
    status: 'pending', 
    due: 'Oct 5, 2025', 
    client: 'Achieng Omondi', 
    progress: 0 
  }
])

const clients = ref([
  { id: 1, name: 'Wanjiku Mwangi' },
  { id: 2, name: 'Ochieng Kiprop' },
  { id: 3, name: 'Muthoni Njoroge' },
  { id: 4, name: 'Achieng Omondi' }
])

const searchQuery = ref('')
const activeFilter = ref('all')
const showAddModal = ref(false)

const newTask = ref({
  title: '',
  description: '',
  priority: 'medium',
  due: '',
  client: ''
})

const filteredTasks = computed(() => {
  let filtered = tasks.value
  
  if (searchQuery.value) {
    filtered = filtered.filter(task => 
      task.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      task.description.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      (task.client && task.client.toLowerCase().includes(searchQuery.value.toLowerCase()))
    )
  }
  
  if (activeFilter.value !== 'all') {
    filtered = filtered.filter(task => task.status === activeFilter.value)
  }
  
  return filtered
})

const setFilter = (filter) => {
  activeFilter.value = filter
}

const toggleTaskStatus = (id) => {
  const task = tasks.value.find(t => t.id === id)
  if (task) {
    task.status = task.status === 'completed' ? 'pending' : 'completed'
    if (task.status === 'completed') {
      task.progress = 100
    }
  }
}

const editTask = (id) => {
  console.log(`Edit task ${id}`)
  // Open edit modal in real app
}

const deleteTask = (id) => {
  if (confirm('Delete this task?')) {
    tasks.value = tasks.value.filter(t => t.id !== id)
  }
}

const addNewTask = () => {
  showAddModal.value = true
  newTask.value = { title: '', description: '', priority: 'medium', due: '', client: '' }
}

const closeAddModal = () => {
  showAddModal.value = false
}

const createTask = () => {
  if (!newTask.value.title.trim()) return
  tasks.value.push({
    id: Date.now(),
    ...newTask.value,
    status: 'pending',
    progress: 0
  })
  closeAddModal()
}
</script>

<style scoped>
</style>