<template>
  <section class="relative py-20 lg:py-28 bg-gradient-to-br from-teal-50/30 via-white to-emerald-50/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16 lg:mb-20">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-full mb-6">
          <BookOpen class="w-8 h-8 text-teal-600" />
        </div>
        <h2 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
          Mental Health
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-600 block lg:inline"> Resources</span>
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
          Free tools, guides, and resources to support your mental wellness journey in Kenya
        </p>
      </div>

      <!-- Quick Tools Section -->
      <div class="mb-16 lg:mb-20">
        <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-10 text-center">
          Quick Assessment Tools
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
          <div 
            v-for="(tool, index) in assessmentTools" 
            :key="index"
            class="group bg-white rounded-3xl p-6 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 cursor-pointer border border-gray-100 hover:border-teal-200"
          >
            <div class="text-center">
              <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300">
                <component :is="tool.icon" class="w-8 h-8 text-white" />
              </div>
              <h4 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-teal-700 transition-colors duration-300">
                {{ tool.title }}
              </h4>
              <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                {{ tool.description }}
              </p>
              <div class="flex items-center justify-center text-teal-600 font-semibold text-sm">
                <Clock class="w-4 h-4 mr-2" />
                {{ tool.duration }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Crisis Resources Section -->
      <div class="mb-16 lg:mb-20">
        <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-3xl p-8 lg:p-12 border border-red-100">
          <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
              <AlertCircle class="w-8 h-8 text-red-600" />
            </div>
            <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">
              Crisis & Emergency Support
            </h3>
            <p class="text-gray-700 text-lg max-w-3xl mx-auto">
              If you're in immediate danger or having thoughts of self-harm, reach out for help right away
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="(crisis, index) in crisisResources" 
              :key="index"
              class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300"
            >
              <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                  <component :is="crisis.icon" class="w-5 h-5 text-red-600" />
                </div>
                <h4 class="font-bold text-gray-900">{{ crisis.title }}</h4>
              </div>
              <p class="text-gray-600 text-sm mb-3">{{ crisis.description }}</p>
              <a 
                :href="crisis.contact" 
                class="inline-flex items-center text-red-600 font-semibold hover:text-red-700 transition-colors duration-300"
              >
                <Phone class="w-4 h-4 mr-2" />
                {{ crisis.number }}
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Resources Library -->
      <div class="mb-16 lg:mb-20">
        <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-10 text-center">
          Free Resources & Guides
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div 
            v-for="(resource, index) in resources" 
            :key="index"
            class="group bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:border-teal-200"
          >
            <div class="h-48 bg-gradient-to-br" :class="resource.gradient">
              <div class="h-full flex items-center justify-center">
                <component :is="resource.icon" class="w-16 h-16 text-white opacity-80" />
              </div>
            </div>
            <div class="p-6">
              <div class="flex items-center justify-between mb-3">
                <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-sm font-medium">
                  {{ resource.category }}
                </span>
                <span class="text-gray-500 text-sm">{{ resource.type }}</span>
              </div>
              <h4 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-teal-700 transition-colors duration-300">
                {{ resource.title }}
              </h4>
              <p class="text-gray-600 mb-4 leading-relaxed">
                {{ resource.description }}
              </p>
              <button class="w-full bg-gray-50 hover:bg-teal-50 text-gray-700 hover:text-teal-700 font-semibold py-3 px-4 rounded-2xl transition-all duration-300 flex items-center justify-center group-hover:bg-teal-600 group-hover:text-white">
                <Download class="w-4 h-4 mr-2" />
                {{ resource.action }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Community Support -->
      <div class="text-center">
        <div class="bg-gradient-to-r from-teal-600 to-emerald-600 rounded-3xl p-8 lg:p-12 relative overflow-hidden">
          <div class="relative z-10">
            <h3 class="text-3xl lg:text-4xl font-bold text-white mb-6">
              Join Our Community
            </h3>
            <p class="text-teal-100 text-lg mb-8 max-w-2xl mx-auto">
              Connect with others on their mental health journey, share experiences, and find support in our safe, moderated community spaces
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
              <button class="group px-8 py-4 bg-white text-teal-600 font-semibold text-lg rounded-2xl hover:bg-gray-50 transform hover:-translate-y-1 transition-all duration-300 flex items-center">
                <Users class="mr-3 w-5 h-5 group-hover:scale-110 transition-transform duration-300" />
                Join Support Groups
              </button>
              
              <button class="group px-8 py-4 border-2 border-white/30 text-white font-semibold text-lg rounded-2xl hover:bg-white/10 transform hover:-translate-y-1 transition-all duration-300 flex items-center">
                <MessageCircle class="mr-3 w-5 h-5" />
                Community Forums
              </button>
            </div>
          </div>
          
          <!-- Background Pattern -->
          <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-32 h-32 border border-white/30 rounded-full"></div>
            <div class="absolute bottom-10 right-10 w-24 h-24 border border-white/30 rounded-full"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Floating Background Elements -->
    <div class="absolute top-20 right-10 w-20 h-20 bg-teal-500 rounded-full opacity-5 animate-pulse"></div>
    <div class="absolute bottom-32 left-8 w-16 h-16 bg-emerald-500 rounded-full opacity-10 animate-pulse" style="animation-delay: 2s;"></div>
    <div class="absolute top-1/3 left-5 w-12 h-12 bg-blue-500 rounded-full opacity-8 animate-pulse" style="animation-delay: 4s;"></div>
  </section>
</template>

<script setup lang="ts">
import { 
  BookOpen,
  Clock,
  AlertCircle,
  Phone,
  Download,
  Users,
  MessageCircle,
  Brain,
  Heart,
  Zap,
  Shield,
  Headphones,
  FileText,
  Video,
  Smartphone
} from 'lucide-vue-next'

const assessmentTools = [
  {
    title: "Stress Level Check",
    description: "Quick assessment to understand your current stress levels and get personalized recommendations",
    duration: "3 mins",
    icon: Zap
  },
  {
    title: "Mood Tracker",
    description: "Daily mood monitoring tool to help identify patterns and triggers in your mental health",
    duration: "2 mins",
    icon: Heart
  },
  {
    title: "Anxiety Screening",
    description: "Evidence-based screening tool to assess anxiety levels and understand if you might benefit from support",
    duration: "5 mins",
    icon: Brain
  },
  {
    title: "Wellness Check",
    description: "Comprehensive wellbeing assessment covering different aspects of your mental health",
    duration: "7 mins",
    icon: Shield
  }
]

const crisisResources = [
  {
    title: "Emergency Services",
    description: "For immediate medical or safety emergencies",
    number: "999",
    contact: "tel:999",
    icon: AlertCircle
  },
  {
    title: "Befrienders Kenya",
    description: "24/7 suicide prevention and emotional support hotline",
    number: "+254 722 178 177",
    contact: "tel:+254722178177",
    icon: Phone
  },
  {
    title: "Crisis Text Line",
    description: "Free, confidential crisis support via text messaging",
    number: "Text 'HELLO' to 741741",
    contact: "sms:741741",
    icon: MessageCircle
  }
]

const resources = [
  {
    title: "Managing Anxiety in Kenya",
    description: "Practical guide addressing anxiety within Kenyan cultural contexts, including family and work pressures",
    category: "Guide",
    type: "PDF",
    action: "Download Guide",
    icon: FileText,
    gradient: "from-blue-500 to-blue-600"
  },
  {
    title: "Mindfulness Meditations",
    description: "Guided meditation sessions in English and Swahili for stress relief and mental clarity",
    category: "Audio",
    type: "MP3",
    action: "Listen Now",
    icon: Headphones,
    gradient: "from-purple-500 to-purple-600"
  },
  {
    title: "Student Mental Health Toolkit",
    description: "Resources specifically for Kenyan university students dealing with academic pressure and transitions",
    category: "Toolkit",
    type: "PDF Bundle",
    action: "Download Toolkit",
    icon: Brain,
    gradient: "from-emerald-500 to-emerald-600"
  },
  {
    title: "Workplace Wellness Video Series",
    description: "Video tutorials on managing work stress, burnout prevention, and maintaining work-life balance",
    category: "Video",
    type: "MP4 Series",
    action: "Watch Videos",
    icon: Video,
    gradient: "from-orange-500 to-orange-600"
  },
  {
    title: "Depression Self-Help Workbook",
    description: "Interactive workbook with exercises and strategies for managing depression symptoms",
    category: "Workbook",
    type: "PDF",
    action: "Download Workbook",
    icon: Heart,
    gradient: "from-pink-500 to-pink-600"
  },
  {
    title: "Mental Health First Aid",
    description: "Learn how to recognize and respond to mental health crises in your family and community",
    category: "Training",
    type: "Mobile App",
    action: "Get App",
    icon: Smartphone,
    gradient: "from-teal-500 to-teal-600"
  }
]
</script>

<style scoped>
/* Gradient text animation */
@keyframes gradientShift {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

.bg-clip-text {
  background-size: 200% 200%;
  animation: gradientShift 6s ease infinite;
}

/* Card hover effects */
.group:hover {
  transform: translateY(-8px);
}

/* Resource card gradient backgrounds */
.from-blue-500 {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
}

.from-purple-500 {
  background: linear-gradient(135deg, #8b5cf6, #7c3aed);
}

.from-emerald-500 {
  background: linear-gradient(135deg, #10b981, #059669);
}

.from-orange-500 {
  background: linear-gradient(135deg, #f97316, #ea580c);
}

.from-pink-500 {
  background: linear-gradient(135deg, #ec4899, #db2777);
}

.from-teal-500 {
  background: linear-gradient(135deg, #14b8a6, #0d9488);
}

/* Smooth transitions */
* {
  transition: all 0.3s ease;
}
</style>