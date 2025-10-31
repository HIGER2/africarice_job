<template>
  <div class="min-h-screen flex items-center justify-center px-4">
    <div class="bg-white p-10 rounded-lg  max-w-xl w-full text-center">
      
      <!-- Icône succès -->
      <div class="mb-6 w-30 h-30 flex items-center justify-center leading-30 bg-green-50 rounded-full mx-auto">
        <svg class="w-16 h-16 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M5 13l4 4L19 7" />
        </svg>
      </div>

      <!-- Titre rassurant bilingue -->
      <h1 class="text-xl font-bold text-primary mb-4">
        Merci pour votre intérêt ! / Thank you for your interest!
      </h1>
      
      <!-- Description détaillée et rassurante bilingue -->
      <p class="text-gray-700 mb-6 leading-relaxed text-[15px]">
        Nous avons bien pris note de votre intérêt et nous vous tiendrons informé dès qu'une nouvelle opportunité sera disponible. Vous pouvez rester tranquille, vous ne manquerez aucune offre future.<br><br>
        We have noted your interest and will notify you as soon as a new opportunity becomes available. Rest assured, you won’t miss any future openings.
      </p>

      <!-- Bouton retour -->
      <a 
        href="/"
        class="bg-secondary max-w-max mx-auto block text-white px-6 py-2 rounded-lg font-medium shadow hover:shadow-lg transition"
      >
        Retour à l'accueil / Back to Home
      </a>
    </div>
  </div>
</template>

<script setup>
// Pas de logique particulière ici
</script>

<style>
/* Tailwind gère déjà tout */
</style>
