<template>
  <section class="relative min-h-screen bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col lg:flex-row items-center justify-between min-h-screen py-12 lg:py-16">
        
        <!-- Right side - Prominent Image (First on mobile) -->
        <div class="w-full lg:w-1/2 mb-12 lg:mb-0 lg:order-2">
          <div class="relative w-full max-w-xl lg:max-w-2xl mx-auto lg:ml-auto">
            <!-- Decorative circles behind the image -->
            <div class="absolute -top-8 -right-8 w-20 h-20 bg-teal-500 rounded-full opacity-20 animate-pulse -z-10"></div>
            <div class="absolute -bottom-10 -left-10 w-16 h-16 bg-emerald-500 rounded-full opacity-30 animate-pulse -z-10" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 -left-16 w-12 h-12 bg-yellow-400 rounded-full opacity-25 animate-pulse -z-10" style="animation-delay: 2s;"></div>
            <!-- Main image -->
            <img 
              src="@/assets/images/hero.jpg" 
              alt="Mental Health and Wellness" 
              class="w-full h-auto rounded-3xl shadow-2xl"
            />
          </div>
        </div>
        
        <!-- Left side - Content (Second on mobile) -->
        <div class="w-full lg:w-1/2 lg:pr-16 xl:pr-20 text-center lg:text-left lg:order-1">
          <div class="space-y-8 lg:space-y-10">
            <!-- Main heading -->
            <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold text-gray-900 leading-tight tracking-tight">
              Your
              <span class="text-teal-600 block mt-2">
                Wellness Journey
              </span>
              <span class="block mt-2">Starts Here</span>
            </h1>
            
            <!-- Subheading -->
            <p class="text-lg sm:text-xl lg:text-2xl text-gray-600 leading-relaxed max-w-2xl mx-auto lg:mx-0">
              Connect with licensed therapists, access personalized mental health resources, and take the first step toward a healthier, happier you.
            </p>
            
            <!-- CTA Buttons -->
            <div class="space-y-4 sm:space-y-0 sm:space-x-0 lg:space-x-4 sm:flex sm:flex-col lg:flex-row">
              <button class="w-full sm:w-full lg:w-auto group px-8 py-4 bg-teal-600 text-white font-semibold text-lg rounded-2xl shadow-xl hover:shadow-2xl hover:bg-teal-700 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center whitespace-nowrap">
                <Calendar class="mr-3 w-5 h-5 flex-shrink-0" />
                <span>Book A Session </span>
                <ArrowRight class="ml-3 w-5 h-5 flex-shrink-0 group-hover:translate-x-1 transition-transform duration-300" />
              </button>
              
              <button class="w-full sm:w-full lg:w-auto group px-8 py-4 bg-white text-gray-700 font-semibold text-lg rounded-2xl border-2 border-gray-200 hover:border-teal-300 hover:bg-gray-50 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center whitespace-nowrap">
                <Brain class="mr-3 w-5 h-5 flex-shrink-0 text-teal-600" />
                <span>Take A Free Quiz</span>
              </button>
            </div>
            
            <!-- Stats with Icons -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 lg:gap-8 pt-8 lg:pt-12">
              <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-2 sm:space-y-0 sm:space-x-3">
                <div class="w-12 h-12 bg-teal-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                  <Users class="w-6 h-6 text-teal-600" />
                </div>
                <div class="text-center sm:text-left">
                  <div class="text-2xl lg:text-3xl font-bold text-gray-900">100+</div>
                  <div class="text-sm lg:text-base text-gray-600">Licensed Therapists</div>
                </div>
              </div>
              
              <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-2 sm:space-y-0 sm:space-x-3">
                <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                  <Heart class="w-6 h-6 text-emerald-600" />
                </div>
                <div class="text-center sm:text-left">
                  <div class="text-2xl lg:text-3xl font-bold text-gray-900">1K+</div>
                  <div class="text-sm lg:text-base text-gray-600">People Helped</div>
                </div>
              </div>
              
              <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-2 sm:space-y-0 sm:space-x-3">
                <div class="w-12 h-12 bg-yellow-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                  <Star class="w-6 h-6 text-yellow-600 fill-current" />
                </div>
                <div class="text-center sm:text-left">
                  <div class="text-2xl lg:text-3xl font-bold text-gray-900">4.7</div>
                  <div class="text-sm lg:text-base text-gray-600">Average Rating</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Subtle background pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-teal-50/30 via-transparent to-emerald-50/30 pointer-events-none"></div>
  </section>
</template>

<script setup lang="ts">
import { 
  Calendar, 
  ArrowRight, 
  Brain, 
  Users, 
  Heart, 
  Star 
} from 'lucide-vue-next'
// World-class hero component for mental health platform
// Clean, professional design with prominent image placement
</script>

<style scoped>
/* World-class hero styling with smooth animations */
@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-10px);
  }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}

/* Enhanced button hover effects */
button {
  position: relative;
  overflow: hidden;
}

button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s;
}

button:hover::before {
  left: 100%;
}
</style>