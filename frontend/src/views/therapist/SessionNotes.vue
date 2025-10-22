<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
      <div>
        <h2 class="text-3xl font-bold text-gray-900">Session Notes</h2>
        <p class="text-gray-600 mt-1">Document observations, progress, and interventions securely</p>
      </div>
      <div class="flex gap-3">
        <button 
          @click="openTemplates"
          class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors flex items-center gap-2 text-sm font-medium"
        >
          <FileText class="w-4 h-4" />
          Templates
        </button>
        <button 
          @click="saveSession"
          :disabled="!isFormValid"
          class="px-6 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 disabled:bg-gray-400 transition-colors flex items-center gap-2 text-sm font-medium"
        >
          <Save class="w-4 h-4" />
          Save Notes
        </button>
      </div>
    </div>

    <!-- Main Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Primary Form Column -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Client & Session Details -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Session Details</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
              <div class="relative">
                <select 
                  v-model="selectedClient" 
                  @change="loadClientData"
                  class="w-full px-4 py-3 pr-10 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none font-medium appearance-none bg-white"
                  required
                >
                  <option value="">Select client...</option>
                  <option v-for="client in clients" :key="client.id" :value="client.id">
                    {{ client.name }}
                  </option>
                </select>
                <ChevronDown class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Date & Time</label>
              <input 
                v-model="sessionDateTime" 
                type="datetime-local" 
                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none font-medium"
                required
              />
            </div>
          </div>
          
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Session Type</label>
            <div class="flex flex-wrap gap-2">
              <button
                v-for="type in sessionTypes"
                :key="type"
                @click="selectedType = type"
                :class="[
                  'px-4 py-2 rounded-xl font-medium transition-all text-sm',
                  selectedType === type
                    ? 'bg-emerald-600 text-white shadow-sm'
                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                ]"
              >
                {{ type }}
              </button>
            </div>
          </div>
        </div>

        <!-- Core Notes Section -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
          <div class="flex items-center justify-between mb-4">
            <label class="text-lg font-semibold text-gray-900">Session Notes</label>
            <div class="text-xs text-gray-500">
              Auto-saving... <span v-if="lastSaved">{{ lastSaved }}</span>
            </div>
          </div>
          <textarea
            v-model="sessionNotes"
            rows="12"
            placeholder="Capture key observations, client insights, therapeutic interventions, and progress indicators. Use structured format for clarity: 
            
Objectives Discussed:
• [Goal 1]
• [Goal 2]

Interventions Applied:
• [Technique 1]
• [Technique 2]

Client Response:
• [Observations]
• [Homework assigned]

