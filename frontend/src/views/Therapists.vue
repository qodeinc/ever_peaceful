<template>
  <section class="relative py-20 lg:py-28 bg-gradient-to-br from-gray-50 via-white to-teal-50/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16 lg:mb-20">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-full mb-6">
          <Users class="w-8 h-8 text-teal-600" />
        </div>
        <h2 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
          Meet Our
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-600 block lg:inline"> Kenyan Experts</span>
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
          Licensed therapists who understand Kenyan culture, languages, and the unique challenges we face as a community
        </p>
      </div>
      <!-- Therapists Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
        <!-- Therapist Card 1 -->
        <div 
          v-for="(therapist, index) in therapists" 
          :key="index"
          class="group relative bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden"
        >
          <!-- Card Background Gradient -->
          <div class="absolute inset-0 bg-gradient-to-br from-white via-gray-50/50 to-teal-50/30 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          
          <div class="relative p-8">
            <!-- Profile Image -->
            <div class="relative mb-6">
              <div class="w-24 h-24 mx-auto rounded-full overflow-hidden ring-4 ring-gray-100 group-hover:ring-teal-200 transition-all duration-300">
                <img 
                  :src="therapist.image" 
                  :alt="therapist.name"
                  class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                />
              </div>
              
              <!-- Status Badge -->
              <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                <div class="flex items-center space-x-1 bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                  <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                  <span>Available</span>
                </div>
              </div>
            </div>
            <!-- Therapist Info -->
            <div class="text-center mb-6">
              <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-teal-700 transition-colors duration-300">
                {{ therapist.name }}
              </h3>
              <p class="text-teal-600 font-semibold mb-2">{{ therapist.title }}</p>
              <p class="text-gray-600 text-sm">{{ therapist.credentials }}</p>
            </div>
            <!-- Languages -->
            <div class="mb-6">
              <div class="flex flex-wrap justify-center gap-2">
                <span 
                  v-for="language in therapist.languages" 
                  :key="language"
                  class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium group-hover:bg-teal-100 group-hover:text-teal-700 transition-colors duration-300"
                >
                  {{ language }}
                </span>
              </div>
            </div>
            <!-- Specializations -->
            <div class="mb-6">
              <h4 class="text-sm font-semibold text-gray-900 mb-3 text-center">Specializations</h4>
              <ul class="space-y-2">
                <li 
                  v-for="specialization in therapist.specializations" 
                  :key="specialization"
                  class="flex items-center justify-center text-sm text-gray-600"
                >
                  <CheckCircle class="w-4 h-4 text-teal-500 mr-2 flex-shrink-0" />
                  {{ specialization }}
                </li>
              </ul>
            </div>
            <!-- Rating & Experience -->
            <div class="flex items-center justify-between mb-6 pt-4 border-t border-gray-100">
              <div class="text-center">
                <div class="flex items-center justify-center space-x-1 mb-1">
                  <Star v-for="n in 5" :key="n" class="w-4 h-4 text-yellow-400 fill-current" />
                </div>
                <p class="text-sm text-gray-600">{{ therapist.rating }} Rating</p>
              </div>
              <div class="text-center">
                <p class="text-lg font-bold text-gray-900">{{ therapist.experience }}+</p>
                <p class="text-sm text-gray-600">Years Experience</p>
              </div>
            </div>
            <!-- Book Session Button -->
            <button class="w-full group-inner bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 px-6 rounded-2xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
              <Calendar class="w-4 h-4 mr-2" />
              Book Session
              <ArrowRight class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-300" />
            </button>
            <!-- Decorative Element -->
            <div class="absolute top-4 right-4 w-16 h-16 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-full opacity-0 group-hover:opacity-50 transition-opacity duration-500 -z-10"></div>
          </div>
        </div>
      </div>
      <!-- View More Button -->
      <div class="text-center mt-12 lg:mt-16">
        <button class="group px-8 py-4 bg-teal-600 hover:bg-teal-700 text-white font-semibold text-lg rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 flex items-center mx-auto">
          <Users class="mr-3 w-5 h-5 group-hover:scale-110 transition-transform duration-300" />
          View All Therapists
          <ArrowRight class="ml-3 w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" />
        </button>
        <p class="text-gray-600 mt-4">Browse our full directory of 100+ licensed therapists</p>
      </div>
      <!-- Bottom Section -->
      <div class="mt-16 lg:mt-20 text-center">
        <div class="bg-gradient-to-r from-teal-600 to-emerald-600 rounded-3xl p-8 lg:p-12 relative overflow-hidden">
          <div class="relative z-10">
            <h3 class="text-3xl lg:text-4xl font-bold text-white mb-4">
              Can't Find the Right Therapist?
            </h3>
            <p class="text-teal-100 text-lg mb-8 max-w-2xl mx-auto">
              Our matching system will connect you with the perfect therapist based on your needs, language preference, and cultural background
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
              <button class="group px-8 py-4 bg-white text-teal-600 font-semibold text-lg rounded-2xl hover:bg-gray-50 transform hover:-translate-y-1 transition-all duration-300 flex items-center">
                <Search class="mr-3 w-5 h-5 group-hover:scale-110 transition-transform duration-300" />
                Find My Therapist
              </button>
              
              <button class="group px-8 py-4 border-2 border-white/30 text-white font-semibold text-lg rounded-2xl hover:bg-white/10 transform hover:-translate-y-1 transition-all duration-300 flex items-center">
                <MessageCircle class="mr-3 w-5 h-5" />
                Chat with Support
              </button>
            </div>
          </div>
          
          <!-- Background Pattern -->
          <div class="absolute inset-0 opacity-10">
            <div class="absolute top-5 left-5 w-20 h-20 border border-white/30 rounded-full"></div>
            <div class="absolute bottom-5 right-5 w-16 h-16 border border-white/30 rounded-full"></div>
          </div>
        </div>
      </div>
    </div>
    <!-- Floating Background Elements -->
    <div class="absolute top-20 right-10 w-20 h-20 bg-teal-500 rounded-full opacity-5 animate-pulse"></div>
    <div class="absolute bottom-32 left-8 w-16 h-16 bg-emerald-500 rounded-full opacity-10 animate-pulse" style="animation-delay: 2s;"></div>
    <div class="absolute top-1/3 right-5 w-12 h-12 bg-blue-500 rounded-full opacity-8 animate-pulse" style="animation-delay: 4s;"></div>
  </section>
