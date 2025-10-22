<!-- src/views/admin/Billing.vue -->
<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-3xl font-bold text-gray-900">Billing & Revenue</h1>
      <button class="px-4 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors">
        Generate Report
      </button>
    </div>
    
    <!-- Search and Date Range -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200">
      <div class="flex flex-col sm:flex-row gap-4 items-center">
        <div class="relative flex-1">
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Search transactions..." 
            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
          />
          <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
        </div>
        <div class="flex gap-2">
          <input 
            v-model="startDate" 
            type="date" 
            class="px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
          />
          <input 
            v-model="endDate" 
            type="date" 
            class="px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
          />
          <button @click="applyDateFilter" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
            Filter
          </button>
        </div>
      </div>
    </div>
    
    <!-- Revenue Summary -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
      <h3 class="text-xl font-semibold text-gray-900 mb-4">Revenue Summary</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="text-center p-4 bg-teal-50 rounded-xl">
          <p class="text-3xl font-bold text-teal-600">{{ formatCurrency(summary.totalRevenue) }}</p>
          <p class="text-sm text-gray-600">Total Revenue</p>
        </div>
        <div class="text-center p-4 bg-emerald-50 rounded-xl">
          <p class="text-3xl font-bold text-emerald-600">{{ formatCurrency(summary.thisMonth) }}</p>
          <p class="text-sm text-gray-600">This Month</p>
        </div>
        <div class="text-center p-4 bg-blue-50 rounded-xl">
          <p class="text-3xl font-bold text-blue-600">{{ summary.transactionCount }}</p>
          <p class="text-sm text-gray-600">Transactions</p>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-50 p-4 rounded-xl">
          <h4 class="font-medium text-gray-900 mb-2">Growth</h4>
          <p class="text-green-600 font-medium">↑ {{ summary.growth }}% vs last month</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-xl">
          <h4 class="font-medium text-gray-900 mb-2">Pending Payouts</h4>
          <p class="text-orange-600 font-medium">KSh {{ formatCurrency(summary.pendingPayouts) }}</p>
        </div>
      </div>
    </div>
    
    <!-- Transactions Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Therapist</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="transaction in paginatedTransactions" :key="transaction.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ transaction.date }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ transaction.userName }}</div>
                <div class="text-sm text-gray-500">{{ transaction.userEmail }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ transaction.therapistName }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">KSh {{ transaction.amount }}</div>
                <div class="text-sm text-green-600">+{{ transaction.amount }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                      :class="transaction.status === 'completed' ? 'bg-green-100 text-green-800' : transaction.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'">
                  {{ transaction.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                <button @click.stop="viewTransaction(transaction.id)" class="text-teal-600 hover:text-teal-900">
                  <Eye class="w-4 h-4" />
                </button>
                <button @click.stop="refundTransaction(transaction.id)" class="text-red-600 hover:text-red-900">
                  <RefreshCw class="w-4 h-4" />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div v-if="filteredTransactions.length > 0" class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Showing
              <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
              to
              <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredTransactions.length) }}</span>
              of
              <span class="font-medium">{{ filteredTransactions.length }}</span>
              transactions
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
    
    <!-- No Transactions Message -->
    <div v-if="filteredTransactions.length === 0" class="text-center py-12 bg-white rounded-2xl shadow-sm border border-gray-200">
      <DollarSign class="w-16 h-16 text-gray-400 mx-auto mb-4" />
      <h3 class="text-lg font-medium text-gray-900 mb-2">No transactions found</h3>
      <p class="text-gray-500 mb-4">Try adjusting your search or date range.</p>
      <button class="px-4 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors">
        Generate Report
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { DollarSign, Search, Eye, RefreshCw, ChevronLeft, ChevronRight } from 'lucide-vue-next'

const searchQuery = ref('')
const startDate = ref('')
const endDate = ref('')
const currentPage = ref(1)
const itemsPerPage = 10

const transactions = ref([
  { id: 1, date: 'Oct 1, 2025', userName: 'Wanjiku Mwangi', userEmail: 'wanjiku@client.co.ke', therapistName: 'Dr. Amina Otieno', amount: 5000, status: 'completed' },
  { id: 2, date: 'Sep 30, 2025', userName: 'Ochieng Kiprop', userEmail: 'ochieng@client.co.ke', therapistName: 'Dr. Kipchoge Kiptoo', amount: 6000, status: 'pending' },
  { id: 3, date: 'Sep 28, 2025', userName: 'Muthoni Njoroge', userEmail: 'muthoni@client.co.ke', therapistName: 'Dr. Muthoni Njoroge', amount: 7000, status: 'completed' },
  { id: 4, date: 'Sep 25, 2025', userName: 'Achieng Omondi', userEmail: 'achieng@client.co.ke', therapistName: 'Dr. Omondi Achieng', amount: 4500, status: 'completed' }
])

const summary = ref({
  totalRevenue: 4725000,
  thisMonth: 1250000,
  transactionCount: 156,
  growth: 15.2,
  pendingPayouts: 250000
})

const filteredTransactions = computed(() => {
  let filtered = transactions.value
  
  if (searchQuery.value) {
    filtered = filtered.filter(transaction => 
      transaction.userName.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      transaction.userEmail.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      transaction.therapistName.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }
  
  // Date filtering logic here
  
  return filtered
})

const totalPages = computed(() => Math.ceil(filteredTransactions.value.length / itemsPerPage))

const paginatedTransactions = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredTransactions.value.slice(start, end)
})

const formatCurrency = (amount) => {
  return `KSh ${amount.toLocaleString('en-KE')}`
}

const applyDateFilter = () => {
  console.log('Applying date filter:', startDate.value, endDate.value)
}

const viewTransaction = (id) => {
  console.log(`View transaction ${id}`)
}

const refundTransaction = (id) => {
  console.log(`Refund transaction ${id}`)
}
</script>

<style scoped>
</style>