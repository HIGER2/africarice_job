
<script lang="ts" setup>
import ButtonBack from './ui/ButtonBack.vue';

defineProps({
  user: Object,
})
</script>

<template>
    <div class="w-full">
        <div class="max-w-6xl mx-auto p-8 space-y-10 " >
        <!-- <pre>{{ {user} }}</pre> -->
        <!-- Profil -->
        <div class="bg-gradient-to-r from-primary to-secondary text-white rounded-3xl p-8">
        <div class="flex flex-col items-center">
            <div class="w-24 h-24 bg-white text-indigo-600 rounded-full flex items-center justify-center text-3xl font-bold border border-gray-200 -lg">
            {{ user?.name?.charAt(0).toUpperCase() }}
            </div>
            <h1 class="mt-4 text-2xl font-bold">
            {{ user?.name }} {{ user?.last_name }}
            </h1>
            <p class="text-indigo-100 mt-1">📱 {{ user?.phone }} | ✉️ {{ user?.email }}</p>
            <span class="mt-3 px-4 py-1 bg-white text-indigo-700 rounded-full text-sm font-semibold border border-gray-200 ">
            {{ user?.role }}
            </span>
        </div>
        </div>

        <!-- Grid Infos -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Informations personnelles -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 ">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">🧑 Informations personnelles</h2>
            <ul class="space-y-2 text-gray-700">
            <li><strong>Date de naissance :</strong> {{ user?.application.identification.birth_date }}</li>
            <li><strong>Adresse :</strong> {{ user?.application.identification.address }}</li>
            <li><strong>Genre :</strong> {{ user?.application.identification.gender }}</li>
            </ul>
        </div>

        <!-- Origine -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 ">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">🌍 Origine & Compétences</h2>
            <ul class="space-y-2 text-gray-700">
            <li><strong>Pays :</strong> {{ user?.application.origin.country }} ({{ user?.application.origin.city }})</li>
            <li><strong>Nationalité :</strong> {{ user?.application.origin.nationality }}</li>
            <li><strong>Expérience :</strong> {{ user?.application.origin.experience_years }} ans</li>
            <li><strong>Français :</strong> {{ user?.application.origin.french_level }}</li>
            <li><strong>Anglais :</strong> {{ user?.application.origin.english_level }}</li>
            </ul>
        </div>
        </div>

        <!-- Diplômes -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 ">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">🎓 Diplômes</h2>
        <ul class="list-disc pl-6 text-gray-700 space-y-1">
            <li v-for="d in user?.application.diplomas" :key="d.uuid">
            {{ d.diploma }} <span class="text-sm text-gray-500">(Option : {{ d.option }})</span>
            </li>
        </ul>
        </div>

        <!-- Expériences -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 ">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">💼 Expériences professionnelles</h2>
        <ul class="divide-y divide-gray-200">
            <li v-for="exp in user?.application.experiences" :key="exp.uuid" class="py-3">
            <p class="font-medium">{{ exp.position }} chez {{ exp.company_name }}</p>
            <p class="text-sm text-gray-500">{{ exp.start_date }} → {{ exp.current ? "Actuel" : exp.end_date }}</p>
            </li>
        </ul>
        </div>

        <!-- Références -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 ">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">📇 Références</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div v-for="ref in user?.application.references" :key="ref.uuid" class="border p-4 rounded-xl bg-gray-50 border border-gray-200 -sm">
            <p class="font-semibold text-gray-800">{{ ref.full_name }}</p>
            <p class="text-sm text-gray-500">{{ ref.function }}</p>
            <p class="text-sm">📱 {{ ref.phone }}</p>
            <p class="text-sm">✉️ {{ ref.email }}</p>
            </div>
        </div>
        </div>

        <!-- Documents -->
        <!-- <pre>{{ user?.application.documents }}</pre> -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 " v-if="user?.application.documents?.length">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">📂 Documents</h2>
        <ul class="list-disc pl-6 text-gray-700">
            <li v-for="doc in user?.application.documents" :key="doc.uuid">
        <a 
            :href="`/${doc.path}`" 
            target="_blank" 
            download
            class="flex cursor-pointer items-center gap-2 text-indigo-600 hover:text-indigo-800 hover:underline"
        >
            📄 <span>{{ doc.name }}</span>
        </a>
        </li>
        </ul>
        </div>
    </div>
    </div>
</template>


<style>

</style>