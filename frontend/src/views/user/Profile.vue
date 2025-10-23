<template>
  <div class="h-screen bg-white flex flex-col">
    <div class="max-w-7xl mx-auto px-6 py-8 w-full">
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-5xl font-light text-gray-900 mb-2 tracking-tight">Profile</h1>
        <p class="text-lg text-gray-500">Manage your personal information and preferences</p>
      </div>
    </div>

    <!-- Profile Content - Scrollable -->
    <div class="flex-1 max-w-7xl mx-auto px-6 pb-6 w-full overflow-y-auto">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Profile Card -->
        <div class="lg:col-span-1">
          <div class="bg-white border border-gray-200 p-6 sticky top-0">
            <!-- Profile Picture -->
            <div class="flex flex-col items-center mb-6">
              <div class="relative">
                <img 
                  :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${profileData.name}`"
                  :alt="profileData.name"
                  class="w-32 h-32 rounded-full border-4 border-emerald-200"
                />
                <button 
                  @click="changeAvatar"
                  class="absolute bottom-0 right-0 w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-white hover:bg-emerald-700 transition-colors border-4 border-white"
                >
                  <Camera class="w-5 h-5" />
                </button>
              </div>
              <h2 class="text-2xl font-semibold text-gray-900 mt-4">{{ profileData.name }}</h2>
              <p class="text-sm text-gray-500">{{ profileData.email }}</p>
              <span class="mt-3 px-3 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">
                {{ profileData.memberSince }}
              </span>
            </div>

            <!-- Quick Stats -->
            <div class="space-y-4 border-t border-gray-200 pt-6">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <Calendar class="w-5 h-5 text-blue-600" />
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-900">Sessions</p>
                    <p class="text-xs text-gray-500">Completed</p>
                  </div>
                </div>
                <span class="text-lg font-semibold text-gray-900">{{ profileData.stats.sessionsCompleted }}</span>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <Target class="w-5 h-5 text-purple-600" />
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-900">Goals</p>
                    <p class="text-xs text-gray-500">Achieved</p>
                  </div>
                </div>
                <span class="text-lg font-semibold text-gray-900">{{ profileData.stats.goalsAchieved }}</span>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <TrendingUp class="w-5 h-5 text-green-600" />
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-900">Streak</p>
                    <p class="text-xs text-gray-500">Days</p>
                  </div>
                </div>
                <span class="text-lg font-semibold text-gray-900">{{ profileData.stats.streak }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column - Profile Details -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Personal Information -->
          <div class="bg-white border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-xl font-semibold text-gray-900">Personal Information</h3>
              <button 
                @click="editMode.personal = !editMode.personal"
                class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
              >
                <Edit2 class="w-4 h-4" />
                {{ editMode.personal ? 'Cancel' : 'Edit' }}
              </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input
                  v-model="profileData.phone"
                  :disabled="!editMode.personal"
                  type="tel"
                  class="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 bg-transparent transition-colors disabled:text-gray-500"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                <input
                  v-model="profileData.dateOfBirth"
                  :disabled="!editMode.personal"
                  type="date"
                  class="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 bg-transparent transition-colors disabled:text-gray-500"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                <select
                  v-model="profileData.gender"
                  :disabled="!editMode.personal"
                  class="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 bg-transparent transition-colors disabled:text-gray-500"
                >
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                  <option value="prefer-not-to-say">Prefer not to say</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                <input
                  v-model="profileData.location"
                  :disabled="!editMode.personal"
                  type="text"
                  class="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 bg-transparent transition-colors disabled:text-gray-500"
                />
              </div>
            </div>

            <div v-if="editMode.personal" class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
              <button
                @click="editMode.personal = false"
                class="px-6 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
              >
                Cancel
              </button>
              <button
                @click="savePersonalInfo"
                class="px-6 py-2 text-sm font-medium bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg transition-colors"
              >
                Save Changes
              </button>
            </div>
          </div>

          <!-- Medical Information -->
          <div class="bg-white border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-xl font-semibold text-gray-900">Medical Information</h3>
              <button 
                @click="editMode.medical = !editMode.medical"
                class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
              >
                <Edit2 class="w-4 h-4" />
                {{ editMode.medical ? 'Cancel' : 'Edit' }}
              </button>
            </div>

            <div class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact Name</label>
                <input
                  v-model="profileData.medical.emergencyContact"
                  :disabled="!editMode.medical"
                  type="text"
                  class="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 bg-transparent transition-colors disabled:text-gray-500"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact Phone</label>
                <input
                  v-model="profileData.medical.emergencyPhone"
                  :disabled="!editMode.medical"
                  type="tel"
                  class="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 bg-transparent transition-colors disabled:text-gray-500"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Allergies</label>
                <textarea
                  v-model="profileData.medical.allergies"
                  :disabled="!editMode.medical"
                  rows="3"
                  class="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 bg-transparent transition-colors disabled:text-gray-500 resize-none"
                  placeholder="List any allergies..."
                ></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Medications</label>
                <textarea
                  v-model="profileData.medical.medications"
                  :disabled="!editMode.medical"
                  rows="3"
                  class="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 bg-transparent transition-colors disabled:text-gray-500 resize-none"
                  placeholder="List any current medications..."
                ></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Medical Conditions</label>
                <textarea
                  v-model="profileData.medical.conditions"
                  :disabled="!editMode.medical"
                  rows="3"
                  class="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 bg-transparent transition-colors disabled:text-gray-500 resize-none"
                  placeholder="List any medical conditions..."
                ></textarea>
              </div>
            </div>

            <div v-if="editMode.medical" class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
              <button
                @click="editMode.medical = false"
                class="px-6 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
              >
                Cancel
              </button>
              <button
                @click="saveMedicalInfo"
                class="px-6 py-2 text-sm font-medium bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg transition-colors"
              >
                Save Changes
              </button>
            </div>
          </div>

          <!-- Preferences -->
          <div class="bg-white border border-gray-200 p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Preferences</h3>

            <div class="space-y-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-900">Email Notifications</p>
                  <p class="text-xs text-gray-500">Receive email updates about your sessions</p>
                </div>
                <button
                  @click="profileData.preferences.emailNotifications = !profileData.preferences.emailNotifications"
                  :class="[
                    'relative w-12 h-6 rounded-full transition-colors',
                    profileData.preferences.emailNotifications ? 'bg-emerald-600' : 'bg-gray-300'
                  ]"
                >
                  <span
                    :class="[
                      'absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform',
                      profileData.preferences.emailNotifications ? 'translate-x-6' : 'translate-x-0'
                    ]"
                  ></span>
                </button>
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-900">SMS Reminders</p>
                  <p class="text-xs text-gray-500">Get text reminders for upcoming sessions</p>
                </div>
                <button
                  @click="profileData.preferences.smsReminders = !profileData.preferences.smsReminders"
                  :class="[
                    'relative w-12 h-6 rounded-full transition-colors',
                    profileData.preferences.smsReminders ? 'bg-emerald-600' : 'bg-gray-300'
                  ]"
                >
                  <span
                    :class="[
                      'absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform',
                      profileData.preferences.smsReminders ? 'translate-x-6' : 'translate-x-0'
                    ]"
                  ></span>
                </button>
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-900">Weekly Progress Reports</p>
                  <p class="text-xs text-gray-500">Receive weekly summaries of your progress</p>
                </div>
                <button
                  @click="profileData.preferences.weeklyReports = !profileData.preferences.weeklyReports"
                  :class="[
                    'relative w-12 h-6 rounded-full transition-colors',
                    profileData.preferences.weeklyReports ? 'bg-emerald-600' : 'bg-gray-300'
                  ]"
                >
                  <span
                    :class="[
                      'absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform',
                      profileData.preferences.weeklyReports ? 'translate-x-6' : 'translate-x-0'
                    ]"
                  ></span>
                </button>
              </div>

              <div class="pt-4 border-t border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Session Time</label>
                <select
                  v-model="profileData.preferences.preferredTime"
                  class="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 bg-transparent transition-colors"
                >
                  <option value="morning">Morning (8AM - 12PM)</option>
                  <option value="afternoon">Afternoon (12PM - 5PM)</option>
                  <option value="evening">Evening (5PM - 9PM)</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Session Duration Preference</label>
                <select
                  v-model="profileData.preferences.sessionDuration"
                  class="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 bg-transparent transition-colors"
                >
                  <option value="30">30 minutes</option>
                  <option value="45">45 minutes</option>
                  <option value="60">60 minutes</option>
                </select>
              </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
              <button
                @click="savePreferences"
                class="px-6 py-2 text-sm font-medium bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg transition-colors"
              >
                Save Preferences
              </button>
            </div>
          </div>

          <!-- Danger Zone -->
          <div class="bg-white border border-red-200 p-6">
            <h3 class="text-xl font-semibold text-red-900 mb-6">Danger Zone</h3>
            
            <div class="space-y-4">
              <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div>
                  <p class="text-sm font-medium text-gray-900">Deactivate Account</p>
                  <p class="text-xs text-gray-500">Temporarily disable your account</p>
                </div>
                <button
                  @click="deactivateAccount"
                  class="px-4 py-2 text-sm font-medium text-orange-600 hover:bg-orange-50 rounded-lg transition-colors"
                >
                  Deactivate
                </button>
              </div>

              <div class="flex items-center justify-between p-4 border border-red-200 rounded-lg">
                <div>
                  <p class="text-sm font-medium text-gray-900">Delete Account</p>
                  <p class="text-xs text-gray-500">Permanently delete your account and all data</p>
                </div>
                <button
                  @click="deleteAccount"
                  class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                >
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Camera, Calendar, Target, TrendingUp, Edit2 } from 'lucide-vue-next'

const profileData = ref({
  name: 'Sarah Johnson',
  email: 'sarah.johnson@mindwell.com',
  phone: '+1 (555) 123-4567',
  dateOfBirth: '1990-05-15',
  gender: 'female',
  location: 'San Francisco, CA',
  memberSince: 'Member since Jan 2024',
  stats: {
    sessionsCompleted: 24,
    goalsAchieved: 8,
    streak: 15
  },
  medical: {
    emergencyContact: 'John Johnson',
    emergencyPhone: '+1 (555) 987-6543',
    allergies: 'Penicillin, Pollen',
    medications: 'None',
    conditions: 'None'
  },
  preferences: {
    emailNotifications: true,
    smsReminders: true,
    weeklyReports: false,
    preferredTime: 'afternoon',
    sessionDuration: '60'
  }
})

const editMode = ref({
  personal: false,
  medical: false
})

const changeAvatar = () => {
  // Generate new random avatar by changing the seed
  const randomSeed = Math.random().toString(36).substring(7)
  profileData.value.name = profileData.value.name + randomSeed
  setTimeout(() => {
    profileData.value.name = profileData.value.name.replace(randomSeed, '')
  }, 100)
  console.log('Change avatar clicked')
}

const savePersonalInfo = () => {
  editMode.value.personal = false
  console.log('Personal info saved:', profileData.value)
  // Add API call here
}

const saveMedicalInfo = () => {
  editMode.value.medical = false
  console.log('Medical info saved:', profileData.value.medical)
  // Add API call here
}

const savePreferences = () => {
  console.log('Preferences saved:', profileData.value.preferences)
  // Add API call here
}

const deactivateAccount = () => {
  if (confirm('Are you sure you want to deactivate your account?')) {
    console.log('Account deactivation requested')
    // Add API call here
  }
}

const deleteAccount = () => {
  if (confirm('Are you sure you want to permanently delete your account? This action cannot be undone.')) {
    console.log('Account deletion requested')
    // Add API call here
  }
}
</script>

<style scoped>
/* Custom scrollbar */
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
button, input, textarea, select {
  transition: all 0.2s ease;
}

/* Toggle button animation */
button span {
  transition: transform 0.3s ease;
}
</style>