</template>

<script setup lang="ts">
import { 
  Users,
  CheckCircle,
  Star,
  Calendar,
  ArrowRight,
  Search,
  MessageCircle
} from 'lucide-vue-next'

// Import local avatar images
import av1 from '@/assets/images/av1.jpg'
import av2 from '@/assets/images/av2.jpg'
import av3 from '@/assets/images/av3.jpg'
import av4 from '@/assets/images/av4.jpg'
import av5 from '@/assets/images/av5.jpg'
import av6 from '@/assets/images/av6.jpg'
import av7 from '@/assets/images/av7.jpg'

const therapists = [
  {
    name: "Dr. Amina Hassan",
    title: "Clinical Psychologist",
    credentials: "PhD Psychology, University of Nairobi",
    image: av4,
    languages: ["English", "Swahili", "Arabic"],
    specializations: [
      "Trauma & PTSD",
      "Cultural Identity Issues", 
      "Women's Mental Health"
    ],
    rating: "4.9",
    experience: "8"
  },
  {
    name: "Dr. Peter Kimani",
    title: "Licensed Counselor",
    credentials: "MA Counseling Psychology, Kenyatta University",
    image: av2,
    languages: ["English", "Swahili", "Kikuyu"],
    specializations: [
      "Anxiety & Depression",
      "Work Stress Management",
      "Family Therapy"
    ],
    rating: "4.8",
    experience: "6"
  },
  {
    name: "Dr. Grace Mwangi",
    title: "Cognitive Behavioral Therapist",
    credentials: "MSc Clinical Psychology, Strathmore University",
    image: av3,
    languages: ["English", "Swahili", "Kikuyu"],
    specializations: [
      "CBT & DBT",
      "Youth Mental Health",
      "Academic Pressure"
    ],
    rating: "4.9",
    experience: "5"
  },
  {
    name: "Dr. James Ochieng",
    title: "Marriage & Family Therapist",
    credentials: "PhD Family Therapy, Moi University",
    image: av1,
    languages: ["English", "Swahili", "Luo"],
    specializations: [
      "Couples Counseling",
      "Relationship Issues",
      "Communication Skills"
    ],
    rating: "4.8",
    experience: "10"
  },
  {
    name: "Dr. Sarah Wambui",
    title: "Child & Adolescent Psychologist",
    credentials: "MSc Developmental Psychology, USIU-Africa",
    image: av7,
    languages: ["English", "Swahili", "Kikuyu"],
    specializations: [
      "Child Psychology",
      "Learning Disabilities",
      "Behavioral Issues"
    ],
    rating: "4.9",
    experience: "7"
  },
  {
    name: "Dr. Michael Kiplagat",
    title: "Addiction Counselor",
    credentials: "MA Addiction Studies, Africa Nazarene University",
    image: av6,
    languages: ["English", "Swahili", "Kalenjin"],
    specializations: [
      "Substance Abuse",
      "Addiction Recovery",
      "Support Groups"
    ],
    rating: "4.7",
    experience: "9"
  }
]
</script>

<style scoped>
/* Enhanced card hover effects */
.group:hover {
  transform: translateY(-8px);
}

/* Profile image zoom effect */
.group:hover img {
  transform: scale(1.1);
}

/* Button inner group for nested hover effects */
.group-inner:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

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

/* Language tags hover effect */
.group:hover span {
  transform: scale(1.05);
}

/* Status badge pulse effect */
@keyframes statusPulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: statusPulse 2s ease-in-out infinite;
}
</style>