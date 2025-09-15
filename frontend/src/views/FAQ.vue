<template>
  <section class="relative py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16 lg:mb-20">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-full mb-6">
          <HelpCircle class="w-8 h-8 text-teal-600" />
        </div>
        <h2 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
          Frequently Asked
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-600 block lg:inline"> Questions</span>
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
          Get answers to common questions about mental health support in Kenya and how our platform works
        </p>
      </div>

      <!-- FAQ Content -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
        <!-- Left Column - General Questions -->
        <div class="space-y-6">
          <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
              <MessageSquare class="w-6 h-6 text-teal-600 mr-3" />
              General Questions
            </h3>
          </div>
          
          <div 
            v-for="(faq, index) in generalFaqs" 
            :key="index"
            class="group"
          >
            <button
              @click="toggleFaq('general', index)"
              class="w-full text-left bg-gray-50 hover:bg-gray-100 rounded-2xl p-6 transition-all duration-300 group-hover:shadow-md"
            >
              <div class="flex items-center justify-between">
                <h4 class="text-lg font-semibold text-gray-900 pr-4 group-hover:text-teal-700 transition-colors duration-300">
                  {{ faq.question }}
                </h4>
                <div 
                  class="flex-shrink-0 w-8 h-8 bg-teal-600 rounded-full flex items-center justify-center transform transition-transform duration-300"
                  :class="activeFaq.category === 'general' && activeFaq.index === index ? 'rotate-45' : ''"
                >
                  <Plus class="w-4 h-4 text-white" />
                </div>
              </div>
              
              <div 
                class="overflow-hidden transition-all duration-500 ease-in-out"
                :class="activeFaq.category === 'general' && activeFaq.index === index ? 'max-h-96 mt-6' : 'max-h-0'"
              >
                <p class="text-gray-600 leading-relaxed">
                  {{ faq.answer }}
                </p>
              </div>
            </button>
          </div>
        </div>

        <!-- Right Column - Platform & Cultural Questions -->
        <div class="space-y-6">
          <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
              <Globe class="w-6 h-6 text-emerald-600 mr-3" />
              Platform & Cultural
            </h3>
          </div>
          
          <div 
            v-for="(faq, index) in platformFaqs" 
            :key="index"
            class="group"
          >
            <button
              @click="toggleFaq('platform', index)"
              class="w-full text-left bg-gray-50 hover:bg-gray-100 rounded-2xl p-6 transition-all duration-300 group-hover:shadow-md"
            >
              <div class="flex items-center justify-between">
                <h4 class="text-lg font-semibold text-gray-900 pr-4 group-hover:text-emerald-700 transition-colors duration-300">
                  {{ faq.question }}
                </h4>
                <div 
                  class="flex-shrink-0 w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center transform transition-transform duration-300"
                  :class="activeFaq.category === 'platform' && activeFaq.index === index ? 'rotate-45' : ''"
                >
                  <Plus class="w-4 h-4 text-white" />
                </div>
              </div>
              
              <div 
                class="overflow-hidden transition-all duration-500 ease-in-out"
                :class="activeFaq.category === 'platform' && activeFaq.index === index ? 'max-h-96 mt-6' : 'max-h-0'"
              >
                <p class="text-gray-600 leading-relaxed">
                  {{ faq.answer }}
                </p>
              </div>
            </button>
          </div>
        </div>
      </div>

      <!-- Bottom CTA -->
      <div class="mt-16 lg:mt-20 text-center">
        <div class="bg-gradient-to-br from-gray-50 to-teal-50/50 rounded-3xl p-8 lg:p-12 border border-gray-100">
          <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">
            Still Have Questions?
          </h3>
          <p class="text-gray-600 text-lg mb-8 max-w-2xl mx-auto">
            Our support team is here to help you understand how we can support your mental health journey
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <button class="group px-8 py-4 bg-teal-600 hover:bg-teal-700 text-white font-semibold text-lg rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center">
              <MessageCircle class="mr-3 w-5 h-5 group-hover:scale-110 transition-transform duration-300" />
              Chat with Support
            </button>
            
            <button class="group px-8 py-4 border-2 border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white font-semibold text-lg rounded-2xl transform hover:-translate-y-1 transition-all duration-300 flex items-center">
              <Phone class="mr-3 w-5 h-5" />
              Call Us: +254 700 123 456
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Floating Background Elements -->
    <div class="absolute top-20 left-10 w-16 h-16 bg-teal-500 rounded-full opacity-5 animate-pulse"></div>
    <div class="absolute bottom-32 right-8 w-12 h-12 bg-emerald-500 rounded-full opacity-10 animate-pulse" style="animation-delay: 2s;"></div>
    <div class="absolute top-1/2 right-5 w-8 h-8 bg-blue-500 rounded-full opacity-8 animate-pulse" style="animation-delay: 4s;"></div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { 
  HelpCircle,
  MessageSquare,
  Globe,
  Plus,
  MessageCircle,
  Phone
} from 'lucide-vue-next'

