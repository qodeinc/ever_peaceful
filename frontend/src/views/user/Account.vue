<template>
  <div class="h-screen bg-white flex flex-col">
    <div class="max-w-7xl mx-auto px-6 py-8 w-full">
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-5xl font-light text-gray-900 mb-2 tracking-tight">Account Settings</h1>
        <p class="text-lg text-gray-500">Manage your account security and billing information</p>
      </div>
    </div>

    <!-- Account Content - Scrollable -->
    <div class="flex-1 max-w-7xl mx-auto px-6 pb-6 w-full overflow-y-auto">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Quick Actions -->
        <div class="lg:col-span-1">
          <div class="bg-white border border-gray-200 p-6 sticky top-0 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            
            <button
              @click="scrollToSection('security')"
              class="w-full flex items-center gap-3 px-4 py-3 text-left text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors"
            >
              <Shield class="w-5 h-5 text-emerald-600" />
              <span>Security Settings</span>
            </button>

            <button
              @click="scrollToSection('billing')"
              class="w-full flex items-center gap-3 px-4 py-3 text-left text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors"
            >
              <CreditCard class="w-5 h-5 text-emerald-600" />
              <span>Billing & Plans</span>
            </button>

            <button
              @click="scrollToSection('sessions')"
              class="w-full flex items-center gap-3 px-4 py-3 text-left text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors"
            >
              <Clock class="w-5 h-5 text-emerald-600" />
              <span>Active Sessions</span>
            </button>

            <button
              @click="scrollToSection('privacy')"
              class="w-full flex items-center gap-3 px-4 py-3 text-left text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors"
            >
              <Lock class="w-5 h-5 text-emerald-600" />
              <span>Privacy & Data</span>
            </button>

            <!-- Account Status -->
            <div class="mt-6 pt-6 border-t border-gray-200">
              <h4 class="text-sm font-semibold text-gray-900 mb-3">Account Status</h4>
              <div class="space-y-3">
                <div class="flex items-center justify-between">
                  <span class="text-xs text-gray-600">Account Type</span>
                  <span class="text-xs font-medium text-emerald-600">Premium</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-xs text-gray-600">Member Since</span>
                  <span class="text-xs font-medium text-gray-900">Jan 2024</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-xs text-gray-600">Status</span>
                  <span class="flex items-center gap-1 text-xs font-medium text-green-600">
                    <CheckCircle class="w-3 h-3" />
                    Active
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column - Account Details -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Security Settings -->
          <div ref="security" class="bg-white border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                <Shield class="w-5 h-5 text-emerald-600" />
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900">Security Settings</h3>
                <p class="text-sm text-gray-500">Manage your password and authentication</p>
              </div>
            </div>

            <div class="space-y-6">
              <!-- Change Password -->
              <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Change Password</h4>
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input
                      v-model="securityData.currentPassword"
                      type="password"
                      placeholder="Enter current password"
                      class="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 bg-transparent transition-colors"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input
                      v-model="securityData.newPassword"
                      type="password"
                      placeholder="Enter new password"
                      class="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 bg-transparent transition-colors"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input
                      v-model="securityData.confirmPassword"
                      type="password"
                      placeholder="Confirm new password"
                      class="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 bg-transparent transition-colors"
                    />
                  </div>
                </div>
                <button
                  @click="changePassword"
                  class="mt-4 px-6 py-2 text-sm font-medium bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg transition-colors"
                >
                  Update Password
                </button>
              </div>

              <!-- Two-Factor Authentication -->
              <div class="pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between mb-4">
                  <div>
                    <h4 class="text-sm font-semibold text-gray-900">Two-Factor Authentication</h4>
                    <p class="text-xs text-gray-500 mt-1">Add an extra layer of security to your account</p>
                  </div>
                  <button
                    @click="securityData.twoFactorEnabled = !securityData.twoFactorEnabled"
                    :class="[
                      'relative w-12 h-6 rounded-full transition-colors',
                      securityData.twoFactorEnabled ? 'bg-emerald-600' : 'bg-gray-300'
                    ]"
                  >
                    <span
                      :class="[
                        'absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform',
                        securityData.twoFactorEnabled ? 'translate-x-6' : 'translate-x-0'
                      ]"
                    ></span>
                  </button>
                </div>
                <div v-if="securityData.twoFactorEnabled" class="p-4 bg-emerald-50 rounded-lg">
                  <p class="text-sm text-emerald-700">Two-factor authentication is enabled. You'll need to verify your identity with a code sent to your phone.</p>
                </div>
              </div>

              <!-- Login Alerts -->
              <div class="pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between">
                  <div>
                    <h4 class="text-sm font-semibold text-gray-900">Login Alerts</h4>
                    <p class="text-xs text-gray-500 mt-1">Get notified of new login attempts</p>
                  </div>
                  <button
                    @click="securityData.loginAlerts = !securityData.loginAlerts"
                    :class="[
                      'relative w-12 h-6 rounded-full transition-colors',
                      securityData.loginAlerts ? 'bg-emerald-600' : 'bg-gray-300'
                    ]"
                  >
                    <span
                      :class="[
                        'absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform',
                        securityData.loginAlerts ? 'translate-x-6' : 'translate-x-0'
                      ]"
                    ></span>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Billing & Subscription -->
          <div ref="billing" class="bg-white border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                <CreditCard class="w-5 h-5 text-blue-600" />
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900">Billing & Plans</h3>
                <p class="text-sm text-gray-500">Manage your subscription and payment methods</p>
              </div>
            </div>

            <!-- Current Plan -->
            <div class="mb-6">
              <div class="border border-emerald-200 rounded-lg p-6 bg-emerald-50">
                <div class="flex items-center justify-between mb-4">
                  <div>
                    <h4 class="text-lg font-semibold text-gray-900">Premium Plan</h4>
                    <p class="text-sm text-gray-600 mt-1">Unlimited sessions and resources</p>
                  </div>
                  <div class="text-right">
                    <p class="text-2xl font-bold text-gray-900">$49.99</p>
                    <p class="text-xs text-gray-500">per month</p>
                  </div>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                  <Calendar class="w-4 h-4" />
                  <span>Next billing date: Feb 15, 2024</span>
                </div>
                <div class="flex gap-3">
                  <button
                    @click="changePlan"
                    class="px-4 py-2 text-sm font-medium text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors"
                  >
                    Change Plan
                  </button>
                  <button
                    @click="cancelSubscription"
                    class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                  >
                    Cancel Subscription
                  </button>
                </div>
              </div>
            </div>

            <!-- Payment Methods -->
            <div>
              <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-semibold text-gray-900">Payment Methods</h4>
                <button
                  @click="addPaymentMethod"
                  class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                >
                  <Plus class="w-4 h-4" />
                  Add New
                </button>
              </div>
              
              <div class="space-y-3">
                <div
                  v-for="card in billingData.paymentMethods"
                  :key="card.id"
                  class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-emerald-200 transition-colors"
                >
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center">
                      <CreditCard class="w-5 h-5 text-gray-600" />
                    </div>
                    <div>
                      <p class="text-sm font-medium text-gray-900">{{ card.brand }} •••• {{ card.last4 }}</p>
                      <p class="text-xs text-gray-500">Expires {{ card.expiry }}</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2">
                    <span v-if="card.default" class="px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">
                      Default
                    </span>
                    <button
                      @click="removePaymentMethod(card.id)"
                      class="p-2 text-gray-400 hover:text-red-600 transition-colors"
                    >
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Billing History -->
            <div class="mt-6 pt-6 border-t border-gray-200">
              <h4 class="text-sm font-semibold text-gray-900 mb-4">Billing History</h4>
              <div class="space-y-3">
                <div
                  v-for="invoice in billingData.invoices"
                  :key="invoice.id"
                  class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                >
                  <div class="flex items-center gap-3">
                    <FileText class="w-5 h-5 text-gray-400" />
                    <div>
                      <p class="text-sm font-medium text-gray-900">{{ invoice.date }}</p>
                      <p class="text-xs text-gray-500">Invoice #{{ invoice.number }}</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-3">
                    <span class="text-sm font-medium text-gray-900">{{ invoice.amount }}</span>
                    <button
                      @click="downloadInvoice(invoice.id)"
                      class="p-2 text-emerald-600 hover:bg-emerald-50 rounded transition-colors"
                    >
                      <Download class="w-4 h-4" />
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Active Sessions -->
          <div ref="sessions" class="bg-white border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                <Clock class="w-5 h-5 text-purple-600" />
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900">Active Sessions</h3>
                <p class="text-sm text-gray-500">Manage devices where you're currently logged in</p>
              </div>
            </div>

            <div class="space-y-3">
              <div
                v-for="session in activeSessions"
                :key="session.id"
                class="flex items-center justify-between p-4 border border-gray-200 rounded-lg"
              >
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                    <Monitor v-if="session.device === 'Desktop'" class="w-5 h-5 text-gray-600" />
                    <Smartphone v-else class="w-5 h-5 text-gray-600" />
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-900">{{ session.device }}</p>
                    <p class="text-xs text-gray-500">{{ session.location }} • {{ session.lastActive }}</p>
                  </div>
                </div>
                <div class="flex items-center gap-2">
                  <span v-if="session.current" class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                    Current
                  </span>
                  <button
                    v-else
                    @click="revokeSession(session.id)"
                    class="px-3 py-1 text-xs font-medium text-red-600 hover:bg-red-50 rounded transition-colors"
                  >
                    Revoke
                  </button>
                </div>
              </div>
            </div>

            <button
              @click="revokeAllSessions"
              class="mt-4 w-full px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors"
            >
              Sign Out All Other Devices
            </button>
          </div>

          <!-- Privacy & Data -->
          <div ref="privacy" class="bg-white border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                <Lock class="w-5 h-5 text-orange-600" />
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900">Privacy & Data</h3>
                <p class="text-sm text-gray-500">Control your data and privacy settings</p>
              </div>
            </div>

            <div class="space-y-6">
              <!-- Data Download -->
              <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div>
                  <p class="text-sm font-medium text-gray-900">Download Your Data</p>
                  <p class="text-xs text-gray-500 mt-1">Get a copy of all your account data</p>
                </div>
                <button
                  @click="downloadData"
                  class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                >
                  <Download class="w-4 h-4" />
                  Download
                </button>
              </div>

              <!-- Privacy Settings -->
              <div class="space-y-4">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm font-medium text-gray-900">Profile Visibility</p>
                    <p class="text-xs text-gray-500">Make your profile visible to therapists</p>
                  </div>
                  <button
                    @click="privacyData.profileVisible = !privacyData.profileVisible"
                    :class="[
                      'relative w-12 h-6 rounded-full transition-colors',
                      privacyData.profileVisible ? 'bg-emerald-600' : 'bg-gray-300'
                    ]"
                  >
                    <span
                      :class="[
                        'absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform',
                        privacyData.profileVisible ? 'translate-x-6' : 'translate-x-0'
                      ]"
                    ></span>
                  </button>
                </div>

                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm font-medium text-gray-900">Activity Status</p>
                    <p class="text-xs text-gray-500">Show when you're online</p>
                  </div>
                  <button
                    @click="privacyData.activityStatus = !privacyData.activityStatus"
                    :class="[
                      'relative w-12 h-6 rounded-full transition-colors',
                      privacyData.activityStatus ? 'bg-emerald-600' : 'bg-gray-300'
                    ]"
                  >
                    <span
                      :class="[
                        'absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform',
                        privacyData.activityStatus ? 'translate-x-6' : 'translate-x-0'
                      ]"
                    ></span>
                  </button>
                </div>

                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm font-medium text-gray-900">Analytics & Cookies</p>
                    <p class="text-xs text-gray-500">Help us improve with usage data</p>
                  </div>
                  <button
                    @click="privacyData.analytics = !privacyData.analytics"
                    :class="[
                      'relative w-12 h-6 rounded-full transition-colors',
                      privacyData.analytics ? 'bg-emerald-600' : 'bg-gray-300'
                    ]"
                  >
                    <span
                      :class="[
                        'absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform',
                        privacyData.analytics ? 'translate-x-6' : 'translate-x-0'
                      ]"
                    ></span>
                  </button>
                </div>
              </div>

              <!-- Data Retention -->
              <div class="pt-6 border-t border-gray-200">
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Data Retention</h4>
                <select
                  v-model="privacyData.dataRetention"
                  class="w-full px-4 py-3 border-b-2 border-gray-200 focus:outline-none focus:border-emerald-600 bg-transparent transition-colors"
                >
                  <option value="1">Delete data after 1 year</option>
                  <option value="2">Delete data after 2 years</option>
                  <option value="5">Delete data after 5 years</option>
                  <option value="never">Keep data indefinitely</option>
                </select>
              </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
              <button
                @click="savePrivacySettings"
                class="px-6 py-2 text-sm font-medium bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg transition-colors"
              >
                Save Privacy Settings
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { 
  Shield, CreditCard, Clock, Lock, Calendar, Plus, Trash2, 
  FileText, Download, Monitor, Smartphone, CheckCircle, Edit2 
} from 'lucide-vue-next'