Next Steps:
• [Action items]"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none resize-none text-sm leading-relaxed font-light"
            @input="autoSave"
          ></textarea>
        </div>

        <!-- Treatment Goals & Interventions -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Treatment Goals & Interventions</h3>
            <button @click="resetProgress" class="text-sm text-gray-500 hover:text-gray-700">Reset All</button>
          </div>
          
          <!-- Goals -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">Progress on Goals</label>
            <div class="space-y-3">
              <div v-for="goal in treatmentGoals" :key="goal.id" class="flex items-center gap-4 p-3 bg-gray-50 rounded-xl">
                <input 
                  type="checkbox" 
                  v-model="goal.discussed" 
                  :id="`goal-${goal.id}`"
                  class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500"
                />
                <label :for="`goal-${goal.id}`" class="flex-1 text-sm text-gray-900 cursor-pointer">
                  {{ goal.text }}
                </label>
                <select 
                  v-model="goal.progress"
                  class="px-3 py-1.5 rounded-lg border border-gray-200 text-sm font-medium focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100"
                >
                  <option value="none">No progress</option>
                  <option value="minimal">Minimal</option>
                  <option value="some">Some</option>
                  <option value="good">Good</option>
                  <option value="achieved">Achieved</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Interventions -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Interventions Applied</label>
            <div class="flex flex-wrap gap-2">
              <button
                v-for="intervention in interventions"
                :key="intervention"
                @click="toggleIntervention(intervention)"
                :class="[
                  'px-3 py-1.5 rounded-lg font-medium transition-all text-xs',
                  selectedInterventions.includes(intervention)
                    ? 'bg-emerald-100 text-emerald-700 border border-emerald-300'
                    : 'bg-gray-100 text-gray-700 border border-gray-200 hover:border-gray-300'
                ]"
              >
                {{ intervention }}
              </button>
            </div>
          </div>
        </div>

        <!-- Tags & Attachments -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tags -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-3">Tags</label>
              <div class="flex flex-wrap gap-2 mb-3">
                <span
                  v-for="tag in selectedTags"
                  :key="tag"
                  class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium gap-1 border border-emerald-200"
                >
                  {{ tag }}
                  <button @click="removeTag(tag)" class="ml-1 hover:text-emerald-900">
                    <X class="w-3 h-3" />
                  </button>
                </span>
              </div>
              <div v-if="!showTagInput" class="flex items-center gap-2">
                <button 
                  @click="showTagInput = true"
                  class="text-xs text-gray-500 hover:text-gray-700 flex items-center gap-1"
                >
                  <Plus class="w-3 h-3" />
                  Add tag
                </button>
              </div>
              <div v-else class="flex gap-2">
                <input 
                  v-model="newTag" 
                  @keyup.enter="addTag"
                  placeholder="Enter tag..."
                  class="flex-1 px-3 py-1.5 rounded-lg border border-gray-200 text-xs focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100"
                />
                <button @click="addTag" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs hover:bg-emerald-700">Add</button>
                <button @click="showTagInput = false" class="px-3 py-1.5 bg-gray-200 text-gray-600 rounded-lg text-xs hover:bg-gray-300">Cancel</button>
              </div>
            </div>

            <!-- Attachments -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-3">Attachments</label>
              <div class="space-y-2">
                <div v-for="attachment in attachments" :key="attachment.id" class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                  <div class="flex items-center gap-2">
                    <div v-if="attachment.type === 'pdf'" class="w-6 h-6 bg-red-100 rounded flex items-center justify-center">
                      <FileText class="w-3 h-3 text-red-600" />
                    </div>
                    <div v-else-if="attachment.type === 'image'" class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center">
                      <Image class="w-3 h-3 text-blue-600" />
                    </div>
                    <span class="text-xs text-gray-900 truncate flex-1">{{ attachment.name }}</span>
                  </div>
                  <button @click="removeAttachment(attachment.id)" class="text-gray-400 hover:text-gray-600">
                    <X class="w-3 h-3" />
                  </button>
                </div>
                <button @click="uploadAttachment" class="w-full py-2 px-4 border-2 border-dashed border-gray-300 rounded-lg text-sm text-gray-600 hover:border-emerald-300 hover:bg-emerald-50 transition-colors flex items-center justify-center gap-2">
                  <Upload class="w-4 h-4" />
                  Add Attachment
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Private Notes -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Private Notes</h3>
          <textarea
            v-model="privateNotes"
            rows="6"
            placeholder="Personal reflections, supervisor notes, or reminders (not visible to client)..."
            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none resize-none text-sm"
            @input="autoSavePrivate"
          ></textarea>
        </div>

        <!-- Session Timer -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 text-center">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Session Timer</h3>
          <div class="text-4xl font-bold text-gray-900 mb-2">{{ formattedTimer }}</div>
          <p class="text-sm text-gray-600 mb-4">of {{ sessionDuration }} minutes</p>
          <div class="space-x-2">
            <button @click="startTimer" :disabled="isTimerRunning" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm hover:bg-emerald-700 disabled:bg-gray-400">
              Start
            </button>
            <button @click="stopTimer" :disabled="!isTimerRunning" class="px-4 py-2 bg-red-600 text-white rounded-xl text-sm hover:bg-red-700 disabled:bg-gray-400">
              Stop
            </button>
          </div>
        </div>

        <!-- Previous Sessions -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Previous Sessions</h3>
          <div class="space-y-2 max-h-48 overflow-y-auto">
            <button
              v-for="session in previousSessions"
              :key="session.id"
              @click="loadPreviousSession(session.id)"
              class="w-full text-left p-3 hover:bg-gray-50 rounded-lg transition-colors text-sm"
            >
              <div class="flex items-center justify-between mb-1">
                <span class="font-medium text-gray-900">{{ session.date }}</span>
                <span class="text-xs text-gray-500">{{ session.duration }} min</span>
              </div>
              <p class="text-xs text-gray-600">{{ session.type }}</p>
            </button>
          </div>
        </div>

        <!-- Safety Checklist -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Safety Checklist</h3>
          <div class="space-y-4">
            <div v-for="(item, key) in safetyChecklist" :key="key" class="border-b border-gray-100 pb-3 last:border-b-0">
              <div class="flex items-center justify-between mb-2">
                <label class="flex items-center gap-3 text-sm font-medium text-gray-700 cursor-pointer">
                  <input 
                    type="checkbox" 
                    v-model="safetyChecklist[key].checked" 
                    :id="`safety-${key}`"
                    class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500"
                  />
                  <span :for="`safety-${key}`">{{ safetyChecklist[key].title }}</span>
                </label>
                <span class="text-xs text-gray-500" :class="safetyChecklist[key].checked ? 'text-emerald-600' : 'text-gray-500'">
                  {{ safetyChecklist[key].checked ? '✓ Completed' : 'Pending' }}
                </span>
              </div>
              <div v-if="safetyChecklist[key].details" class="ml-7 text-xs text-gray-600 pl-1 border-l border-gray-200">
                {{ safetyChecklist[key].details }}
              </div>
            </div>
          </div>
          <button 
            @click="saveSafetyChecklist" 
            class="mt-4 w-full py-2 px-4 bg-emerald-600 text-white rounded-xl text-sm hover:bg-emerald-700 transition-colors font-medium"
          >
            Update Checklist
          </button>
        </div>

        <!-- Risk Indicators -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Risk Indicators</h3>
          <div class="space-y-3">
            <button 
              @click="reportRisk"
              class="w-full py-2 px-4 bg-red-50 text-red-700 rounded-xl hover:bg-red-100 transition-colors text-sm font-medium text-left flex items-center gap-2"
            >
              <AlertTriangle class="w-4 h-4" />
              Report Risk Concern
            </button>
            <button 
              @click="updateRiskAssessment"
              class="w-full py-2 px-4 bg-yellow-50 text-yellow-700 rounded-xl hover:bg-yellow-100 transition-colors text-sm font-medium text-left flex items-center gap-2"
            >
              <AlertTriangle class="w-4 h-4" />
              Update Assessment
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Auto-save Status -->
    <div v-if="isAutoSaving" class="fixed bottom-4 right-4 bg-emerald-600 text-white px-4 py-2 rounded-xl shadow-lg animate-fade-in">
      Saving...
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, nextTick } from 'vue'
import { 
  Save, FileText, Clock, Archive, Shield, AlertTriangle, Eye, Tag, Plus, X, 
  ChevronDown, Upload, Image 
} from 'lucide-vue-next'

