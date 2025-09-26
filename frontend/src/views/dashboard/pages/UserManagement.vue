<template>
  <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
    <div class="flex items-center justify-between mb-6">
      <h3 class="text-lg font-bold text-gray-900">User Overview</h3>
      <button class="px-4 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors">
        Add New User
      </button>
    </div>
    <div class="text-center py-12 text-gray-500">
      <Users class="w-16 h-16 mx-auto mb-4 text-gray-300" />
      <p>User management interface would be implemented here</p>
    </div>
  </div>
</template>
<script setup lang="ts">
import { Users } from 'lucide-vue-next'
</script>