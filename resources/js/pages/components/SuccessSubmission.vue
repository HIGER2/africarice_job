<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-50 px-4">
    <div class="bg-white border border-gray-200 rounded-lg p-8 max-w-md w-full text-center space-y-6">

      <!-- Icône validation -->
      <div class="flex justify-center">
        <div class="bg-green-100 w-20 text-green-700 text-center leading-20 h-20 rounded-full text-6xl">
            <i class="uil uil-check"></i>
        </div>
      </div>

      <!-- Message Principal -->
      <div class="space-y-1">
        <h2 class="text-xl font-semibold text-gray-800">
          Candidature soumise avec succès
        </h2>
        <p class="text-gray-500 text-sm">
          Application submitted successfully
        </p>
      </div>

      <!-- Texte secondaire -->
      <div class="space-y-2 leading-relaxed">
        <p class="text-gray-700 text-sm">
          Merci d’avoir soumis votre candidature.  
          Notre équipe l’examinera et vous contactera si nécessaire.
        </p>

        <p class="text-gray-500 text-xs">
          Thank you for submitting your application.  
          Our team will review it and contact you if needed.
        </p>
      </div>

      <!-- Informations supplémentaires -->
      <div class="text-left space-y-4 bg-gray-100 p-4 rounded-xl">
        
        <div class="space-y-1">
          <p class="font-semibold text-gray-700">Statut :</p>
          <p class="text-gray-700 text-sm">En cours de vérification</p>
          <p class="text-gray-500 text-xs">Status: Under review</p>
        </div>

        <!-- <div class="space-y-1">
          <p class="font-semibold text-gray-700">Référence :</p>
          <p class="text-gray-700 text-sm">{{ reference }}</p>
          <p class="text-gray-500 text-xs">Reference: {{ reference }}</p>
        </div> -->

      </div>

      <!-- Bouton retour -->
      <a href="/"
        class="w-full border block border-primary text-priborder-primary py-3 rounded-xl font-semibold hover:bg-gray-50 transition">
        Retour à l’accueil / Back to Home
      </a>

    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  reference: {
    type: String,
    default: "REF-2025-001"
  }
})

const emit = defineEmits(["go-home"])
const goHome = () => emit("go-home")
</script>
