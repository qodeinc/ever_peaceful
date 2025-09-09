<!-- src/App.vue -->
<template>
  <div id="app" class="min-h-screen bg-white">
    <router-view name="navbar"></router-view>
    <main>
      <router-view></router-view>
    </main>
    <router-view name="footer"></router-view>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  name: "App",
});
</script>

<style>
/* CSS Variables for the mental health theme */
:root {
  --primary: #3b82f6;
  --primary-dark: #2563eb;
  --secondary: #8b5cf6;
  --accent: #10b981;
  --gray-50: #f9fafb;
  --gray-100: #f3f4f6;
  --gray-200: #e5e7eb;
  --gray-300: #d1d5db;
  --gray-400: #9ca3af;
  --gray-500: #6b7280;
  --gray-600: #4b5563;
  --gray-700: #374151;
  --gray-800: #1f2937;
  --gray-900: #111827;
}

/* Base styles */
html {
  scroll-behavior: smooth;
  height: 100%;
}

body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  height: 100%;
  margin: 0;
  padding: 0;
  line-height: 1.6;
  color: var(--gray-900);
}

/* Remove Safari/Chrome tap highlight on mobile */
* {
  -webkit-tap-highlight-color: transparent;
  box-sizing: border-box;
}

/* App container */
#app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

/* Main content area */
main {
  flex: 1;
  padding-top: 64px; /* Account for fixed navbar */
}

/* Global transitions for page changes */
.page-enter-active,
.page-leave-active {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.page-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.page-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}

/* Router view transitions */
.router-view {
  transition: all 0.3s ease;
}

/* Proper image handling */
img {
  max-width: 100%;
  height: auto;
}

/* Custom scrollbar for webkit browsers */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: var(--gray-100);
}

::-webkit-scrollbar-thumb {
  background: var(--gray-400);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: var(--gray-500);
}

/* Focus styles for accessibility */
*:focus {
  outline: 2px solid var(--primary);
  outline-offset: 2px;
}

/* Button focus styles */
button:focus,
a:focus {
  outline: 2px solid var(--primary);
  outline-offset: 2px;
}

/* Loading animation for async components */
.loading {
  position: relative;
  overflow: hidden;
}

.loading::after {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(59, 130, 246, 0.1),
    transparent
  );
  animation: loading 1.5s infinite;
}

@keyframes loading {
  0% {
    left: -100%;
  }
  100% {
    left: 100%;
  }
}

/* Utility classes */
.fade-in {
  animation: fadeIn 0.6s ease-out;
}

.fade-in-up {
  animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive typography */
@media (max-width: 640px) {
  body {
    font-size: 14px;
  }
}

/* Print styles */
@media print {
  .no-print {
    display: none !important;
  }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
  :root {
    --primary: #0056b3;
    --primary-dark: #004494;
  }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}

/* Dark mode support (if needed later) */
@media (prefers-color-scheme: dark) {
  /* Dark mode styles can be added here if needed */
}
</style>