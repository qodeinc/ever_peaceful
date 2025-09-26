<template>
  <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Account Settings</h2>
    <p class="text-gray-600 mb-6">Manage your account preferences.</p>
    
    <!-- Change Password -->
    <form @submit.prevent="changePassword" class="space-y-6 mb-8">
      <h3 class="text-lg font-semibold text-gray-900">Change Password</h3>
      <div>
        <label for="currentPassword" class="block text-sm font-medium text-gray-700">Current Password</label>
        <input 
          v-model="passwordForm.currentPassword" 
          id="currentPassword" 
          type="password" 
          class="mt-1 w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
        />
      </div>
      <div>
        <label for="newPassword" class="block text-sm font-medium text-gray-700">New Password</label>
        <input 
          v-model="passwordForm.newPassword" 
          id="newPassword" 
          type="password" 
          class="mt-1 w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
        />
      </div>
      <button 
        type="submit" 
        :disabled="!passwordForm.currentPassword || !passwordForm.newPassword"
        class="w-full bg-teal-600 text-white py-3 px-4 rounded-xl hover:bg-teal-700 transition-colors font-medium disabled:bg-gray-300 disabled:cursor-not-allowed"
      >
        Update Password
      </button>
    </form>

    <!-- Notification Preferences -->
    <form @submit.prevent="updateNotifications" class="space-y-6">
      <h3 class="text-lg font-semibold text-gray-900">Notification Preferences</h3>
      <div class="space-y-4">
        <div class="flex items-center space-x-3">
          <input type="checkbox" v-model="notificationPrefs.email" id="emailNotifications" class="h-5 w-5 text-teal-600 rounded">
          <label for="emailNotifications" class="text-sm text-gray-700">Email Notifications</label>
        </div>
        <div class="flex items-center space-x-3">
          <input type="checkbox" v-model="notificationPrefs.sms" id="smsNotifications" class="h-5 w-5 text-teal-600 rounded">
          <label for="smsNotifications" class="text-sm text-gray-700">SMS Notifications</label>
        </div>
      </div>
      <button 
        type="submit" 
        class="w-full bg-teal-600 text-white py-3 px-4 rounded-xl hover:bg-teal-700 transition-colors font-medium"
      >
        Save Preferences
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const passwordForm = ref({
  currentPassword: '',
  newPassword: ''
})

const notificationPrefs = ref({
  email: true,
  sms: false
})

const changePassword = async () => {
  try {
    console.log('Changing password:', passwordForm.value)
    alert('Password updated successfully!')
    passwordForm.value = { currentPassword: '', newPassword: '' }
  } catch (error) {
    console.error('Error changing password:', error)
    alert('Failed to update password.')
  }
}

const updateNotifications = async () => {
  try {
    console.log('Updating notification preferences:', notificationPrefs.value)
    alert('Notification preferences updated!')
  } catch (error) {
    console.error('Error updating notification preferences:', error)
    alert('Failed to update preferences.')
  }
}
</script>

<style scoped>
/* Checkbox and input styling */
input[type="checkbox"] {
  accent-color: #0d9488;
}

input[type="password"] {
  position: relative;
  overflow: hidden;
}

input[type="password"]::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s;
}

input[type="password"]:focus::before {
  left: 100%;
}
</style>