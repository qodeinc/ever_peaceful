<template>
  <section class="page">
    <header class="page__header">
      <h1 class="page__title">Alert Details</h1>
      <p class="page__subtitle">Review details for a client alert.</p>
    </header>

    <main class="page__content">
      <p>This view is a placeholder.</p>
    </main>
  </section>
</template>

<script setup lang="ts">
// Therapist alert details view placeholder
</script>

<style scoped>
.page {
  max-width: 960px;
  margin: 0 auto;
  padding: 2rem 1.5rem 3rem;
}
.page__header {
  margin-bottom: 1.5rem;
}
.page__title {
  font-size: 1.75rem;
  font-weight: 700;
  margin: 0 0 0.25rem;
}
.page__subtitle {
  margin: 0;
  color: #6b7280;
  font-size: 0.95rem;
}
.page__content {
  padding: 1.5rem;
  border-radius: 0.75rem;
  background: #fff;
  border: 1px solid #e5e7eb;
  box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
  color: #374151;
  line-height: 1.6;
}
</style>
