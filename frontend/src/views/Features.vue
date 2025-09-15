<template>
  <section class="relative py-20 lg:py-28 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16 lg:mb-24">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-full mb-6">
          <Sparkles class="w-8 h-8 text-teal-600" />
        </div>
        <h2 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
          Why Choose
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-600 block lg:inline"> Our Platform</span>
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
          We're committed to providing you with the highest quality mental health care through innovative technology and compassionate professionals
        </p>
      </div>

      <!-- Features Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
        <!-- Feature 1: Licensed Therapists -->
        <div class="group relative">
          <div class="absolute inset-0 bg-gradient-to-br from-teal-500/5 to-emerald-500/5 rounded-3xl transform group-hover:scale-105 transition-all duration-500"></div>
          <div class="relative bg-white rounded-3xl p-8 lg:p-10 shadow-xl group-hover:shadow-2xl transition-all duration-500 border border-gray-100 group-hover:border-teal-200">
            <!-- Icon -->
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
              <GraduationCap class="w-8 h-8 text-white" />
            </div>
            
            <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-teal-700 transition-colors duration-300">
              Licensed & Verified Therapists
            </h3>
            
            <p class="text-gray-600 leading-relaxed mb-6">
              All our therapists are licensed professionals with extensive training and experience. We rigorously verify credentials to ensure you receive care from qualified experts.
            </p>
            
            <div class="flex items-center text-teal-600 font-semibold group-hover:text-teal-700">
              <CheckCircle class="w-5 h-5 mr-2" />
              <span>100+ Licensed Professionals</span>
            </div>
            
            <!-- Decorative element -->
            <div class="absolute top-6 right-6 w-20 h-20 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-full opacity-50 -z-10 group-hover:opacity-70 transition-opacity duration-300"></div>
          </div>
        </div>

        <!-- Feature 2: Confidential & Secure -->
        <div class="group relative">
          <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-blue-500/5 rounded-3xl transform group-hover:scale-105 transition-all duration-500"></div>
          <div class="relative bg-white rounded-3xl p-8 lg:p-10 shadow-xl group-hover:shadow-2xl transition-all duration-500 border border-gray-100 group-hover:border-emerald-200">
            <!-- Icon -->
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
              <Shield class="w-8 h-8 text-white" />
            </div>
            
            <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-emerald-700 transition-colors duration-300">
              Confidential & Secure
            </h3>
            
            <p class="text-gray-600 leading-relaxed mb-6">
              Your privacy is our priority. We use bank-level encryption and comply with HIPAA regulations to ensure your personal information and sessions remain completely confidential.
            </p>
            
            <div class="flex items-center text-emerald-600 font-semibold group-hover:text-emerald-700">
              <Lock class="w-5 h-5 mr-2" />
              <span>HIPAA Compliant Security</span>
            </div>
            
            <!-- Decorative element -->
            <div class="absolute top-6 right-6 w-20 h-20 bg-gradient-to-br from-emerald-100 to-blue-100 rounded-full opacity-50 -z-10 group-hover:opacity-70 transition-opacity duration-300"></div>
          </div>
        </div>

        <!-- Feature 3: Available 24/7 -->
        <div class="group relative">
          <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-indigo-500/5 rounded-3xl transform group-hover:scale-105 transition-all duration-500"></div>
          <div class="relative bg-white rounded-3xl p-8 lg:p-10 shadow-xl group-hover:shadow-2xl transition-all duration-500 border border-gray-100 group-hover:border-blue-200">
            <!-- Icon -->
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
              <Clock class="w-8 h-8 text-white" />
            </div>
            
            <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-blue-700 transition-colors duration-300">
              Available 24/7
            </h3>
            
            <p class="text-gray-600 leading-relaxed mb-6">
              Mental health doesn't follow a schedule. Our platform provides round-the-clock access to resources, crisis support, and the ability to message your therapist anytime.
            </p>
            
            <div class="flex items-center text-blue-600 font-semibold group-hover:text-blue-700">
              <Zap class="w-5 h-5 mr-2" />
              <span>Always Here When You Need Us</span>
            </div>
            
            <!-- Decorative element -->
            <div class="absolute top-6 right-6 w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full opacity-50 -z-10 group-hover:opacity-70 transition-opacity duration-300"></div>
          </div>
        </div>

        <!-- Feature 4: Evidence-Based Care -->
        <div class="group relative">
          <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 rounded-3xl transform group-hover:scale-105 transition-all duration-500"></div>
          <div class="relative bg-white rounded-3xl p-8 lg:p-10 shadow-xl group-hover:shadow-2xl transition-all duration-500 border border-gray-100 group-hover:border-purple-200">
            <!-- Icon -->
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
              <BookOpen class="w-8 h-8 text-white" />
            </div>
            
            <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-purple-700 transition-colors duration-300">
              Evidence-Based Care
            </h3>
            
            <p class="text-gray-600 leading-relaxed mb-6">
              Our treatment approaches are grounded in scientific research and proven methodologies like CBT, DBT, and mindfulness-based therapies for effective, lasting results.
            </p>
            
            <div class="flex items-center text-purple-600 font-semibold group-hover:text-purple-700">
              <Award class="w-5 h-5 mr-2" />
              <span>Research-Backed Methods</span>
            </div>
            
            <!-- Decorative element -->
            <div class="absolute top-6 right-6 w-20 h-20 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full opacity-50 -z-10 group-hover:opacity-70 transition-opacity duration-300"></div>
          </div>
        </div>

        <!-- Feature 5: Transparent Pricing -->
        <div class="group relative md:col-span-2 lg:col-span-1">
          <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-red-500/5 rounded-3xl transform group-hover:scale-105 transition-all duration-500"></div>
          <div class="relative bg-white rounded-3xl p-8 lg:p-10 shadow-xl group-hover:shadow-2xl transition-all duration-500 border border-gray-100 group-hover:border-orange-200">
            <!-- Icon -->
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
              <DollarSign class="w-8 h-8 text-white" />
            </div>
            
            <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-orange-700 transition-colors duration-300">
              Transparent Pricing
            </h3>
            
            <p class="text-gray-600 leading-relaxed mb-6">
              No hidden fees or surprise charges. Our clear, upfront pricing makes quality mental health care accessible and affordable for everyone.
            </p>
            
            <div class="flex items-center text-orange-600 font-semibold group-hover:text-orange-700">
              <TrendingUp class="w-5 h-5 mr-2" />
              <span>No Hidden Costs</span>
            </div>
            
            <!-- Decorative element -->
            <div class="absolute top-6 right-6 w-20 h-20 bg-gradient-to-br from-orange-100 to-red-100 rounded-full opacity-50 -z-10 group-hover:opacity-70 transition-opacity duration-300"></div>
          </div>
        </div>
      </div>

      <!-- Bottom CTA Section -->
      <div class="mt-20 lg:mt-24 text-center">
        <div class="bg-gradient-to-br from-teal-600 to-emerald-600 rounded-3xl p-10 lg:p-16 relative overflow-hidden">
          <div class="relative z-10">
            <h3 class="text-3xl lg:text-4xl font-bold text-white mb-6">
              Ready to Start Your Journey?
            </h3>
            <p class="text-teal-100 text-lg lg:text-xl mb-8 max-w-2xl mx-auto">
              Join thousands of people who have found their path to better mental health with our platform
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
              <button class="group px-8 py-4 bg-white text-teal-600 font-semibold text-lg rounded-2xl hover:bg-gray-50 transform hover:-translate-y-1 transition-all duration-300 flex items-center">
                <Rocket class="mr-3 w-5 h-5 group-hover:scale-110 transition-transform duration-300" />
                Get Started Today
                <ArrowRight class="ml-3 w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" />
              </button>
              
              <button class="group px-8 py-4 border-2 border-white/30 text-white font-semibold text-lg rounded-2xl hover:bg-white/10 transform hover:-translate-y-1 transition-all duration-300 flex items-center">
                <Phone class="mr-3 w-5 h-5" />
                Talk to an Expert
              </button>
            </div>
          </div>
          
          <!-- Background Pattern -->
          <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-32 h-32 border border-white/30 rounded-full"></div>
            <div class="absolute bottom-10 right-10 w-24 h-24 border border-white/30 rounded-full"></div>
            <div class="absolute top-1/2 right-20 w-16 h-16 border border-white/30 rounded-full"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Floating Background Elements -->
    <div class="absolute top-20 left-5 w-20 h-20 bg-teal-500 rounded-full opacity-5 animate-pulse"></div>
    <div class="absolute bottom-32 right-8 w-16 h-16 bg-emerald-500 rounded-full opacity-10 animate-pulse" style="animation-delay: 2s;"></div>
    <div class="absolute top-1/2 left-8 w-12 h-12 bg-blue-500 rounded-full opacity-8 animate-pulse" style="animation-delay: 4s;"></div>
  </section>
</template>

<script setup lang="ts">
import { 
  Sparkles,
  GraduationCap,
  Shield,
  Clock,
  BookOpen,
  DollarSign,
  CheckCircle,
  Lock,
  Zap,
  Award,
  TrendingUp,
  Rocket,
  ArrowRight,
  Phone
} from 'lucide-vue-next'
</script>

<style scoped>
/* Enhanced hover animations */
.group:hover .absolute {
  transform: scale(1.05) rotate(5deg);
}

/* Gradient text animations */
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

/* Icon animation */
.group:hover div[class*="bg-gradient"] {
  transform: scale(1.1) rotate(5deg);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

/* Smooth transitions for all interactive elements */
* {
  transition: all 0.3s ease;
}
</style>