const selectedClient = ref('')
const sessionDateTime = ref('')
const selectedType = ref('Individual')
const sessionNotes = ref('')
const privateNotes = ref('')
const showTagInput = ref(false)
const newTag = ref('')
const isAutoSaving = ref(false)
const lastSaved = ref('')
const attachments = ref([])

const sessionTypes = ['Individual', 'Group', 'Family', 'Couples', 'Crisis']
const clients = ref([
  { id: 1, name: 'Wanjiku Mwangi' },
  { id: 2, name: 'Ochieng Kiprop' },
  { id: 3, name: 'Muthoni Njoroge' },
  { id: 4, name: 'Achieng Omondi' }
])

const treatmentGoals = ref([
  { id: 1, text: 'Reduce anxiety through CBT techniques', discussed: false, progress: 'some' },
  { id: 2, text: 'Improve sleep hygiene routine', discussed: false, progress: 'good' },
  { id: 3, text: 'Build healthy coping mechanisms', discussed: false, progress: 'minimal' }
])

const interventions = ['CBT', 'DBT', 'EMDR', 'Mindfulness', 'Exposure', 'Narrative']
const selectedInterventions = ref(['CBT', 'Mindfulness'])

const selectedTags = ref(['Anxiety', 'Progress'])

const previousSessions = ref([
  { id: 1, date: 'Sep 27, 2025', type: 'Individual', duration: 50 },
  { id: 2, date: 'Sep 20, 2025', type: 'Individual', duration: 50 },
  { id: 3, date: 'Sep 13, 2025', type: 'Individual', duration: 50 }
])