const securityData = ref({
  currentPassword: '',
  newPassword: '',
  confirmPassword: '',
  twoFactorEnabled: true,
  loginAlerts: true
})

const billingData = ref({
  paymentMethods: [
    { id: 1, brand: 'Visa', last4: '4242', expiry: '12/25', default: true },
    { id: 2, brand: 'Mastercard', last4: '8888', expiry: '08/26', default: false }
  ],
  invoices: [
    { id: 1, date: 'Jan 15, 2024', number: '2024-001', amount: '$49.99' },
    { id: 2, date: 'Dec 15, 2023', number: '2023-012', amount: '$49.99' },
    { id: 3, date: 'Nov 15, 2023', number: '2023-011', amount: '$49.99' }
  ]
})

const activeSessions = ref([
  { id: 1, device: 'Desktop', location: 'San Francisco, CA', lastActive: 'Active now', current: true },
  { id: 2, device: 'Mobile', location: 'San Francisco, CA', lastActive: '2 hours ago', current: false },
  { id: 3, device: 'Desktop', location: 'New York, NY', lastActive: '1 day ago', current: false }
])

const privacyData = ref({
  profileVisible: true,
  activityStatus: true,
  analytics: false,
  dataRetention: '2'
})

const scrollToSection = (section: string) => {
  const element = document.querySelector(`[ref="${section}"]`)
  if (element) {
    element.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

const changePassword = () => {
  if (securityData.value.newPassword !== securityData.value.confirmPassword) {
    alert('Passwords do not match')
    return
  }
  console.log('Password change requested')
  // Add API call here
  securityData.value.currentPassword = ''
  securityData.value.newPassword = ''
  securityData.value.confirmPassword = ''
}

const changePlan = () => {
  console.log('Change plan clicked')
  // Navigate to plans page
}

const cancelSubscription = () => {
  if (confirm('Are you sure you want to cancel your subscription?')) {
    console.log('Cancel subscription requested')
    // Add API call here
  }
}

const addPaymentMethod = () => {
  console.log('Add payment method clicked')
  // Open payment method modal
}

const removePaymentMethod = (id: number) => {
  if (confirm('Are you sure you want to remove this payment method?')) {
    billingData.value.paymentMethods = billingData.value.paymentMethods.filter(pm => pm.id !== id)
    console.log('Payment method removed:', id)
  }
}

const downloadInvoice = (id: number) => {
  console.log('Download invoice:', id)
  // Add download logic here
}

const revokeSession = (id: number) => {
  if (confirm('Are you sure you want to revoke this session?')) {
    activeSessions.value = activeSessions.value.filter(s => s.id !== id)
    console.log('Session revoked:', id)
  }
}

const revokeAllSessions = () => {
  if (confirm('Are you sure you want to sign out all other devices?')) {
    activeSessions.value = activeSessions.value.filter(s => s.current)
    console.log('All other sessions revoked')
  }
}

const downloadData = () => {
  console.log('Download data requested')
  // Add API call to generate data export
}

const savePrivacySettings = () => {
  console.log('Privacy settings saved:', privacyData.value)
  // Add API call here
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

/* Smooth scroll */
html {
  scroll-behavior: smooth;
}
</style>