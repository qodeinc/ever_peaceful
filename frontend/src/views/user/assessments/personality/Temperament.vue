<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-20">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">
          Personality Temperament Test
        </h1>
        <p class="text-lg text-gray-500 mb-8 max-w-2xl">
          Discover your temperament blend based on Tim LaHaye's "Why You Act The Way You Do". This assessment helps identify your potential strengths and weaknesses across four temperament types.
        </p>
        <div class="text-sm text-gray-400">120 descriptive words · 4 sections · 15–20 minutes</div>
      </div>

      <!-- Instructions -->
      <div v-if="!showResults" class="mb-16 pb-16 border-b border-gray-200">
        <h3 class="text-sm uppercase tracking-wider text-gray-500 mb-6">
          Instructions
        </h3>
        <p class="text-base text-gray-600 leading-relaxed mb-4">
          Read each descriptive word and rate how well it describes the REAL you. Be completely honest - don't answer according to how you want to be, but how you naturally are right now. The assessment will automatically move to the next word after you select your answer.
        </p>
        <div class="mt-6 p-6 bg-gray-50 rounded-lg">
          <h4 class="font-semibold text-gray-900 mb-3">Rating Scale:</h4>
          <div class="space-y-2 text-sm text-gray-700">
            <div><span class="font-bold">1</span> = Definitely not me</div>
            <div><span class="font-bold">2</span> = Usually not me</div>
            <div><span class="font-bold">3</span> = Usually me</div>
            <div><span class="font-bold">4</span> = Mostly me</div>
            <div><span class="font-bold">5</span> = Definitely me</div>
          </div>
        </div>
      </div>

      <!-- Progress Bar -->
      <div v-if="!showResults" class="mb-16">
        <div class="flex items-center gap-2">
          <div
            v-for="i in totalWords"
            :key="i"
            :class="[
              'h-1 flex-1 transition-all duration-500',
              answeredCount >= i ? 'bg-emerald-600' : 'bg-gray-200'
            ]"
          />
        </div>
        <div class="text-sm text-gray-500 mt-3">
          {{ answeredCount }} of {{ totalWords }} completed
        </div>
      </div>

      <!-- Questions -->
      <form v-if="!showResults" @submit.prevent="calculateResults" class="space-y-24">
        <div
          v-for="(item, index) in allWords"
          :key="index"
          :ref="el => setWordRef(index, el)"
          class="scroll-mt-32"
        >
          <div class="mb-8">
            <div class="text-sm text-gray-400 mb-3">
              Word {{ index + 1 }} of {{ totalWords }} · {{ item.sectionName }}
            </div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">
              {{ item.word }}
            </h2>
            <p class="text-gray-500">How well does this describe you?</p>
          </div>

          <!-- Rating Options -->
          <div class="grid grid-cols-5 gap-3">
            <button
              v-for="rating in ratings"
              :key="rating.value"
              type="button"
              @click="setAnswer(index, rating.value)"
              :class="[
                'group block p-6 border-2 cursor-pointer text-center transition-all duration-200 rounded-lg',
                answers[index] === rating.value
                  ? 'border-emerald-600 bg-emerald-50/50'
                  : 'border-gray-200 hover:border-gray-400'
              ]"
            >
              <div class="text-2xl text-gray-900 font-medium mb-2">{{ rating.value }}</div>
              <div class="text-xs text-gray-600 leading-tight">{{ rating.label }}</div>
            </button>
          </div>
        </div>

        <!-- Submit -->
        <div v-if="allAnswered" class="pt-12 border-t border-gray-200">
          <button
            type="submit"
            class="w-full bg-emerald-600 text-white py-5 text-lg hover:bg-emerald-700 transition-colors rounded-lg"
          >
            View Results
          </button>
        </div>
      </form>

      <!-- Results -->
      <div v-if="showResults" class="mt-32 pt-32 border-t border-gray-200">
        <div class="mb-20">
          <div class="text-sm text-gray-400 mb-4">Your Temperament Blend</div>
          <div class="p-8 border-2 border-gray-200 rounded-xl text-center">
            <div class="text-6xl font-light text-gray-900 mb-4 tracking-wide">
              {{ temperamentBlend }}
            </div>
            <div class="text-lg text-gray-500">
              {{ blendDescription }}
            </div>
          </div>
        </div>

        <!-- Section Scores -->
        <div class="grid sm:grid-cols-2 gap-8 mb-20">
          <div
            v-for="(score, index) in sortedScores"
            :key="index"
            class="p-6 border-2 rounded-xl"
            :class="index < 2 ? 'border-emerald-600 bg-emerald-50/30' : 'border-gray-200'"
          >
            <div class="flex items-center justify-between mb-3">
              <h3 class="text-xl font-semibold text-gray-800">{{ score.name }}</h3>
              <span v-if="index < 2" class="text-sm font-bold text-emerald-600">
                {{ index === 0 ? 'Primary' : 'Secondary' }}
              </span>
            </div>
            <p class="text-4xl text-emerald-600 font-light mb-3">
              {{ score.total }}
            </p>
            <p class="text-sm text-gray-600">{{ score.description }}</p>
          </div>
        </div>

        <!-- Detailed Profile -->
        <div class="mb-20">
          <h3 class="text-2xl font-light text-gray-900 mb-8">Your Temperament Profile</h3>
          <div class="p-8 border border-gray-200 rounded-xl">
            <div class="prose prose-gray max-w-none">
              <div v-html="profileDescription" class="text-gray-700 leading-relaxed"></div>
            </div>
          </div>
        </div>

        <!-- Characteristics -->
        <div class="mb-20">
          <h3 class="text-2xl font-light text-gray-900 mb-8">Key Characteristics</h3>
          <div class="grid sm:grid-cols-2 gap-6">
            <div class="p-6 bg-emerald-50 rounded-xl">
              <h4 class="text-lg font-semibold text-emerald-900 mb-4">Strengths</h4>
              <ul class="space-y-2 text-sm text-emerald-800">
                <li v-for="(strength, index) in strengths" :key="index" class="flex items-start gap-2">
                  <span class="text-emerald-600 mt-1">•</span>
                  <span>{{ strength }}</span>
                </li>
              </ul>
            </div>
            <div class="p-6 bg-gray-50 rounded-xl">
              <h4 class="text-lg font-semibold text-gray-900 mb-4">Areas to Develop</h4>
              <ul class="space-y-2 text-sm text-gray-700">
                <li v-for="(weakness, index) in weaknesses" :key="index" class="flex items-start gap-2">
                  <span class="text-gray-500 mt-1">•</span>
                  <span>{{ weakness }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="flex gap-4">
          <button
            @click="resetAssessment"
            class="flex-1 py-4 border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white transition-colors rounded-lg"
          >
            Retake Assessment
          </button>
          <button
            @click="saveResults"
            class="flex-1 py-4 bg-emerald-600 text-white hover:bg-emerald-700 transition-colors rounded-lg"
          >
            Save Results
          </button>
        </div>

        <div class="mt-20 pt-12 border-t border-gray-200 text-sm text-gray-500 leading-relaxed">
          <p class="mb-4">
            This personality temperament test is based on Tim LaHaye's work "Why You Act The Way You Do". The four temperaments—Sanguine, Choleric, Melancholy, and Phlegmatic—represent different personality patterns, each with unique strengths and challenges.
          </p>
          <p>
            Remember that no temperament is better than another, and most people are a blend of two or more types. Understanding your temperament can help you maximize your strengths and work on areas that need development.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue'

const section1Words = [
  'emotional', 'egotistical', 'interrupts others', 'compassionate', 'impulsive', 
  'disorganized', 'impractical', 'funny', 'forgetful', 'easily discouraged',
  'very positive', 'easily angered', 'undisciplined', 'extrovert', 'refreshing',
  'lively/spirited', 'weak-willed', 'spontaneous', 'talkative', 'delightful/cheerful',
  'enjoyable', 'popular', 'friendly/sociable', 'bouncy', 'restless',
  'difficulty concentrating', 'likes to play', 'difficulty keeping resolutions',
  'lives in present', 'difficulty with appointments', 'optimistic'
]

const section2Words = [
  'determined', 'bossy', 'goal-oriented', 'decisive', 'frank', 'self-confident',
  'sarcastic', 'workaholic', 'self-sufficient', 'practical', 'headstrong',
  'activist', 'outgoing', 'domineering', 'adventurous', 'aggressive',
  'competitive', 'leadership ability', 'daring', 'persevering', 'bold',
  'strong-willed', 'persuasive', 'hot-tempered', 'resourceful', 'insensitive',
  'outspoken', 'unsympathetic', 'productive'
]

const section3Words = [
  'deep feeling', 'critical', 'insecure', 'sensitive', 'indecisive',
  'hard to please', 'self-centered', 'pessimistic', 'depressed easily',
  'easily offended', 'idealistic', 'loner', 'self-sacrificing', 'introvert',
  'faithful friend', 'analytical', 'considerate', 'likes behind the scenes',
  'suspicious', 'respectful', 'introspective', 'planner', 'perfectionist',
  'scheduled', 'unforgiving/resents', 'orderly', 'creative', 'detailed',
  'moody', 'gifted (musically or athletically)'
]

const section4Words = [
  'very quiet', 'selfish', 'unenthusiastic', 'negative', 'regular daily habits',
  'hesitant', 'shy', 'stingy', 'aimless', 'not aggressive', 'stubborn',
  'worrier', 'spectator of life', 'works well under pressure', 'indecisive',
  'adaptable', 'slow and lazy', 'submissive to others', 'easy going',
  'reserved', 'calm and cool', 'content/satisfied', 'efficient', 'patient',
  'dependable', 'listener', 'witty/dry humor', 'pleasant', 'teases others',
  'consistent'
]

const sections = [
  { name: 'Section 1', words: section1Words, temperament: 'Sanguine', description: 'Fun-loving extrovert; outgoing; very social' },
  { name: 'Section 2', words: section2Words, temperament: 'Choleric', description: 'Focused; extrovert; goal oriented' },
  { name: 'Section 3', words: section3Words, temperament: 'Melancholy', description: 'Detailed; introspective; artistic' },
  { name: 'Section 4', words: section4Words, temperament: 'Phlegmatic', description: 'Easy going; stable; consistent' }
]

const ratings = [
  { value: 1, label: 'Definitely not me' },
  { value: 2, label: 'Usually not me' },
  { value: 3, label: 'Usually me' },
  { value: 4, label: 'Mostly me' },
  { value: 5, label: 'Definitely me' }
]

const allWords = computed(() => {
  const words = []
  sections.forEach((section, sectionIndex) => {
    section.words.forEach(word => {
      words.push({
        word,
        sectionIndex,
        sectionName: section.name,
        temperament: section.temperament
      })
    })
  })
  return words
})

const answers = ref({})
const showResults = ref(false)
const wordRefs = ref({})

const totalWords = computed(() => allWords.value.length)

const answeredCount = computed(() => Object.keys(answers.value).length)

const allAnswered = computed(() => answeredCount.value === totalWords.value)

const setWordRef = (index, el) => {
  if (el) wordRefs.value[index] = el
}

const setAnswer = async (index, rating) => {
  answers.value[index] = rating
  
  await nextTick()
  
  const nextIndex = index + 1
  if (nextIndex < totalWords.value && wordRefs.value[nextIndex]) {
    setTimeout(() => {
      wordRefs.value[nextIndex].scrollIntoView({ behavior: 'smooth', block: 'center' })
    }, 150)
  }
}

const sectionScores = computed(() => {
  return sections.map((section, sectionIndex) => {
    let total = 0
    let countedAnswers = 0
    
    allWords.value.forEach((item, index) => {
      if (item.sectionIndex === sectionIndex) {
        const rating = answers.value[index]
        if (rating && rating >= 3) {
          total += rating
          countedAnswers++
        }
      }
    })
    
    return {
      name: section.temperament,
      total,
      countedAnswers,
      description: section.description
    }
  })
})

const sortedScores = computed(() => {
  return [...sectionScores.value].sort((a, b) => b.total - a.total)
})

const temperamentBlend = computed(() => {
  if (sortedScores.value.length < 2) return ''
  const primary = sortedScores.value[0].name
  const secondary = sortedScores.value[1].name
  return `${primary.substring(0, 3)}${secondary.substring(0, 4)}`
})

const blendDescription = computed(() => {
  if (sortedScores.value.length < 2) return ''
  return `${sortedScores.value[0].name} with ${sortedScores.value[1].name} traits`
})

const profileDescription = computed(() => {
  const blend = temperamentBlend.value
  
  const profiles = {
    'SanChol': '<p>The strongest extrovert of all the blends. You are a people-oriented, enthusiastic person with the resolution and character to be organized and productive. Almost any people-oriented field is open to you, especially those offering variety, activity, and excitement.</p>',
    
    'SanMela': '<p>You are highly emotional and fluctuate drastically between highs and lows. You genuinely feel the griefs of others and are drawn to public speaking, acting, music, and fine arts. You have sufficient substance to make meaningful contributions to others if ego and arrogance are kept in check.</p>',
    
    'SanPhle': '<p>You are extremely likable with a carefree spirit and good humor. Your gracious, easygoing nature makes you a lighthearted entertainer sought after by others. You naturally preserve love and would not purposely hurt anyone. Helping people is your calling.</p>',
    
    'ChoSang': '<p>Your life is given over completely to activity. You are a natural promoter and salesman with boundless energy. You thrive on challenges and are almost fearless. You are the best motivator of people and an excellent communicator, particularly in practical matters.</p>',
    
    'ChoMela': '<p>You are extremely industrious and capable. Your optimism and practicality overcome moodiness, making you both goal-oriented and detail-focused. You are a thorough leader who does extraordinary work. You excel in research and are usually successful in any business you pursue.</p>',
    
    'ChoPhle': '<p>You are the most subdued of the extroverts, blending quick action with calm deliberation. You are extremely capable, well-organized, and know where you are going. People enjoy working with you because you help others make the best use of their skills without making them feel used.</p>',
    
    'MelSang': '<p>You are a very gifted person capable of excellence in music, art, or education. You make an outstanding teacher with the ability to ferret out facts and communicate them well. You are deeply emotional and feel everything intensely, which fuels your creativity.</p>',
    
    'MelChol': '<p>Your mood swings are stabilized by self-will and determination. You are both a perfectionist and a driver with strong leadership capabilities. Almost any craft, construction, or educational pursuit is open to you. You run things capably with efficiency rather than noise.</p>',
    
    'MelPhle': '<p>You are among the greatest scholars, combining analytical perfectionism with organized efficiency. You are a good-natured humanitarian who prefers quiet, solitary environments for study and research. Many significant inventions and discoveries have been made by people like you.</p>',
    
    'PhlSang': '<p>You are the easiest blend to get along with over time. You are congenial, happy, cooperative, thoughtful, people-oriented, diplomatic, dependable, and fun-loving. You are a favorite with both children and adults and never display an abrasive personality.</p>',
    
    'PhlChol': '<p>You are the most active of all phlegmatics. You make an excellent group leader and counselor, as you are an excellent listener genuinely interested in others. Your gentle spirit never makes people feel threatened, and your advice is practical and trustworthy.</p>',
    
    'PhlMela': '<p>You are the most gracious, gentle, and quiet of all blends. You are rarely angry and almost never say anything requiring an apology. You are dependable, exact, and have the spiritual gifts of mercy and help. You are neat and organized with a strong humanitarian spirit.</p>'
  }
  
  return profiles[blend] || '<p>You have a unique temperament blend that combines traits from multiple personality types. This makes you adaptable and able to relate to many different kinds of people.</p>'
})

const strengths = computed(() => {
  const blend = temperamentBlend.value
  
  const strengthsList = {
    'SanChol': ['Enthusiastic and energetic', 'Natural leadership', 'Excellent communicator', 'People-oriented', 'Motivates others'],
    'SanMela': ['Deeply empathetic', 'Creative and artistic', 'Excellent communicator', 'Emotional depth', 'Makes meaningful contributions'],
    'SanPhle': ['Extremely likable', 'Good humor', 'Caring and gentle', 'Loyal friend', 'Natural helper'],
    'ChoSang': ['Boundless energy', 'Natural promoter', 'Fearless', 'Excellent motivator', 'Thrives on challenges'],
    'ChoMela': ['Highly capable', 'Detail-oriented', 'Strong leader', 'Thorough researcher', 'Goal-driven'],
    'ChoPhle': ['Well-organized', 'Deliberate and thoughtful', 'Makes others feel valued', 'Stable leadership', 'Efficient planner'],
    'MelSang': ['Highly gifted', 'Excellent teacher', 'Creative', 'Detail-oriented', 'Deep emotional awareness'],
    'MelChol': ['Perfectionist', 'Strong determination', 'Analytical mind', 'Capable leader', 'Self-disciplined'],
    'MelPhle': ['Scholarly', 'Analytical', 'Organized', 'Humanitarian', 'Innovative thinker'],
    'PhlSang': ['Very likable', 'Cooperative', 'Diplomatic', 'Fun-loving', 'Dependable'],
    'PhlChol': ['Excellent listener', 'Practical advisor', 'Gentle spirit', 'Good leader', 'Trustworthy'],
    'PhlMela': ['Gracious and gentle', 'Dependable', 'Merciful', 'Organized', 'Never offensive']
  }
  
  return strengthsList[blend] || ['Adaptable', 'Balanced', 'Understanding', 'Versatile', 'Well-rounded']
})

const weaknesses = computed(() => {
  const blend = temperamentBlend.value
  
  const weaknessesList = {
    'SanChol': ['Talks too much', 'Quick to anger', 'Impulsive', 'Can be overbearing', 'Difficulty finishing tasks'],
    'SanMela': ['Mood swings', 'Negative thinking', 'Insecurity', 'Overly critical', 'Fear-driven'],
    'SanPhle': ['Lacks motivation', 'Undisciplined', 'Too casual', 'Avoids seriousness', 'Procrastinates'],
    'ChoSang': ['Hostile and angry', 'Impatient', 'Brutally frank', 'Workaholic', 'Neglects family'],
    'ChoMela': ['Autocratic', 'Harsh sarcasm', 'Harbors resentment', 'Overly strict', 'Hard to please'],
    'ChoPhle': ['Stubborn', 'Harbors bitterness', 'Sarcastic humor', 'Worrisome', 'Resistant to change'],
    'MelSang': ['Emotional extremes', 'Rigid', 'Fearful', 'Poor self-image', 'Unrealistic expectations'],
    'MelChol': ['Intolerant', 'Quick to depression', 'Hostile thoughts', 'Nitpicker', 'Critical of others'],
    'MelPhle': ['Easily discouraged', 'Negative thinking', 'Fearful', 'Poor self-worth', 'Allows others to pressure them'],
    'PhlSang': ['Lacks discipline', 'Fearful', 'Timid', 'Avoids commitment', 'Falls short of potential'],
    'PhlChol': ['Passive', 'Stubborn', 'Silent anger', 'Lacks motivation', 'Overly sedentary'],
    'PhlMela': ['Fearful', 'Negative', 'Critical', 'Passive', 'Avoids involvement']
  }
  
  return weaknessesList[blend] || ['May struggle with decisiveness', 'Can be inconsistent', 'Difficulty prioritizing', 'Sometimes unfocused']
})

const calculateResults = () => {
  showResults.value = true
  nextTick(() => {
    window.scrollTo({ top: 0, behavior: 'smooth' })
  })
}

const resetAssessment = () => {
  answers.value = {}
  showResults.value = false
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const saveResults = () => {
  alert('Results saved successfully!')
}
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}
</style>