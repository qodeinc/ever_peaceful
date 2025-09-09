<template>
  <section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-primary-50 via-white to-cyan-50">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
      <!-- Floating shapes -->
      <div class="absolute top-20 left-10 w-72 h-72 bg-primary-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse-soft"></div>
      <div class="absolute top-40 right-10 w-64 h-64 bg-cyan-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse-soft" style="animation-delay: 2s;"></div>
      <div class="absolute bottom-20 left-1/3 w-80 h-80 bg-primary-100 rounded-full mix-blend-multiply filter blur-xl opacity-40 animate-pulse-soft" style="animation-delay: 4s;"></div>
      
      <!-- Grid pattern -->
      <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
    </div>
    
    <!-- Main Content -->
    <div class="container relative z-10">
      <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        <!-- Text Content -->
        <div class="text-center lg:text-left space-y-8">
          <!-- Badge -->
          <div class="inline-flex items-center px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-medium animate-fade-in-down">
            <span class="w-2 h-2 bg-primary-500 rounded-full mr-2 animate-pulse"></span>
            Professional Mental Health Support
          </div>
          
          <!-- Main Headline -->
          <h1 class="font-display font-bold leading-tight animate-fade-in-up" style="animation-delay: 0.2s;">
            <span class="block text-gray-900">Your Journey to</span>
            <span class="block text-gradient">Mental Wellness</span>
            <span class="block text-gray-900">Starts Here</span>
          </h1>
          
          <!-- Subheading -->
          <p class="text-xl text-gray-600 max-w-2xl mx-auto lg:mx-0 leading-relaxed animate-fade-in-up" style="animation-delay: 0.4s;">
            Connect with licensed therapists, access personalized therapy sessions, and take control of your mental health journey in a safe, supportive environment.
          </p>
          
          <!-- Key Benefits -->
          <div class="flex flex-wrap justify-center lg:justify-start gap-6 text-sm text-gray-600 animate-fade-in-up" style="animation-delay: 0.6s;">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-primary-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              Licensed Professionals
            </div>
            <div class="flex items-center">
              <svg class="w-5 h-5 text-primary-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              100% Confidential
            </div>
            <div class="flex items-center">
              <svg class="w-5 h-5 text-primary-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              24/7 Support
            </div>
          </div>
          
          <!-- CTA Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start animate-fade-in-up" style="animation-delay: 0.8s;">
            <button @click="scrollToContact" class="btn btn-primary btn-lg group">
              Start Your Journey
              <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </button>
            <button @click="openVideo" class="btn btn-secondary btn-lg group">
              <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
              </svg>
              Watch Our Story
              <span class="ml-2 text-sm text-gray-500 group-hover:text-gray-700 transition-colors">(2 min)</span>
            </button>
          </div>
          
          <!-- Social Proof -->
          <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-6 pt-6 animate-fade-in-up" style="animation-delay: 1s;">
            <div class="text-center sm:text-left">
              <div class="text-2xl font-bold text-gray-900">10,000+</div>
              <div class="text-sm text-gray-600">People Helped</div>
            </div>
            <div class="hidden sm:block w-px h-12 bg-gray-200"></div>
            <div class="text-center sm:text-left">
              <div class="text-2xl font-bold text-gray-900">4.9/5</div>
              <div class="text-sm text-gray-600">Client Rating</div>
            </div>
            <div class="hidden sm:block w-px h-12 bg-gray-200"></div>
            <div class="text-center sm:text-left">
              <div class="text-2xl font-bold text-gray-900">500+</div>
              <div class="text-sm text-gray-600">Licensed Therapists</div>
            </div>
          </div>
        </div>
        
        <!-- Hero Image/Illustration -->
        <div class="relative animate-fade-in" style="animation-delay: 0.5s;">
          <div class="relative">
            <!-- Main illustration placeholder -->
            <div class="aspect-square max-w-lg mx-auto">
              <div class="w-full h-full bg-gradient-to-br from-primary-100 to-cyan-100 rounded-3xl flex items-center justify-center relative overflow-hidden shadow-strong">
                <!-- Therapy session illustration -->
                <div class="absolute inset-8">
                  <div class="w-full h-full bg-white rounded-2xl shadow-medium p-8 flex flex-col items-center justify-center">
                    <!-- Two people talking illustration -->
                    <div class="flex items-center space-x-6 mb-6">
                      <!-- Therapist figure -->
                      <div class="w-16 h-16 bg-gradient-to-br from-primary-300 to-primary-400 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                      </div>
                      <!-- Connection line -->
                      <div class="flex-1 h-px bg-gradient-to-r from-primary-200 to-cyan-200 relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-primary-400 to-cyan-400 animate-pulse opacity-50"></div>
                      </div>
                      <!-- Client figure -->
                      <div class="w-16 h-16 bg-gradient-to-br from-cyan-300 to-cyan-400 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                      </div>
                    </div>
                    <!-- Supportive text -->
                    <div class="text-center">
                      <div class="text-lg font-semibold text-gray-700 mb-2">Safe & Supportive</div>
                      <div class="text-sm text-gray-500">Professional Therapy Sessions</div>
                    </div>
                    <!-- Floating elements -->
                    <div class="absolute top-4 right-4 w-3 h-3 bg-primary-400 rounded-full animate-bounce-light"></div>
                    <div class="absolute bottom-4 left-4 w-2 h-2 bg-cyan-400 rounded-full animate-bounce-light" style="animation-delay: 1s;"></div>
                  </div>
                </div>
                
                <!-- Floating badges -->
                <div class="absolute -top-4 -left-4 bg-white rounded-xl shadow-medium p-3 animate-float">
                  <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                    <span class="text-sm font-medium text-gray-700">Available Now</span>
                  </div>
                </div>
                
                <div class="absolute -bottom-4 -right-4 bg-white rounded-xl shadow-medium p-3 animate-float" style="animation-delay: 2s;">
                  <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">4.9★ Rated</span>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Decorative elements -->
            <div class="absolute -z-10 top-8 -left-8 w-24 h-24 bg-primary-200 rounded-full opacity-60 animate-pulse-soft"></div>
            <div class="absolute -z-10 -bottom-8 -right-8 w-32 h-32 bg-cyan-200 rounded-full opacity-40 animate-pulse-soft" style="animation-delay: 3s;"></div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
      <button @click="scrollToFeatures" class="text-primary-400 hover:text-primary-600 transition-colors p-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
      </button>
    </div>
  </section>
