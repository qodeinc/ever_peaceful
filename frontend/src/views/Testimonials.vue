<template>
  <section class="relative py-20 lg:py-24 bg-gradient-to-br from-teal-50/50 via-white to-emerald-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16 lg:mb-20">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-teal-100 rounded-full mb-6">
          <MessageCircle class="w-8 h-8 text-teal-600" />
        </div>
        <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
          Stories of 
          <span class="text-teal-600">Transformation</span>
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
          Real experiences from people who found their path to wellness through our platform
        </p>
      </div>
      <!-- Testimonial Carousel -->
      <div class="relative max-w-6xl mx-auto">
        <div class="overflow-hidden rounded-3xl">
          <div 
            class="flex transition-transform duration-700 ease-in-out"
            :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
          >
            <div 
              v-for="(testimonial, index) in testimonials" 
              :key="index"
              class="w-full flex-shrink-0 px-4"
            >
              <div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-12 mx-4 relative">
                <!-- Quote Icon -->
                <div class="absolute -top-6 left-8 mt-6">
                  <div class="w-12 h-12 bg-teal-600 rounded-full flex items-center justify-center">
                    <Quote class="w-6 h-6 text-white" />
                  </div>
                </div>
                
                <!-- Rating Stars -->
                <div class="flex items-center justify-center lg:justify-start mb-6 pt-4">
                  <div class="flex items-center space-x-1">
                    <Star v-for="n in 5" :key="n" class="w-5 h-5 text-yellow-400 fill-current" />
                  </div>
                </div>
                <!-- Testimonial Content -->
                <blockquote class="text-lg lg:text-xl text-gray-700 leading-relaxed mb-8 text-center lg:text-left">
                  "{{ testimonial.content }}"
                </blockquote>
                <!-- User Info -->
                <div class="flex items-center justify-center lg:justify-start space-x-4">
                  <div class="text-center lg:text-left">
                    <div class="font-semibold text-gray-900 text-lg">{{ testimonial.name }}</div>
                    <div class="text-gray-600">{{ testimonial.role }}</div>
                    <div class="text-sm text-teal-600 font-medium">{{ testimonial.therapy_type }}</div>
                  </div>
                </div>
                <!-- Background Pattern -->
                <div class="absolute top-0 right-0 w-32 h-32 opacity-5">
                  <div class="w-full h-full bg-teal-600 rounded-full transform rotate-45"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Navigation Arrows -->
        <button 
          @click="previousSlide"
          class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white shadow-lg rounded-full flex items-center justify-center text-gray-600 hover:text-teal-600 hover:shadow-xl transition-all duration-300 transform hover:scale-105"
        >
          <ChevronLeft class="w-6 h-6" />
        </button>
        
        <button 
          @click="nextSlide"
          class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white shadow-lg rounded-full flex items-center justify-center text-gray-600 hover:text-teal-600 hover:shadow-xl transition-all duration-300 transform hover:scale-105"
        >
          <ChevronRight class="w-6 h-6" />
        </button>
      </div>
      <!-- Dots Indicator -->
      <div class="flex items-center justify-center mt-10 space-x-3">
        <button
          v-for="(testimonial, index) in testimonials"
          :key="index"
          @click="currentSlide = index"
          class="w-3 h-3 rounded-full transition-all duration-300"
          :class="currentSlide === index ? 'bg-teal-600 w-8' : 'bg-gray-300 hover:bg-gray-400'"
        ></button>
      </div>
    </div>
    <!-- Floating Elements -->
    <div class="absolute top-20 left-10 w-16 h-16 bg-emerald-500 rounded-full opacity-10 animate-pulse"></div>
    <div class="absolute bottom-20 right-10 w-12 h-12 bg-teal-500 rounded-full opacity-15 animate-pulse" style="animation-delay: 1.5s;"></div>
    <div class="absolute top-1/2 left-5 w-8 h-8 bg-yellow-400 rounded-full opacity-20 animate-pulse" style="animation-delay: 3s;"></div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { 
  MessageCircle, 
  Quote, 
  Star, 
  ChevronLeft, 
  ChevronRight 
} from 'lucide-vue-next'

const currentSlide = ref(0)
let autoSlideInterval: number | null = null

const testimonials = [
  {
    content: "This platform made mental health support accessible for me as a busy professional in Nairobi. The flexibility to schedule sessions around my work and the understanding of Kenyan cultural contexts by my therapist has been incredible.",
    name: "Grace Wanjiku",
    role: "Banking Executive",
    therapy_type: "Work Stress & Burnout"
  },
  {
    content: "Growing up, mental health was rarely discussed in our community. This platform changed that for me. My therapist helped me navigate family expectations while staying true to my own path. The confidentiality gave me the safe space I needed.",
    name: "Kevin Ochieng",
    role: "University Student",
    therapy_type: "Identity & Cultural Pressures"
  },
  {
    content: "As an entrepreneur dealing with the pressures of building a business in Kenya, I found the support I needed here. My therapist understands the unique challenges we face and helped me develop resilience strategies that work in our context.",
    name: "Mercy Njeri",
    role: "Tech Entrepreneur",
    therapy_type: "Anxiety & Business Stress"
  },
  {
    content: "The 24/7 availability was crucial during my difficult period. Having someone to talk to who understands both professional therapy and the Kenyan way of life made all the difference. I'm grateful for this service.",
    name: "Samuel Kiprop",
    role: "Teacher",
    therapy_type: "Depression & Life Transitions"
  }
]

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % testimonials.length
}

const previousSlide = () => {
  currentSlide.value = currentSlide.value === 0 ? testimonials.length - 1 : currentSlide.value - 1
}

const startAutoSlide = () => {
  autoSlideInterval = setInterval(nextSlide, 5000)
}

const stopAutoSlide = () => {
  if (autoSlideInterval) {
    clearInterval(autoSlideInterval)
  }
}

onMounted(() => {
  startAutoSlide()
})

onUnmounted(() => {
  stopAutoSlide()
})
</script>

<style scoped>
/* Enhanced animations and hover effects */
.testimonial-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.testimonial-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Custom scrollbar for better aesthetics */
::-webkit-scrollbar {
  display: none;
}

/* Smooth slide transitions */
.slide-enter-active,
.slide-leave-active {
  transition: all 0.7s ease-in-out;
}

.slide-enter-from {
  transform: translateX(100%);
  opacity: 0;
}

.slide-leave-to {
  transform: translateX(-100%);
  opacity: 0;
}
</style>