const safetyChecklist = ref({
  risk: {
    title: 'No Immediate Risk Concerns',
    checked: false,
    details: 'Assess for suicidal ideation, self-harm, harm to others, or acute crisis indicators. No immediate danger identified.'
  },
  safetyPlan: {
    title: 'Safety Plan Reviewed',
    checked: false,
    details: 'Client verbalized understanding of safety plan steps, including emergency contacts and coping strategies.'
  },
  crisisResources: {
    title: 'Crisis Resources Provided',
    checked: false,
    details: 'Provided/reinforced access to hotlines (e.g., Befrienders Kenya: 0722 178 177), emergency services, and support networks.'
  },
  nextSession: {
    title: 'Next Session Scheduled',
    checked: false,
    details: 'Follow-up appointment confirmed with client. Client understands how to reschedule if needed.'
  }
})

const sessionDuration = ref(50)
const timerInterval = ref(null)
const isTimerRunning = ref(false)
const startTime = ref(0)
const elapsedTime = ref(0)

const formattedTimer = computed(() => {
  const totalSeconds = elapsedTime.value
  const minutes = Math.floor(totalSeconds / 60)
  const seconds = totalSeconds % 60
  return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
})

const isFormValid = computed(() => {
  return selectedClient.value && sessionDateTime.value && sessionNotes.value.trim()
})

const toggleIntervention = (intervention) => {
  const index = selectedInterventions.value.indexOf(intervention)
  if (index > -1) {
    selectedInterventions.value.splice(index, 1)
  } else {
    selectedInterventions.value.push(intervention)
  }
}

const removeTag = (tag) => {
  const index = selectedTags.value.indexOf(tag)
  if (index > -1) {
    selectedTags.value.splice(index, 1)
  }
}

const addTag = () => {
  if (newTag.value.trim() && !selectedTags.value.includes(newTag.value.trim())) {
    selectedTags.value.push(newTag.value.trim())
  }
  newTag.value = ''
  showTagInput.value = false
}

const removeAttachment = (id) => {
  attachments.value = attachments.value.filter(a => a.id !== id)
}

const uploadAttachment = () => {
  // Trigger file input in real app
  console.log('Upload attachment')
}

const autoSave = () => {
  if (sessionNotes.value.trim()) {
    isAutoSaving.value = true
    setTimeout(() => {
      lastSaved.value = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
      isAutoSaving.value = false
    }, 1000)
  }
}

const autoSavePrivate = () => {
  // Similar auto-save logic
}

const openTemplates = () => {
  console.log('Open templates modal')
}

const saveSession = async () => {
  if (!isFormValid.value) return
  try {
    console.log('Saving session:', {
      client: selectedClient.value,
      dateTime: sessionDateTime.value,
      type: selectedType.value,
      notes: sessionNotes.value,
      goals: treatmentGoals.value,
      interventions: selectedInterventions.value,
      tags: selectedTags.value,
      privateNotes: privateNotes.value
    })
    // API call in real app
    alert('Session saved successfully!')
  } catch (error) {
    console.error('Save error:', error)
  }
}

const loadClientData = () => {
  if (selectedClient.value) {
    // Load client-specific goals, etc.
    console.log(`Loading data for client ${selectedClient.value}`)
  }
}

const loadPreviousSession = (id) => {
  console.log(`Loading previous session ${id}`)
  // Populate form with previous data
}

const saveSafetyChecklist = () => {
  console.log('Saving safety checklist:', safetyChecklist.value)
}

const reportRisk = () => {
  console.log('Reporting risk')
}

const updateRiskAssessment = () => {
  console.log('Updating risk assessment')
}

const resetProgress = () => {
  treatmentGoals.value.forEach(goal => {
    goal.discussed = false
    goal.progress = 'none'
  })
}

const startTimer = () => {
  if (!isTimerRunning.value) {
    isTimerRunning.value = true
    startTime.value = Date.now()
    timerInterval.value = setInterval(() => {
      elapsedTime.value = Math.floor((Date.now() - startTime.value) / 1000)
    }, 1000)
  }
}

const stopTimer = () => {
  if (isTimerRunning.value) {
    isTimerRunning.value = false
    clearInterval(timerInterval.value)
    // Log session duration
    console.log(`Session duration: ${elapsedTime.value} seconds`)
  }
}

onMounted(() => {
  sessionDateTime.value = new Date().toISOString().slice(0, 16)
})
</script>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(5px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fade-in 0.3s ease-out;
}

::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}
</style>