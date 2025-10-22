// src/utils/auth.ts
// Authentication utility functions

export interface User {
  id: string
  name: string
  email: string
  role: 'user' | 'therapist' | 'admin'
}

/**
 * Check if user is authenticated
 */
export const isAuthenticated = (): boolean => {
  const authToken = localStorage.getItem('authToken')
  const userSession = localStorage.getItem('userSession')
  return !!(authToken || userSession)
}

/**
 * Get current user role
 */
export const getUserRole = (): 'user' | 'therapist' | 'admin' => {
  const role = localStorage.getItem('userRole')
  return (role as 'user' | 'therapist' | 'admin') || 'user'
}

/**
 * Get current user data
 */
export const getCurrentUser = (): User | null => {
  const userDataStr = localStorage.getItem('userData')
  if (!userDataStr) return null
  
  try {
    return JSON.parse(userDataStr) as User
  } catch {
    return null
  }
}

/**
 * Set authentication data
 */
export const setAuth = (token: string, user: User): void => {
  localStorage.setItem('authToken', token)
  localStorage.setItem('userRole', user.role)
  localStorage.setItem('userData', JSON.stringify(user))
  localStorage.setItem('userSession', Date.now().toString())
}

/**
 * Clear all authentication data
 */
export const clearAuth = (): void => {
  // Remove specific auth items
  localStorage.removeItem('authToken')
  localStorage.removeItem('userSession')
  localStorage.removeItem('userRole')
  localStorage.removeItem('userData')
  localStorage.removeItem('therapistData')
  localStorage.removeItem('therapistProfile')
  
  // Remove Calendly tokens
  localStorage.removeItem('calendly_access_token')
  localStorage.removeItem('calendly_refresh_token')
  
  // Clear session storage
  sessionStorage.clear()
}

/**
 * Logout user and redirect
 */
export const logout = async (router: any): Promise<void> => {
  try {
    // Optional: Call your API logout endpoint
    // await fetch('/api/auth/logout', { 
    //   method: 'POST',
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('authToken')}`
    //   }
    // })
    
    // Clear all auth data
    clearAuth()
    
    // Redirect to login
    await router.push({ name: 'login' })
    
    // Force page reload to clear any cached state
    window.location.reload()
  } catch (error) {
    console.error('Logout error:', error)
    // Still clear auth and redirect even if API call fails
    clearAuth()
    router.push({ name: 'login' })
  }
}

/**
 * Check if session is expired (optional)
 */
export const isSessionExpired = (): boolean => {
  const sessionTime = localStorage.getItem('userSession')
  if (!sessionTime) return true
  
  const sessionAge = Date.now() - parseInt(sessionTime)
  const maxAge = 24 * 60 * 60 * 1000 // 24 hours in milliseconds
  
  return sessionAge > maxAge
}

/**
 * Refresh session timestamp
 */
export const refreshSession = (): void => {
  if (isAuthenticated()) {
    localStorage.setItem('userSession', Date.now().toString())
  }
}