const activeFaq = ref({ category: '', index: -1 })

const toggleFaq = (category: string, index: number) => {
  if (activeFaq.value.category === category && activeFaq.value.index === index) {
    activeFaq.value = { category: '', index: -1 }
  } else {
    activeFaq.value = { category, index }
  }
}

const generalFaqs = [
  {
    question: "Is online therapy effective?",
    answer: "Yes, numerous studies show that online therapy is as effective as in-person therapy for many mental health conditions. Our licensed therapists use evidence-based approaches that have proven successful in helping thousands of people across Kenya improve their mental wellbeing."
  },
  {
    question: "How do I know if I need therapy?",
    answer: "Consider therapy if you're experiencing persistent sadness, anxiety, relationship difficulties, work stress, or major life changes. In Kenyan culture, seeking help shows strength, not weakness. Our free assessment can help you understand if therapy would be beneficial."
  },
  {
    question: "What types of therapy do you offer?",
    answer: "We offer various evidence-based therapies including Cognitive Behavioral Therapy (CBT), Dialectical Behavior Therapy (DBT), mindfulness-based therapy, and culturally-adapted approaches that respect Kenyan values and traditions."
  },
  {
    question: "Is my information kept confidential?",
    answer: "Absolutely. We follow strict HIPAA compliance and Kenyan data protection laws. All conversations with your therapist are completely confidential, encrypted, and secure. We never share your personal information without your explicit consent."
  },
  {
    question: "What if I have a mental health emergency?",
    answer: "For immediate crisis support, contact our 24/7 crisis line, visit your nearest hospital, or call Kenya's National Police Service at 999. Our platform also provides instant access to crisis resources and emergency contacts."
  }
]

const platformFaqs = [
  {
    question: "Will my therapist understand Kenyan culture?",
    answer: "Yes! All our therapists are either Kenyan or have extensive experience working within Kenyan cultural contexts. They understand family dynamics, cultural expectations, and the unique challenges faced by people living in Kenya."
  },
  {
    question: "Can I have sessions in Swahili or other local languages?",
    answer: "Absolutely. Many of our therapists are multilingual and can conduct sessions in Swahili, English, Kikuyu, Luo, Kalenjin, and other local languages. You can specify your language preference when booking."
  },
  {
    question: "How much do sessions cost?",
    answer: "Our sessions start from KES 2,500 per session with flexible payment options including M-Pesa. We offer student discounts, sliding scale fees based on income, and accept several Kenyan insurance plans. Your first consultation is free."
  },
  {
    question: "How does online therapy work technically?",
    answer: "Sessions are conducted through our secure video platform accessible via computer, tablet, or smartphone. You'll need a stable internet connection and a private space. We also offer phone sessions if video isn't possible."
  },
  {
    question: "Can I switch therapists if needed?",
    answer: "Yes, finding the right therapist is crucial for your success. If you feel your current therapist isn't the right fit, we'll help you find another one at no extra cost. Your wellbeing is our priority."
  },
  {
    question: "Do you work with teenagers and families?",
    answer: "Yes, we have specialists who work with adolescents (with parental consent for those under 18) and offer family therapy sessions. We understand the unique dynamics of Kenyan families and cultural considerations."
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

/* FAQ smooth animations */
.group:hover {
  transform: translateY(-2px);
}

/* Plus icon rotation */
.rotate-45 {
  transform: rotate(45deg);
}

/* Smooth height transitions for FAQ answers */
.max-h-0 {
  max-height: 0;
}

.max-h-96 {
  max-height: 24rem;
}
</style>