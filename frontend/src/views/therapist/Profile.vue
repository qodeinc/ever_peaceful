<template>
  <section class="therapist-profile">
    <header class="therapist-profile__header">
      <h1 class="therapist-profile__title">Therapist Profile</h1>
      <p class="therapist-profile__subtitle">
        Manage and update your professional profile information.
      </p>
    </header>

    <main class="therapist-profile__content">
      <p>
        This page is currently a placeholder. You can extend it with profile details,
        specialties, experience, and availability settings.
      </p>
    </main>
  </section>
</template>

<script setup lang="ts">
// Therapist profile view placeholder
// Add your profile form, API integration, and related logic here.
</script>

<style scoped>
.therapist-profile {
  max-width: 960px;
  margin: 0 auto;
  padding: 2rem 1.5rem 3rem;
}

.therapist-profile__header {
  margin-bottom: 1.5rem;
}

.therapist-profile__title {
  font-size: 1.75rem;
  font-weight: 700;
  margin-bottom: 0.25rem;
}

.therapist-profile__subtitle {
  color: #6b7280;
  font-size: 0.95rem;
}

.therapist-profile__content {
  padding: 1.5rem;
  border-radius: 0.75rem;
  background: #ffffff;
  box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
  border: 1px solid #e5e7eb;
  color: #374151;
  line-height: 1.6;
}
</style>
