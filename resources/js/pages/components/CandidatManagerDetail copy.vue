
<script lang="ts" setup>
import AddOffre from './AddOffre.vue';
import Table from './Table.vue';

defineProps({
  data: Array,
})

// const columns = [
// //   { key: "uuid", label: "# UUID" },
//   { key: "user", label: "Nom complet" },
//   { key: "nationality", label: "Nationalité" },
//   { key: "country", label: "Pays" },
//   { key: "city", label: "Ville" },
//   { key: "experience_years", label: "Expérience" },
// //   { key: "french_level", label: "Français" },
// //   { key: "english_level", label: "Anglais" },
//   { key: "documents", label: "document" },
// //   { key: "created_at", label: "Créé le" },
// //   { key: "actions", label: "Actions" }, // colonne pour slot
// ];

</script>

<template>
 <div>
<div class="max-w-6xl mx-auto  space-y-8">
  <!-- <pre>{{ data }}</pre> -->
  <!-- User Info -->
  <div class="bg-white rounded-lg border border-gray-200 p-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-4">Profil: {{ data.data[0].name }} {{ data.data[0].last_name }}</h2>
    <div class="space-y-2 text-gray-700">
      <p><span class="font-semibold">Role:</span> {{ data.data[0].role }}</p>
      <p><span class="font-semibold">Email:</span> {{ data.data[0].email }}</p>
      <p><span class="font-semibold">Phone:</span> {{ data.data[0].phone }}</p>
      <p class="text-gray-400 text-sm">Créé le: {{ data.data[0].created_at }} | Mis à jour: {{ data.data[0].updated_at }}</p>
    </div>
  </div>

  <!-- Origin / Identification / CGIAR -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <!-- Origin -->
    <div class="bg-blue-50 rounded-lg p-6 space-y-3" v-if="data.data[0]?.application?.origin">
      <h3 class="text-xl font-semibold text-blue-700 mb-2">Origine</h3>
      <div class="space-y-1">
        <p><span class="font-semibold">Nationalité:</span> {{ data.data[0].application.origin.nationality }}</p>
        <p><span class="font-semibold">Pays:</span> {{ data.data[0].application.origin.country }}</p>
        <p><span class="font-semibold">Ville:</span> {{ data.data[0].application.origin.city }}</p>
        <p><span class="font-semibold">Expérience:</span> {{ data.data[0].application.origin.experience_years }} ans</p>
        <p><span class="font-semibold">Français:</span> {{ data.data[0].application.origin.french_level }}</p>
        <p><span class="font-semibold">Anglais:</span> {{ data.data[0].application.origin.english_level }}</p>
      </div>
    </div>

    <!-- Identification -->
    <div class="bg-green-50 rounded-lg p-6 space-y-2" v-if="data.data[0]?.application?.identification">
      <h3 class="text-xl font-semibold text-green-700 mb-2">Identification</h3>
      <p><span class="font-semibold">Date de naissance:</span> {{ data.data[0].application.identification.birth_date }}</p>
      <p><span class="font-semibold">Adresse:</span> {{ data.data[0].application.identification.address }}</p>
      <p><span class="font-semibold">Genre:</span> {{ data.data[0].application.identification.gender }}</p>
    </div>

    <!-- CGIAR -->
    <div class="bg-yellow-50 rounded-lg p-6 space-y-2" v-if="data.data[0]?.application?.cgiar_information">
      <h3 class="text-xl font-semibold text-yellow-700 mb-2">CGIAR</h3>
      <p><span class="font-semibold">Actif:</span> {{ data.data[0].application.cgiar_information.current ? 'Oui' : 'Non' }}</p>
      <p><span class="font-semibold">Centre:</span> {{ data.data[0].application.cgiar_information.cgiar_center || '-' }}</p>
      <p><span class="font-semibold">Email:</span> {{ data.data[0].application.cgiar_information.cgiar_email || '-' }}</p>
    </div>

  </div>

  <!-- Diplomas -->
  <div class="bg-gray-50 rounded-lg p-6 space-y-4" v-if="data.data[0]?.application?.diplomas && data.data[0].application.diplomas.length">
    <h3 class="text-2xl font-bold  mb-2">Diplômes</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div v-for="d in data.data[0].application.diplomas" :key="d.id" class="bg-white-100 rounded-lg p-4">
        <p><span class="font-semibold">Diplôme:</span> {{ d.diploma }}</p>
        <p><span class="font-semibold">Option:</span> {{ d.option }}</p>
      </div>
    </div>
  </div>

  <!-- Experiences -->
  <div class=" rounded-lg p-6 space-y-4" v-if="data.data[0]?.application?.experiences && data.data[0].application.experiences.length">
    <h3 class="text-2xl font-bold  mb-2">Expériences professionnelles</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div v-for="e in data.data[0].application.experiences" :key="e.id" class="bg-gray-100 rounded-lg p-4">
        <p><span class="font-semibold">Entreprise:</span> {{ e.company_name }}</p>
        <p><span class="font-semibold">Poste:</span> {{ e.position }}</p>
        <p><span class="font-semibold">Début:</span> {{ e.start_date }}</p>
        <p><span class="font-semibold">Fin:</span> {{ e.end_date }}</p>
        <p><span class="font-semibold">Actuel:</span> {{ e.current ? 'Oui' : 'Non' }}</p>
      </div>
    </div>
  </div>

  <!-- Documents -->
  <div class="bg-teal-50 rounded-lg p-6 space-y-2" v-if="data.data[0]?.application?.documents && data.data[0].application.documents.length">
    <h3 class="text-2xl font-bold text-teal-800 mb-2">Documents</h3>
    <ul class="space-y-2">
      <li v-for="doc in data.data[0].application.documents" :key="doc.id">
        <a :href="`/${doc.path}`" target="_blank" class="text-teal-700 hover:underline">
          {{ doc.name }}
        </a>
      </li>
    </ul>
  </div>

</div>

 </div>
</template>


<style>

</style>