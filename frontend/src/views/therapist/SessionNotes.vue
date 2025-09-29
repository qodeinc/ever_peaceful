<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-3xl font-bold text-gray-900">Session Notes</h2>
        <p class="text-gray-600 mt-1">Document session observations and progress</p>
      </div>
      <div class="flex gap-3">
        <button class="px-5 py-3 bg-white rounded-2xl border-2 border-emerald-200 hover:border-emerald-500 hover:bg-emerald-50 transition-all font-medium">
          <FileText class="w-5 h-5 inline mr-2" />
          Templates
        </button>
        <button class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl hover:shadow-lg transition-all flex items-center gap-2 font-medium">
          <Save class="w-5 h-5" />
          Save Notes
        </button>
      </div>
    </div>

    <div class="grid grid-cols-4 gap-6">
      <div class="col-span-3 space-y-6">
        <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
          <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Client</label>
              <select v-model="selectedClient" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none font-medium">
                <option value="">Select a client...</option>
                <option v-for="client in clients" :key="client.id" :value="client.id">{{ client.name }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Session Date & Time</label>
              <input 
                v-model="sessionDateTime" 
                type="datetime-local" 
                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none font-medium"
              />
            </div>
          </div>

          <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-3">Session Type</label>
            <div class="flex gap-3">
              <button
                v-for="type in sessionTypes"
                :key="type"
                @click="selectedType = type"
                :class="[
                  'px-5 py-2.5 rounded-xl font-medium transition-all',
                  selectedType === type
                    ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg'
                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                ]"
              >
                {{ type }}
              </button>
            </div>
          </div>

          <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Session Notes</label>
            <textarea
              v-model="sessionNotes"
              rows="14"
              placeholder="Document your session observations, client progress, interventions used, and treatment notes..."
              class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none resize-none font-normal leading-relaxed"
            ></textarea>
            <p class="text-xs text-gray-500 mt-2">Auto-saving... Last saved 2 minutes ago</p>
          </div>

          <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-3">Treatment Goals Progress</label>
            <div class="space-y-3">
              <div v-for="goal in treatmentGoals" :key="goal.id" class="flex items-center gap-3 p-4 bg-gradient-to-r from-emerald-50/50 to-teal-50/50 rounded-xl border border-emerald-100">
                <input 
                  type="checkbox" 
                  v-model="goal.discussed" 
                  class="w-5 h-5 text-emerald-600 rounded border-2 border-gray-300"
                />
                <span class="flex-1 text-gray-900 font-medium">{{ goal.text }}</span>
                <select 
                  v-model="goal.progress"
                  class="px-4 py-2 rounded-lg border-2 border-emerald-200 text-sm font-medium outline-none focus:border-emerald-500"
                >
                  <option value="none">No progress</option>
                  <option value="minimal">Minimal progress</option>
                  <option value="some">Some progress</option>
                  <option value="good">Good progress</option>
                  <option value="achieved">Goal achieved</option>
                </select>
              </div>
            </div>
          </div>

          <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-3">Interventions Used</label>
            <div class="flex flex-wrap gap-2">
              <button
                v-for="intervention in interventions"
                :key="intervention"
                @click="toggleIntervention(intervention)"
                :class="[
                  'px-4 py-2 rounded-xl font-medium transition-all text-sm',
                  selectedInterventions.includes(intervention)
                    ? 'bg-emerald-100 text-emerald-700 border-2 border-emerald-300'
                    : 'bg-gray-100 text-gray-700 border-2 border-gray-200 hover:border-gray-300'
                ]"
              >
                {{ intervention }}
              </button>
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Tags</label>
            <div class="flex flex-wrap gap-2">
              <span
                v-for="tag in selectedTags"
                :key="tag"
                class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl text-sm font-medium flex items-center gap-2 border border-emerald-200"
              >
                <Tag class="w-4 h-4" />
                {{ tag }}
                <button @click="removeTag(tag)">
                  <X class="w-4 h-4 hover:text-emerald-900" />
                </button>
              </span>
              <button 
                @click="showTagInput = true"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium flex items-center gap-2 hover:bg-gray-200 transition-colors"
              >
                <Plus class="w-4 h-4" />
                Add Tag
              </button>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Private Notes (Not Shared)</h3>
          <textarea
            v-model="privateNotes"
            rows="4"
            placeholder="Personal observations and reminders that won't be shared in client records..."
            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none resize-none"
          ></textarea>
        </div>
      </div>

      <div class="space-y-6">
        <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
          <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <Clock class="w-5 h-5 text-emerald-600" />
            Session Timer
          </h3>
          <div class="text-center p-6 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl">
            <p class="text-4xl font-bold text-gray-900">45:32</p>
            <p class="text-sm text-gray-600 mt-2">of 50 minutes</p>
          </div>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
          <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <Archive class="w-5 h-5 text-emerald-600" />
            Previous Sessions
          </h3>
          <div class="space-y-2">
            <div
              v-for="session in previousSessions"
              :key="session.id"
              class="p-3 bg-gradient-to-r from-emerald-50/50 to-teal-50/50 rounded-xl cursor-pointer hover:from-emerald-50 hover:to-teal-50 transition-all border border-emerald-100"
            >
              <p class="font-semibold text-gray-900 text-sm">{{ session.date }}</p>
              <p class="text-xs text-gray-600 mt-1">{{ session.type }}</p>
              <button class="text-xs text-emerald-600 hover:text-emerald-700 font-medium mt-2 flex items-center gap-1">
                <Eye class="w-3 h-3" />
                View Notes
              </button>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
          <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <Shield class="w-5 h-5 text-emerald-600" />
            Safety Checklist
          </h3>
          <div class="space-y-3">
            <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer hover:bg-emerald-50 p-2 rounded-lg transition-colors">
              <input type="checkbox" class="w-5 h-5 text-emerald-600 rounded border-2 border-gray-300" />
              <span>No immediate risk concerns</span>
            </label>
            <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer hover:bg-emerald-50 p-2 rounded-lg transition-colors">
              <input type="checkbox" class="w-5 h-5 text-emerald-600 rounded border-2 border-gray-300" />
              <span>Safety plan reviewed</span>
            </label>
            <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer hover:bg-emerald-50 p-2 rounded-lg transition-colors">
              <input type="checkbox" class="w-5 h-5 text-emerald-600 rounded border-2 border-gray-300" />
              <span>Crisis resources provided</span>
            </label>
            <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer hover:bg-emerald-50 p-2 rounded-lg transition-colors">
              <input type="checkbox" class="w-5 h-5 text-emerald-600 rounded border-2 border-gray-300" />
              <span>Next session scheduled</span>
            </label>
          </div>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
          <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <AlertTriangle class="w-5 h-5 text-yellow-600" />
            Risk Indicators
          </h3>
          <div class="space-y-2">
            <button class="w-full px-4 py-2 bg-red-50 text-red-700 rounded-xl hover:bg-red-100 transition-colors text-sm font-medium text-left">
              Report Risk Concern
            </button>
            <button class="w-full px-4 py-2 bg-yellow-50 text-yellow-700 rounded-xl hover:bg-yellow-100 transition-colors text-sm font-medium text-left">
              Update Risk Assessment
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Save, FileText, Clock, Archive, Shield, AlertTriangle, Eye, Tag, Plus, X } from 'lucide-vue-next';

