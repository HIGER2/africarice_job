
<script lang="ts" setup>
defineProps({
  user: Object,
})
</script>


<template>
  <div class="max-w-5xl mx-auto p-6 space-y-8">
    <!-- Header -->
    <div class="bg-white  rounded-2xl p-6 text-center">
      <h1 class="text-2xl font-bold">{{ user?.data?.name }} {{ user?.data?.last_name }}</h1>
      <p class="text-gray-500">📱 {{ user?.data?.phone }} | ✉️ {{ user?.data?.email }}</p>
      <span class="inline-block mt-2 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm font-semibold">
        {{ user?.data?.role }}
      </span>
    </div>

    <!-- Informations personnelles -->
    <div class="bg-white border-b border-gray-200  p-6">
      <h2 class="text-xl font-semibold mb-4">Informations personnelles</h2>
      <ul class="space-y-2 text-gray-700">
        <li><strong>Date de naissance :</strong> {{ user?.data?.application.identification.birth_date }}</li>
        <li><strong>Adresse :</strong> {{ user?.data?.application.identification.address }}</li>
        <li><strong>Genre :</strong> {{ user?.data?.application.identification.gender }}</li>
      </ul>
    </div>

    <!-- Origine -->
    <div class="bg-white border-b border-gray-200  p-6">
      <h2 class="text-xl font-semibold mb-4">Origine & Compétences</h2>
      <ul class="space-y-2 text-gray-700">
        <li><strong>Pays :</strong> {{ user?.data?.application.origin.country }} ({{ user?.data?.application.origin.city }})</li>
        <li><strong>Nationalité :</strong> {{ user?.data?.application.origin.nationality }}</li>
        <li><strong>Expérience :</strong> {{ user?.data?.application.origin.experience_years }} ans</li>
        <li><strong>Niveau Français :</strong> {{ user?.data?.application.origin.french_level }}</li>
        <li><strong>Niveau Anglais :</strong> {{ user?.data?.application.origin.english_level }}</li>
      </ul>
    </div>

    <!-- Diplômes -->
    <div class="bg-white border-b border-gray-200  p-6">
      <h2 class="text-xl font-semibold mb-4">Diplômes</h2>
      <ul class="list-disc pl-6 text-gray-700">
        <li v-for="d in user?.data?.application.diplomas" :key="d.uuid">
          🎓 {{ d.diploma }} (Option : {{ d.option }})
        </li>
      </ul>
    </div>

    <!-- Expériences -->
    <div class="bg-white border-b border-gray-200  p-6">
      <h2 class="text-xl font-semibold mb-4">Expérience professionnelle</h2>
      <ul class="divide-y divide-gray-200">
        <li v-for="exp in user?.data?.application.experience" :key="exp.uuid" class="py-2">
          <p class="font-medium">{{ exp.position }} chez {{ exp.company_name }}</p>
          <p class="text-sm text-gray-500">{{ exp.start_date }} → {{ exp.current ? "Actuel" : exp.end_date }}</p>
        </li>
      </ul>
    </div>

    <!-- Documents -->
    <div class="bg-white border-b border-gray-200  p-6">
      <h2 class="text-xl font-semibold mb-4">Documents</h2>
      <ul class="list-disc pl-6 text-gray-700">
        <li v-for="doc in user?.data?.application.documents" :key="doc.uuid">
          📄 {{ doc.name }}
        </li>
      </ul>
    </div>

    <!-- Références -->
    <div class="bg-white border-b border-gray-200  p-6">
      <h2 class="text-xl font-semibold mb-4">Références</h2>
      <ul class="space-y-3">
        <li v-for="ref in user?.data?.application.references" :key="ref.uuid" class="border p-3 rounded-lg bg-gray-50">
          <p class="font-semibold">{{ ref.name }}</p>
          <p class="text-sm text-gray-500">{{ ref.function }}</p>
          <p class="text-sm">📱 {{ ref.phone }} | ✉️ {{ ref.email }}</p>
        </li>
      </ul>
    </div>
  </div>
</template>

<style>

</style>