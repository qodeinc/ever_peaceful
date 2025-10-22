<!-- src/views/admin/Settings.vue -->
<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-3xl font-bold text-gray-900">Platform Settings</h1>
    </div>
    
    <!-- Settings Sections -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
      <div class="space-y-8">
        <!-- General Settings -->
        <div>
          <h3 class="text-xl font-semibold text-gray-900 mb-4">General Settings</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Platform Name</label>
              <input 
                v-model="settings.platformName" 
                type="text" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Support Email</label>
              <input 
                v-model="settings.supportEmail" 
                type="email" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
              />
            </div>
          </div>
        </div>
        
        <!-- Billing Settings -->
        <div>
          <h3 class="text-xl font-semibold text-gray-900 mb-4">Billing Settings</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Default Session Rate (KSh)</label>
              <input 
                v-model="settings.defaultRate" 
                type="number" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Payment Gateway</label>
              <select v-model="settings.paymentGateway" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="mpesa">M-Pesa</option>
                <option value="stripe">Stripe</option>
                <option value="paypal">PayPal</option>
              </select>
            </div>
          </div>
        </div>
        
        <!-- Notification Settings -->
        <div>
          <h3 class="text-xl font-semibold text-gray-900 mb-4">Notification Settings</h3>
          <div class="space-y-4">
            <label class="flex items-center gap-3">
              <input type="checkbox" v-model="settings.emailNotifications" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500" />
              <span class="text-sm text-gray-700">Email notifications for admins</span>
            </label>
            <label class="flex items-center gap-3">
              <input type="checkbox" v-model="settings.smsNotifications" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500" />
              <span class="text-sm text-gray-700">SMS alerts for critical events</span>
            </label>
          </div>
        </div>
      </div>
      <div class="pt-6 border-t border-gray-200 flex justify-end gap-3">
        <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors">Cancel</button>
        <button @click="saveSettings" class="px-6 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors font-medium">
          Save Settings
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const settings = ref({
  platformName: 'MindWell Therapy',
  supportEmail: 'support@mindwell.co.ke',
  defaultRate: 5000,
  paymentGateway: 'mpesa',
  emailNotifications: true,
  smsNotifications: false
})

const saveSettings = () => {
  console.log('Saving settings:', settings.value)
  alert('Settings saved successfully!')
}
</script>

<style scoped>
</style>