const selectedClient = ref('');
const sessionDateTime = ref('');
const selectedType = ref('Individual');
const sessionNotes = ref('');
const privateNotes = ref('');
const showTagInput = ref(false);

const sessionTypes = ['Individual', 'Group', 'Family', 'Couples'];
const clients = ref([
  { id: 1, name: 'Sarah Johnson' },
  { id: 2, name: 'Michael Chen' },
  { id: 3, name: 'Emma Davis' }
]);

const treatmentGoals = ref([
  { id: 1, text: 'Reduce anxiety symptoms through CBT techniques', discussed: false, progress: 'some' },
  { id: 2, text: 'Improve sleep quality and establish bedtime routine', discussed: false, progress: 'good' },
  { id: 3, text: 'Develop healthy coping mechanisms for stress', discussed: false, progress: 'minimal' }
]);

const interventions = ['CBT', 'DBT', 'EMDR', 'Mindfulness', 'Art Therapy', 'Exposure Therapy', 'Narrative Therapy'];
const selectedInterventions = ref(['CBT', 'Mindfulness']);

const selectedTags = ref(['Anxiety', 'Progress', 'Homework Assigned']);

const previousSessions = ref([
  { id: 1, date: 'Sep 27, 2025', type: 'Individual Therapy' },
  { id: 2, date: 'Sep 20, 2025', type: 'Individual Therapy' },
  { id: 3, date: 'Sep 13, 2025', type: 'Individual Therapy' }
]);

const toggleIntervention = (intervention: string) => {
  const index = selectedInterventions.value.indexOf(intervention);
  if (index > -1) {
    selectedInterventions.value.splice(index, 1);
  } else {
    selectedInterventions.value.push(intervention);
  }
};

const removeTag = (tag: string) => {
  const index = selectedTags.value.indexOf(tag);
  if (index > -1) {
    selectedTags.value.splice(index, 1);
  }
};
</script>