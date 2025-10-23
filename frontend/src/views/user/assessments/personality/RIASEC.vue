<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-20">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">
          The RIASEC Career Interest Test
        </h1>
        <p class="text-lg text-gray-500 mb-8 max-w-2xl">
          Discover which career pathway aligns with your interests and personality. This assessment helps identify your career type across six key dimensions.
        </p>
        <div class="text-sm text-gray-400">42 questions · 8–10 minutes</div>
      </div>

      <!-- Instructions -->
      <div class="mb-16 pb-16 border-b border-gray-200">
        <h3 class="text-sm uppercase tracking-wider text-gray-500 mb-6">
          Instructions
        </h3>
        <p class="text-base text-gray-600 leading-relaxed mb-4">
          Read each statement carefully. If you agree with the statement, select "Yes". If you disagree or it doesn't apply to you, select "No". There are no wrong answers—answer honestly based on your true interests and preferences.
        </p>
      </div>

      <!-- Progress Bar -->
      <div class="mb-16">
        <div class="flex items-center gap-2">
          <div
            v-for="i in 42"
            :key="i"
            :class="[
              'h-1 flex-1 transition-all duration-500',
              answeredQuestions >= i ? 'bg-emerald-600' : 'bg-gray-200'
            ]"
          />
        </div>
        <div class="text-sm text-gray-500 mt-3">
          Question {{ Math.min(answeredQuestions + 1, 42) }} of 42
        </div>
      </div>

      <!-- Questions -->
      <form @submit.prevent="calculateResults" class="space-y-24">
        <div
          v-for="(question, index) in questions"
          :key="index"
          :ref="el => setQuestionRef(index, el)"
          class="scroll-mt-32"
        >
          <div class="mb-8">
            <div class="text-sm text-gray-400 mb-3">
              Question {{ index + 1 }}
            </div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">
              {{ question.text }}
            </h2>
            <p class="text-gray-500">Do you agree with this statement?</p>
          </div>

          <!-- Options -->
          <div class="grid grid-cols-2 gap-4">
            <label
              :class="[
                'group block p-8 border-2 cursor-pointer text-center transition-all duration-200 rounded-lg',
                answers[index] === true
                  ? 'border-emerald-600 bg-emerald-50/50'
                  : 'border-gray-200 hover:border-gray-400'
              ]"
            >
              <input
                type="radio"
                class="sr-only"
                :name="`q${index}`"
                :value="true"
                v-model="answers[index]"
                required
                @change="onAnswerChange(index)"
              />
              <div class="text-2xl text-gray-900 font-medium">Yes</div>
              <div class="text-sm text-gray-500 mt-2">I agree</div>
            </label>

            <label
              :class="[
                'group block p-8 border-2 cursor-pointer text-center transition-all duration-200 rounded-lg',
                answers[index] === false
                  ? 'border-gray-600 bg-gray-50/50'
                  : 'border-gray-200 hover:border-gray-400'
              ]"
            >
              <input
                type="radio"
                class="sr-only"
                :name="`q${index}`"
                :value="false"
                v-model="answers[index]"
                required
                @change="onAnswerChange(index)"
              />
              <div class="text-2xl text-gray-900 font-medium">No</div>
              <div class="text-sm text-gray-500 mt-2">I disagree</div>
            </label>
          </div>
        </div>

        <!-- Submit -->
        <div v-if="allAnswered" class="pt-12 border-t border-gray-200">
          <button
            type="submit"
            class="w-full bg-emerald-600 text-white py-5 text-lg hover:bg-emerald-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
          >
            View Results
          </button>
        </div>
      </form>

      <!-- Results -->
      <div v-if="showResults" class="mt-32 pt-32 border-t border-gray-200">
        <div class="mb-20">
          <div class="text-sm text-gray-400 mb-4">Your Interest Code</div>
          <div class="p-8 border-2 border-gray-200 rounded-xl text-center">
            <div class="text-6xl font-light text-gray-900 mb-4 tracking-widest">
              {{ interestCode }}
            </div>
            <div class="text-lg text-gray-500">
              Your top three career interest areas
            </div>
          </div>
        </div>

        <!-- Category Scores -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
          <div
            v-for="(score, category) in sortedScores"
            :key="category"
            class="p-6 border-2 rounded-xl"
            :class="topThree.includes(category) ? 'border-emerald-600 bg-emerald-50/30' : 'border-gray-200'"
          >
            <div class="flex items-center justify-between mb-3">
              <h3 class="text-xl font-semibold text-gray-800">{{ categoryNames[category] }}</h3>
              <span class="text-sm font-bold text-emerald-600">{{ category }}</span>
            </div>
            <p class="text-4xl text-emerald-600 font-light mb-3">
              {{ score }}
            </p>
            <p class="text-sm text-gray-600">{{ categoryDescriptions[category] }}</p>
          </div>
        </div>

        <!-- Career Pathways -->
        <div class="mb-20">
          <h3 class="text-2xl font-light text-gray-900 mb-8">Recommended Career Pathways</h3>
          <div class="space-y-8">
            <div
              v-for="category in topThree"
              :key="category"
              class="p-8 border border-gray-200 rounded-xl"
            >
              <div class="flex items-center gap-3 mb-4">
                <span class="text-3xl font-bold text-emerald-600">{{ category }}</span>
                <h4 class="text-2xl font-semibold text-gray-900">{{ categoryNames[category] }}</h4>
              </div>
              <p class="text-gray-600 mb-6">{{ fullDescriptions[category] }}</p>
              <div class="mb-4">
                <h5 class="text-sm uppercase tracking-wider text-gray-500 mb-3">Related Pathways</h5>
                <div class="flex flex-wrap gap-2">
                  <span
                    v-for="pathway in relatedPathways[category]"
                    :key="pathway"
                    class="px-3 py-1 bg-emerald-50 text-emerald-700 text-sm rounded-full"
                  >
                    {{ pathway }}
                  </span>
                </div>
              </div>
              <div>
                <h5 class="text-sm uppercase tracking-wider text-gray-500 mb-3">Good College Majors</h5>
                <div class="grid sm:grid-cols-2 gap-2 text-sm text-gray-700">
                  <div v-for="major in majors[category]" :key="major" class="flex items-center gap-2">
                    <span class="text-emerald-600">•</span>
                    {{ major }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="flex gap-4">
          <button
            @click="resetAssessment"
            class="flex-1 py-4 border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white transition-colors"
          >
            Retake Assessment
          </button>
          <button
            @click="saveResults"
            class="flex-1 py-4 bg-emerald-600 text-white hover:bg-emerald-700 transition-colors"
          >
            Save Results
          </button>
        </div>

        <div class="mt-20 pt-12 border-t border-gray-200 text-sm text-gray-500 leading-relaxed">
          <p class="mb-4">
            The RIASEC model, developed by psychologist John Holland, categorizes career interests into six types: Realistic, Investigative, Artistic, Social, Enterprising, and Conventional. Your top three scores form your "Interest Code," which can help guide career exploration and education decisions.
          </p>
          <p>
            Remember that these results are indicative of your current interests and can evolve over time. Use them as a starting point for exploring careers that align with your personality and preferences.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue'

const questions = [
  { text: 'I like to work on cars', category: 'R' },
  { text: 'I like to do puzzles', category: 'I' },
  { text: 'I am good at working independently', category: 'I' },
  { text: 'I like to work in teams', category: 'S' },
  { text: 'I am an ambitious person, I set goals for myself', category: 'E' },
  { text: 'I like to organize things (files, desks/offices)', category: 'C' },
  { text: 'I like to build things', category: 'R' },
  { text: 'I like to read about art and music', category: 'A' },
  { text: 'I like to have clear instructions to follow', category: 'C' },
  { text: 'I like to try to influence or persuade people', category: 'E' },
  { text: 'I like to do experiments', category: 'I' },
  { text: 'I like to teach or train people', category: 'S' },
  { text: 'I like trying to help people solve their problems', category: 'S' },
  { text: 'I like to take care of animals', category: 'R' },
  { text: "I wouldn't mind working 8 hours per day in an office", category: 'C' },
  { text: 'I like selling things', category: 'E' },
  { text: 'I enjoy creative writing', category: 'A' },
  { text: 'I enjoy science', category: 'I' },
  { text: 'I am quick to take on new responsibilities', category: 'E' },
  { text: 'I am interested in healing people', category: 'S' },
  { text: 'I enjoy trying to figure out how things work', category: 'I' },
  { text: 'I like putting things together or assembling things', category: 'R' },
  { text: 'I am a creative person', category: 'A' },
  { text: 'I pay attention to details', category: 'C' },
  { text: 'I like to do filing or typing', category: 'C' },
  { text: 'I like to analyze things (problems/situations)', category: 'I' },
  { text: 'I like to play instruments or sing', category: 'A' },
  { text: 'I enjoy learning about other cultures', category: 'S' },
  { text: 'I would like to start my own business', category: 'E' },
  { text: 'I like to cook', category: 'R' },
  { text: 'I like acting in plays', category: 'A' },
  { text: 'I am a practical person', category: 'R' },
  { text: 'I like working with numbers or charts', category: 'C' },
  { text: 'I like to get into discussions about issues', category: 'I' },
  { text: 'I am good at keeping records of my work', category: 'C' },
  { text: 'I like to lead', category: 'E' },
  { text: 'I like working outdoors', category: 'R' },
  { text: 'I would like to work in an office', category: 'C' },
  { text: "I'm good at math", category: 'I' },
  { text: 'I like helping people', category: 'S' },
  { text: 'I like to draw', category: 'A' },
  { text: 'I like to give speeches', category: 'E' }
]

const categoryNames = {
  R: 'Realistic',
  I: 'Investigative',
  A: 'Artistic',
  S: 'Social',
  E: 'Enterprising',
  C: 'Conventional'
}

const categoryDescriptions = {
  R: 'Hands-on, practical, mechanical',
  I: 'Analytical, curious, problem-solver',
  A: 'Creative, expressive, imaginative',
  S: 'Helpful, collaborative, people-oriented',
  E: 'Persuasive, ambitious, leader',
  C: 'Organized, detail-oriented, systematic'
}

const fullDescriptions = {
  R: 'These people are often good at mechanical or athletic jobs. They enjoy working with tools, machines, and their hands.',
  I: 'These people like to watch, learn, analyze and solve problems. They are curious and enjoy research and investigation.',
  A: 'These people like to work in unstructured situations where they can use their creativity. They value self-expression and originality.',
  S: 'These people like to work with other people, rather than things. They enjoy helping, teaching, and caring for others.',
  E: 'These people like to work with others and enjoy persuading and performing. They are natural leaders and are goal-oriented.',
  C: 'These people are very detail oriented, organized and like to work with data. They value accuracy and efficiency.'
}

const relatedPathways = {
  R: ['Natural Resources', 'Health Services', 'Industrial and Engineering Technology', 'Arts and Communication'],
  I: ['Health Services', 'Business', 'Public and Human Services', 'Industrial and Engineering Technology'],
  A: ['Public and Human Services', 'Arts and Communication'],
  S: ['Health Services', 'Public and Human Services'],
  E: ['Business', 'Public and Human Services', 'Arts and Communication'],
  C: ['Health Services', 'Business', 'Industrial and Engineering Technology']
}

const majors = {
  R: ['Agriculture', 'Health Assistant', 'Computers', 'Construction', 'Mechanic/Machinist', 'Engineering', 'Food and Hospitality'],
  I: ['Marine Biology', 'Engineering', 'Chemistry', 'Zoology', 'Medicine/Surgery', 'Consumer Economics', 'Psychology'],
  A: ['Communications', 'Cosmetology', 'Fine and Performing Arts', 'Photography', 'Radio and TV', 'Interior Design', 'Architecture'],
  S: ['Counseling', 'Nursing', 'Physical Therapy', 'Travel', 'Advertising', 'Public Relations', 'Education'],
  E: ['Fashion Merchandising', 'Real Estate', 'Marketing/Sales', 'Law', 'Political Science', 'International Trade', 'Banking/Finance'],
  C: ['Accounting', 'Court Reporting', 'Insurance', 'Administration', 'Medical Records', 'Banking', 'Data Processing']
}

const answers = ref({})
const questionRefs = ref({})
const showResults = ref(false)
const scores = ref({})

const answeredQuestions = computed(() => Object.keys(answers.value).length)
const allAnswered = computed(() => answeredQuestions.value === questions.length)

const setQuestionRef = (index, el) => {
  if (el) questionRefs.value[index] = el
}

const onAnswerChange = async (index) => {
  await nextTick()
  const nextIndex = index + 1
  if (questionRefs.value[nextIndex]) {
    questionRefs.value[nextIndex].scrollIntoView({ behavior: 'smooth', block: 'center' })
  }
}

const calculateResults = () => {
  const categoryScores = { R: 0, I: 0, A: 0, S: 0, E: 0, C: 0 }
  
  questions.forEach((question, index) => {
    if (answers.value[index] === true) {
      categoryScores[question.category]++
    }
  })
  
  scores.value = categoryScores
  showResults.value = true
  
  nextTick(() => {
    document.querySelector('.mt-32.pt-32')?.scrollIntoView({ behavior: 'smooth' })
  })
}

const sortedScores = computed(() => {
  return Object.fromEntries(
    Object.entries(scores.value).sort((a, b) => b[1] - a[1])
  )
})

const topThree = computed(() => {
  return Object.keys(sortedScores.value).slice(0, 3)
})

const interestCode = computed(() => {
  return topThree.value.join('')
})

const resetAssessment = () => {
  answers.value = {}
  showResults.value = false
  scores.value = {}
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const saveResults = () => {
  console.log('Saved results:', { code: interestCode.value, scores: scores.value })
  alert('Results saved successfully!')
}
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}
</style>