<!-- src/views/admin/Users.vue -->
<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
      <button class="px-4 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors flex items-center gap-2">
        <Plus class="w-4 h-4" />
        Add User
      </button>
    </div>
    
    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200">
      <div class="flex flex-col sm:flex-row gap-4 items-center">
        <div class="relative flex-1">
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Search users..." 
            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
          />
          <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
        </div>
        <div class="flex space-x-2">
          <button 
            @click="setUserFilter('all')"
            :class="[
              'px-3 py-1 rounded-lg text-sm font-medium transition-colors',
              userFilter === 'all' ? 'bg-teal-600 text-white' : 'text-gray-600 hover:text-teal-600'
            ]"
          >
            All
          </button>
          <button 
            @click="setUserFilter('active')"
            :class="[
              'px-3 py-1 rounded-lg text-sm font-medium transition-colors',
              userFilter === 'active' ? 'bg-teal-600 text-white' : 'text-gray-600 hover:text-teal-600'
            ]"
          >
            Active
          </button>
          <button 
            @click="setUserFilter('suspended')"
            :class="[
              'px-3 py-1 rounded-lg text-sm font-medium transition-colors',
              userFilter === 'suspended' ? 'bg-teal-600 text-white' : 'text-gray-600 hover:text-teal-600'
            ]"
          >
            Suspended
          </button>
        </div>
      </div>
    </div>
    
    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Active</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in paginatedUsers" :key="user.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="w-10 h-10 bg-teal-600 rounded-full flex items-center justify-center text-white font-medium text-sm flex-shrink-0">
                    {{ user.initials }}
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                    <div class="text-sm text-gray-500">{{ user.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                      :class="user.role === 'therapist' ? 'bg-teal-100 text-teal-800' : 'bg-gray-100 text-gray-800'">
                  {{ user.role }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                      :class="user.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                  {{ user.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ user.joined }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ user.lastActive }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                <button @click.stop="viewUser(user.id)" class="text-teal-600 hover:text-teal-900">
                  <Eye class="w-4 h-4" />
                </button>
                <button @click.stop="toggleUserStatus(user.id)" class="text-gray-600 hover:text-gray-900">
                  <ToggleRight class="w-4 h-4" />
                </button>
                <button @click.stop="deleteUser(user.id)" class="text-red-600 hover:text-red-900">
                  <Trash2 class="w-4 h-4" />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div v-if="filteredUsers.length > 0" class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Showing
              <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
              to
              <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredUsers.length) }}</span>
              of
              <span class="font-medium">{{ filteredUsers.length }}</span>
              users
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
              <button
                @click="currentPage = Math.max(currentPage - 1, 1)"
                :disabled="currentPage === 1"
                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
              >
                <ChevronLeft class="h-5 w-5" />
              </button>
              <button
                v-for="page in totalPages"
                :key="page"
                @click="currentPage = page"
                :class="[
                  'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                  currentPage === page
                    ? 'z-10 bg-teal-50 border-teal-500 text-teal-600'
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                ]"
              >
                {{ page }}
              </button>
              <button
                @click="currentPage = Math.min(currentPage + 1, totalPages)"
                :disabled="currentPage === totalPages"
                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
              >
                <ChevronRight class="h-5 w-5" />
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>
    
    <!-- No Users Message -->
    <div v-if="filteredUsers.length === 0" class="text-center py-12 bg-white rounded-2xl shadow-sm border border-gray-200">
      <Users class="w-16 h-16 text-gray-400 mx-auto mb-4" />
      <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
      <p class="text-gray-500 mb-4">Try adjusting your search or status filter.</p>
      <button class="px-4 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors">
        Add First User
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Users, Search, Eye, ToggleRight, Trash2, Plus, ChevronLeft, ChevronRight } from 'lucide-vue-next'

const searchQuery = ref('')
const userFilter = ref('all')
const currentPage = ref(1)
const itemsPerPage = 10

const users = ref([
  { id: 1, name: 'Wanjiku Mwangi', initials: 'WM', email: 'wanjiku@client.co.ke', role: 'client', status: 'active', joined: 'Sep 15, 2025', lastActive: 'Today' },
  { id: 2, name: 'Ochieng Kiprop', initials: 'OK', email: 'ochieng@client.co.ke', role: 'client', status: 'active', joined: 'Aug 20, 2025', lastActive: '2 days ago' },
  { id: 3, name: 'Muthoni Njoroge', initials: 'MN', email: 'muthoni@client.co.ke', role: 'therapist', status: 'suspended', joined: 'Jul 10, 2025', lastActive: '1 week ago' },
  { id: 4, name: 'Achieng Omondi', initials: 'AO', email: 'achieng@client.co.ke', role: 'client', status: 'active', joined: 'Oct 1, 2025', lastActive: 'Today' },
  { id: 5, name: 'Juma Kiptoo', initials: 'JK', email: 'juma@client.co.ke', role: 'client', status: 'active', joined: 'Sep 5, 2025', lastActive: '3 days ago' }
])

const filteredUsers = computed(() => {
  let filtered = users.value
  
  if (searchQuery.value) {
    filtered = filtered.filter(user => 
      user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      user.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }
  
  if (userFilter.value !== 'all') {
    filtered = filtered.filter(user => user.status === userFilter.value)
  }
  
  return filtered
})

const totalPages = computed(() => Math.ceil(filteredUsers.value.length / itemsPerPage))

const paginatedUsers = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredUsers.value.slice(start, end)
})

const setUserFilter = (filter) => {
  userFilter.value = filter
  currentPage.value = 1
}

const viewUser = (id) => {
  console.log(`View user ${id}`)
}

const toggleUserStatus = (id) => {
  console.log(`Toggle status for user ${id}`)
}

const deleteUser = (id) => {
  if (confirm('Delete this user?')) {
    console.log(`Delete user ${id}`)
  }
}
</script>

<style scoped>
</style>