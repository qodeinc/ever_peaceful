import { ref } from 'vue'

export const useDashboardNavigation = () => {
  const activeComponent = ref('dashboard')
  const sessionId = ref<number | null>(null)

  const setActiveComponent = (name: string, params: { sessionId?: number } = {}) => {
    activeComponent.value = name
    sessionId.value = params.sessionId || null
  }

  return {
    activeComponent,
    sessionId,
    setActiveComponent
  }
}
