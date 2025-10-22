<template>
  <section
    class="relative min-h-screen flex items-center justify-start text-left overflow-hidden"
  >
    <!-- Background image -->
    <div
      class="absolute inset-0 bg-cover bg-center"
      :style="{ backgroundImage: `url(${heroImage})` }"
    ></div>

    <!-- Full-section backdrop blur with gradient tint -->
    <div
      class="absolute inset-0 backdrop-blur-[6px] bg-gradient-to-br from-teal-900/60 via-black/40 to-emerald-800/60"
    ></div>

    <!-- Decorative circles -->
    <div
      class="absolute -top-10 -right-10 w-24 h-24 bg-teal-400/30 rounded-full blur-2xl animate-pulse"
    ></div>
    <div
      class="absolute -bottom-16 -left-16 w-20 h-20 bg-yellow-400/30 rounded-full blur-2xl animate-pulse delay-1000"
    ></div>

    <!-- Content container -->
    <div class="relative z-10 w-full max-w-5xl px-6 lg:px-16 py-24 text-white">
      <div class="space-y-10 opacity-0 translate-y-10 animate-fadeSlide">
        <!-- Heading -->
        <h1
          class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight tracking-tight"
        >
          Your
          <span class="text-teal-300 block mt-2">Wellness Journey</span>
          <span class="block mt-2">Starts Here</span>
        </h1>

        <!-- Subheading -->
        <p
          class="text-lg sm:text-xl lg:text-2xl text-gray-100/90 leading-relaxed max-w-2xl"
        >
          Connect with licensed therapists, access personalized mental health
          resources, and take the first step toward a healthier, happier you.
        </p>

        <!-- CTA Buttons -->
        <div
          class="flex flex-col sm:flex-row items-center sm:items-start gap-4"
        >
          <button
            class="group px-8 py-4 bg-teal-600 text-white font-semibold text-lg rounded-2xl shadow-xl hover:shadow-2xl hover:bg-teal-700 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center whitespace-nowrap"
          >
            <Calendar class="mr-3 w-5 h-5 flex-shrink-0" />
            <span>Book A Session </span>
            <ArrowRight
              class="ml-3 w-5 h-5 flex-shrink-0 group-hover:translate-x-1 transition-transform duration-300"
            />
          </button>

          <button
            class="group px-8 py-4 bg-white/90 text-gray-800 font-semibold text-lg rounded-2xl border-2 border-gray-200 hover:border-teal-300 hover:bg-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center whitespace-nowrap"
          >
            <Brain class="mr-3 w-5 h-5 flex-shrink-0 text-teal-600" />
            <span>Take A Free Quiz</span>
          </button>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 lg:gap-8 pt-12">
          <div class="flex items-center space-x-3">
            <div
              class="w-12 h-12 bg-teal-100/80 rounded-2xl flex items-center justify-center flex-shrink-0"
            >
              <Users class="w-6 h-6 text-teal-700" />
            </div>
            <div>
              <div class="text-2xl lg:text-3xl font-bold">100+</div>
              <div class="text-sm lg:text-base text-gray-200">
                Licensed Therapists
              </div>
            </div>
          </div>

          <div class="flex items-center space-x-3">
            <div
              class="w-12 h-12 bg-emerald-100/80 rounded-2xl flex items-center justify-center flex-shrink-0"
            >
              <Heart class="w-6 h-6 text-emerald-700" />
            </div>
            <div>
              <div class="text-2xl lg:text-3xl font-bold">1K+</div>
              <div class="text-sm lg:text-base text-gray-200">
                People Helped
              </div>
            </div>
          </div>

          <div class="flex items-center space-x-3">
            <div
              class="w-12 h-12 bg-yellow-100/80 rounded-2xl flex items-center justify-center flex-shrink-0"
            >
              <Star class="w-6 h-6 text-yellow-700 fill-current" />
            </div>
            <div>
              <div class="text-2xl lg:text-3xl font-bold">4.7</div>
              <div class="text-sm lg:text-base text-gray-200">
                Average Rating
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import heroImage from '@/assets/images/hero.jpg'
import { Calendar, ArrowRight, Brain, Users, Heart, Star } from 'lucide-vue-next'
</script>

<style scoped>
@keyframes fadeSlide {
  0% {
    opacity: 0;
    transform: translateY(30px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeSlide {
  animation: fadeSlide 1.2s ease-out forwards;
  animation-delay: 0.2s;
}
</style>