</template>

<script>
export default {
  name: 'Hero',
  methods: {
    scrollToContact() {
      const contactSection = document.querySelector('#contact')
      if (contactSection) {
        contactSection.scrollIntoView({ behavior: 'smooth' })
      }
    },
    scrollToFeatures() {
      const featuresSection = document.querySelector('#features')
      if (featuresSection) {
        featuresSection.scrollIntoView({ behavior: 'smooth' })
      }
    },
    openVideo() {
      // This would open a modal with an introductory video
      // For now, we'll just scroll to about section
      const aboutSection = document.querySelector('#about')
      if (aboutSection) {
        aboutSection.scrollIntoView({ behavior: 'smooth' })
      }
    }
  }
}
</script>

<style scoped>
.bg-grid-pattern {
  background-image: 
    linear-gradient(rgba(6, 182, 212, 0.1) 1px, transparent 1px),
    linear-gradient(90deg, rgba(6, 182, 212, 0.1) 1px, transparent 1px);
  background-size: 20px 20px;
}

@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-10px);
  }
}

@keyframes bounceLight {
  0%, 100% {
    transform: translateY(-2px);
  }
  50% {
    transform: translateY(2px);
  }
}

@keyframes pulseSoft {
  0%, 100% {
    opacity: 0.3;
    transform: scale(1);
  }
  50% {
    opacity: 0.5;
    transform: scale(1.05);
  }
}
</style>