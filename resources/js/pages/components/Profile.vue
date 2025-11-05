
<script lang="ts" setup>
import { ref } from 'vue';
import ButtonBack from './ui/ButtonBack.vue';
import UploadCv from './UploadCv.vue';
import UpdateUser from './UpdateUser.vue';
import { useApplyForm } from '../composables';

defineProps({
  user: Object,
})

const isOpen = ref(false)
const setOPen=(data)=>{
    isOpen.value = data
}
const isLoading=ref(false)
const {deleteItemApplication}=useApplyForm()

const handleDelete=async(id,type)=>{
    if(isLoading.value) return false
    isLoading.value  =true
    await deleteItemApplication({
        uuid: id,
        type : type
    })

    isLoading.value  =false
    
}
</script>

<template>
    <div class="max-w-4xl mx-auto">
        <div class="space-y-10 " >
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
                    <p class="text-indigo-100 mt-1">📱 {{ user?.phone_code }} | ✉️ {{ user?.email }}</p>
                    <div class="flex items-center gap-3">
                        <button @click="setOPen(user)" type="button" class="px-5 cursor-pointer py-1 text-sm border rounded-md mt-3">
                                Edit profile
                        </button>
                        <a href="/apply-job" type="button" class="px-5 cursor-pointer py-1 text-sm border rounded-md mt-3">
                               Update my information
                        </a>
                    </div>
                    <UpdateUser :row="isOpen" @close="()=>setOPen(false)"/>

                    <!-- <span class="mt-3 px-4 py-1 bg-white text-indigo-700 rounded-full text-sm font-semibold border border-gray-200 ">
                    {{ user?.role }}
                    </span> -->
                </div>
            </div>
            <!-- Grid Infos -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Origin -->
                    <div v-if="user?.application?.origin" class="bg-gray-50 rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-2 text-gray-800">Origine & Compétences</h2>
                    <ul class="text-gray-700 space-y-1 text-sm">
                        <li><strong>Nationalité:</strong> {{ user.application.origin.nationality }}</li>
                        <li><strong>Pays:</strong> {{ user.application.origin.country }}</li>
                        <li><strong>Ville:</strong> {{ user.application.origin.city }}</li>
                        <li><strong>Expérience:</strong> {{ user.application.origin.experience_years }} ans</li>
                        <li><strong>Français:</strong> {{ user.application.origin.french_level }}</li>
                        <li><strong>Anglais:</strong> {{ user.application.origin.english_level }}</li>
                    </ul>
                    </div>

                    <!-- Identification -->
                    <div v-if="user?.application?.identification" class="bg-gray-50 rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-2 text-gray-800">Identification</h2>
                    <ul class="text-gray-700 space-y-1 text-sm">
                        <li><strong>Date de naissance:</strong> {{ user.application.identification.birth_date }}</li>
                        <li><strong>Adresse:</strong> {{ user.application.identification.address }}</li>
                        <li><strong>Genre:</strong> {{ user.application.identification.gender }}</li>
                    </ul>
                    </div>

                    <!-- CGIAR -->
                    <div v-if="user?.application?.cgiar_information" class="bg-gray-50 rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-2 text-gray-800">CGIAR</h2>
                    <ul class="text-gray-700 space-y-1 text-sm">
                        <li><strong>Actif:</strong> {{ user.application.cgiar_information.current ? 'Oui' : 'Non' }}</li>
                        <li><strong>Centre:</strong> {{ user.application.cgiar_information.cgiar_center || '-' }}</li>
                        <li><strong>Email:</strong> {{ user.application.cgiar_information.cgiar_email || '-' }}</li>
                    </ul>
                    </div>

                </div>
            <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
                <div class="bg-white rounded-2xl p-6 border border-gray-200 ">
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">🧑 Informations personnelles</h2>
                    <ul class="space-y-2 text-gray-700">
                    <li><strong>Date de naissance :</strong> {{ user?.application.identification.birth_date }}</li>
                    <li><strong>Adresse :</strong> {{ user?.application.identification.address }}</li>
                    <li><strong>Genre :</strong> {{ user?.application.identification.gender }}</li>
                    </ul>
                </div>

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
            </div> -->

            <!-- Diplômes -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 ">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">🎓 Diplômes</h2>
            <ul class="list-disc pl-6 text-gray-700 space-y-1">
                <li v-for="d in user?.application?.diplomas" :key="d.uuid"
                class="flex gap-2 items-center"
                >
                    {{ d.diploma }} <span class="text-sm text-gray-500">(Option : {{ d.option }})</span>
                    <button 
                    :disabled="isLoading"
                        @click="handleDelete(d.uuid,'diplomas')"
                        class="text-[16px] text-red-600 w-11 h-11 rounded-full cursor-pointer hover:bg-red-50 text-center"
                        type="button" 
                        title="delete diploma">
                        <i class="uil uil-trash"></i>
                    </button>
                </li>
            </ul>
            </div>

            <!-- Expériences -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 ">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">💼 Expériences professionnelles</h2>
            <ul class="divide-y divide-gray-200">
                <li v-for="exp in user?.application?.experiences" :key="exp.uuid" 
                class="py-3  flex items-end gap-1">
                <div>
                    <p class="font-medium">{{ exp.position }} chez {{ exp.company_name }}</p>
                    <p class="text-sm text-gray-500">{{ exp.start_date }} → {{ exp.current ? "Actuel" : exp.end_date }}</p>
                </div>
                <button 
                    :disabled="isLoading"
                    @click="handleDelete(exp.uuid,'experiences')"
                    class="text-[16px] text-red-600 w-11 h-11 rounded-full cursor-pointer hover:bg-red-50 text-center"
                    type="button" 
                    title="delete diploma">
                    <i class="uil uil-trash"></i>
                </button>
            </li>
            </ul>
            </div>

            <!-- Références -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 ">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">📇 Références</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div v-for="ref in user?.application?.references" :key="ref.uuid" 
                class=" flex justify-between items-start p-4 rounded-xl bg-gray-50 border border-gray-200 -sm">
                    <div>
                        <p class="font-semibold text-gray-800">{{ ref.full_name }}</p>
                        <p class="text-sm text-gray-500">{{ ref.function }}</p>
                        <p class="text-sm">📱 {{(ref.country_code ?? '')+ref.phone }}</p>
                        <p class="text-sm">✉️ {{ ref.email }}</p>
                    </div>
                    <button 
                        :disabled="isLoading"
                        @click="handleDelete(ref.uuid,'references')"
                        class="text-[16px] text-red-600 w-11 h-11 rounded-full cursor-pointer hover:bg-red-50 text-center"
                        type="button" 
                        title="delete diploma">
                        <i class="uil uil-trash"></i>
                    </button>
                </div>
            </div>
            </div>

            <!-- Documents -->
            <!-- <pre>{{ user?.application.documents }}</pre> -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 ">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">📂 Documents</h2>
            <div class="flex items-center gap-2">
                <ul class="list-disc pl-6 text-gray-700">
                    <li v-for="doc in user?.application?.documents" :key="doc.uuid">
                <a 
                    :href="doc.path" 
                    :download="doc.name"
                    class="flex truncate w-full cursor-pointer items-center gap-2 text-indigo-600 hover:text-indigo-800 hover:underline"
                >
                    📄 <span>{{ doc.name }}</span>
                </a>
                </li>
                </ul>
                <UploadCv/>
            </div>
            </div>
        </div>
    </div>
</template>


<style>

</style>