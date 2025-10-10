
<script setup lang="ts">
import { ref, computed,defineProps } from 'vue'
import AuthUser from './components/AuthUser.vue'
import NavBar from './components/NavBar.vue'

const q = ref('')
const props = defineProps({
  user: Object,
  publications: Array
})


const opened = ref<string[]>([])

function toggleDetails(uuid: string) {
  if (opened.value.includes(uuid)) {
    opened.value = opened.value.filter(id => id !== uuid)
  } else {
    opened.value.push(uuid)
  }
}

function initials(name: string) {
  if (!name) return "?"   // sécurité si jamais la donnée est vide
 
  return name
    .split(" ")[0][0]
    .toUpperCase()
  
  return name
    .split(" ")
    .map(n => n[0])
    .join("")
    .toUpperCase()
}

  function formatDate(date: string) {
    return new Date(date).toLocaleDateString("fr-FR", {
    year: "numeric",
    month: "long",
    day: "numeric"
  })
}

function openApply(pub: any) {
  console.log("Apply for", pub)
}


</script>
<template>
  
    <nav class="bg-white/98 backdrop-blur-lg border-b border-gray-200 sticky top-0 z-20 px-4 p-3 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <img src="https://static.wixstatic.com/media/0839e4_7910df264aee46ba85347ab33684d739~mv2_d_4782_1488_s_2.png/v1/fill/w_520,h_160,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/0839e4_7910df264aee46ba85347ab33684d739~mv2_d_4782_1488_s_2.png" alt="Logo" 
                class="w-44 object-contain" />
            </div>
            <template v-if="user">
                <AuthUser :user />
            </template>
            <template v-else>
                <a href="/login" class="px-7 py-3 badge-primary text-white rounded-full font-semibold hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                    <i class="uil uil-user"></i>
                    <span>Connexion</span>
                </a>
            </template>
        </div>
    </nav>

    <section class="relative bg-black text-white overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://static.wixstatic.com/media/0839e4_237664be86134106b6ff95a1becaeb9a~mv2_d_4608_2166_s_2.jpg/v1/fill/w_1898,h_750,al_c,q_85,usm_2.00_1.00_0.00,enc_avif,quality_auto/0839e4_237664be86134106b6ff95a1becaeb9a~mv2_d_4608_2166_s_2.jpg" 
                alt="Hero Illustration" class="w-full h-full object-cover" />
            <div class="absolute inset-0 gradient-overlay"></div>
        </div>

        <div class="relative z-10 flex flex-col h-[480px] items-center justify-center text-center py-20 px-6">
            <div class="mb-4 inline-block px-5 py-2 bg-white/20 backdrop-blur-md rounded-full text-sm font-semibold text-white">
                <i class="uil uil-briefcase mr-2"></i>Opportunités de Carrière
            </div>
            <h1 class="text-5xl md:text-7xl font-black mb-5 drop-shadow-2xl leading-tight">
                Our Vacancies / <br>
                <span class="text-green-400">Nos Postes Vacants</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-100 mb-8 max-w-3xl leading-relaxed">
                Rejoignez notre équipe et construisez votre avenir professionnel
            </p>
            <div class="flex gap-5 justify-center flex-wrap mt-6">
                <div class="bg-white/25 backdrop-blur-lg px-8 py-4 rounded-2xl text-white font-bold text-lg shadow-xl">
                    <i class="uil uil-estate mr-2 text-green-300"></i>{{ publications?.data?.length }} Postes Disponibles
                </div>
                <div class="bg-white/25 backdrop-blur-lg px-8 py-4 rounded-2xl text-white font-bold text-lg shadow-xl">
                    <i class="uil uil-globe mr-2 text-green-300"></i>Afrique de l'Ouest
                </div>
            </div>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <section class="w-full space-y-8">
            <div class="mb-12">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-12 w-2 badge-primary rounded-full"></div>
                    <h1 class="text-5xl font-black text-gray-900">
                        Offres d'emploi
                        <span class="text-green-700">({{ publications?.data?.length }})</span>
                    </h1>
                </div>
                <p class="text-gray-600 text-xl ml-6">Explorez nos opportunités et postulez dès maintenant</p>
            </div>

            <div 
              v-for="pub in publications?.data" 
              :key="pub?.uuid" 
              class="job-card w-full p-8 shadow-lg hover:shadow-2xl cursor-pointer rounded-3xl border-2 border-gray-100"
            >
                <div class="flex justify-between items-start gap-8 flex-wrap">
                    <div class="flex gap-6 items-start flex-1 min-w-[300px]">
                        <div class="w-20 h-20 badge-primary rounded-2xl flex items-center justify-center shadow-xl flex-shrink-0 transform hover:rotate-6 transition-transform duration-300">
                            <span class="font-black text-white text-2xl">
                                {{ initials(pub?.job?.position_title || 'N/A') }}
                            </span>
                        </div>
                        
                        <div class="flex-1">
                            <div class="mb-4">
                                <h3 class="text-2xl font-black text-gray-900 mb-3 leading-tight">{{ pub.job.position_title }}</h3>
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                    <span class="flex items-center gap-2 bg-gray-100 px-4 py-2 rounded-xl font-medium">
                                        <i class="uil uil-building text-green-700 text-lg"></i>
                                        {{ pub.job.center }}
                                    </span>
                                    <span class="flex items-center gap-2 bg-gray-100 px-4 py-2 rounded-xl font-medium">
                                        <i class="uil uil-map-marker text-gray-700 text-lg"></i>
                                        {{ pub.job.city_duty_station }}, {{ pub.job.country_duty_station }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap gap-3">
                                <span class="px-4 py-2 bg-green-50 text-green-800 rounded-xl text-sm font-bold border-2 border-green-200 flex items-center gap-2">
                                    <i class="uil uil-briefcase-alt"></i> 
                                    {{ pub.type }}
                                </span>
                                <span class="px-4 py-2 bg-blue-50 text-blue-800 rounded-xl text-sm font-bold border-2 border-blue-200 flex items-center gap-2">
                                    <i class="uil uil-calendar-alt"></i> 
                                    Publié: {{ formatDate(pub.published_at) }}
                                </span>
                                <span class="px-4 py-2 bg-orange-50 text-orange-800 rounded-xl text-sm font-bold border-2 border-orange-200 flex items-center gap-2">
                                    <i class="uil uil-hourglass"></i> 
                                    Expire: {{ formatDate(pub.expires_at) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 items-stretch min-w-[140px]">
                        <button 
                            @click="toggleDetails(pub.uuid)" 
                            class="px-6 py-3 bg-white border-3 border-gray-800 text-gray-800 rounded-2xl font-bold hover:bg-gray-800 hover:text-white transition-all duration-300 shadow-md hover:shadow-xl flex items-center justify-center gap-2"
                        >
                            <i :class="opened.includes(pub.uuid) ? 'uil uil-eye-slash' : 'uil uil-eye'"></i>
                            {{ opened.includes(pub.uuid) ? 'Fermer' : 'Détails' }}
                        </button>
                        <a 
                            :href="`/apply-job/${pub.uuid}`" 
                            class="px-6 py-3 badge-primary text-white rounded-2xl font-bold hover:shadow-2xl transform hover:scale-105 transition-all duration-300 text-center flex items-center justify-center gap-2"
                        >
                            <i class="uil uil-message"></i> 
                            Postuler
                        </a>
                    </div>
                </div>

                <transition name="fade">
                    <div 
                        v-if="opened.includes(pub.uuid)" 
                        class="mt-8 pt-8 border-t-2 border-gray-200 space-y-6"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                            <div class="detail-card bg-gradient-to-br from-green-50 to-emerald-100 p-5 rounded-2xl shadow-md hover:shadow-lg transition-shadow">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center">
                                        <i class="uil uil-briefcase text-white text-lg"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-600">Type de contrat</p>
                                </div>
                                <p class="font-black text-gray-900 text-lg ml-13">{{ pub.type }}</p>
                            </div>
                            
                            <div class="detail-card bg-gradient-to-br from-blue-50 to-cyan-100 p-5 rounded-2xl shadow-md hover:shadow-lg transition-shadow">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                                        <i class="uil uil-calendar-alt text-white text-lg"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-600">Date de publication</p>
                                </div>
                                <p class="font-black text-gray-900 text-lg ml-13">{{ formatDate(pub.published_at) }}</p>
                            </div>
                            
                            <div class="detail-card bg-gradient-to-br from-orange-50 to-amber-100 p-5 rounded-2xl shadow-md hover:shadow-lg transition-shadow">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center">
                                        <i class="uil uil-clock text-white text-lg"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-600">Date d'expiration</p>
                                </div>
                                <p class="font-black text-gray-900 text-lg ml-13">{{ formatDate(pub.expires_at) }}</p>
                            </div>
                            
                            <div class="detail-card p-5 rounded-2xl shadow-md hover:shadow-lg transition-shadow" :class="pub.is_closed ? 'bg-gradient-to-br from-red-50 to-rose-100' : 'bg-gradient-to-br from-emerald-50 to-green-100'">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" :class="pub.is_closed ? 'bg-red-600' : 'bg-green-600'">
                                        <i class="uil text-white text-lg" :class="pub.is_closed ? 'uil-times-circle' : 'uil-check-circle'"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-600">Statut</p>
                                </div>
                                <p v-if="pub.is_closed" class="font-black text-red-700 text-lg ml-13 flex items-center gap-2">
                                    <span class="w-3 h-3 bg-red-600 rounded-full"></span>
                                    Fermée
                                </p>
                                <p v-else class="font-black text-green-700 text-lg ml-13 flex items-center gap-2">
                                    <span class="w-3 h-3 bg-green-600 rounded-full pulse-dot"></span>
                                    Active
                                </p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-gray-50 via-green-50 to-gray-100 p-8 rounded-3xl border-2 border-gray-200 shadow-lg">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 badge-dark rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="uil uil-file-download-alt text-white text-2xl"></i>
                                </div>
                                <h2 class="text-2xl font-black text-gray-900">Documents à télécharger</h2>
                            </div>

                            <div v-if="pub.files.length > 0" class="space-y-4">
                                <div 
                                    v-for="(file, index) in pub.files" 
                                    :key="index" 
                                    class="flex items-center justify-between p-5 bg-white rounded-2xl hover:shadow-xl transition-all border-2 border-gray-100 hover:border-green-300 group"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                            <i class="uil uil-file-alt text-white text-2xl"></i>
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-900 text-lg block">{{ file.name }}</span>
                                            <span class="text-sm text-gray-500">Document PDF</span>
                                        </div>
                                    </div>
                                    <a 
                                        :href="`/${file.url}`" 
                                        :download="file.name"
                                        class="px-6 py-3 badge-primary text-white font-bold rounded-xl flex items-center gap-2 transition-all hover:shadow-lg transform hover:scale-105"
                                    >
                                        <i class="uil uil-download-alt text-xl"></i>
                                        <span>Télécharger</span>
                                    </a>
                                </div>
                            </div>

                            <div v-else class="text-center py-12">
                                <div class="inline-block p-6 bg-gray-100 rounded-3xl mb-4">
                                    <i class="uil uil-folder-open text-6xl text-gray-400"></i>
                                </div>
                                <p class="font-bold text-gray-600 text-lg">Aucun document disponible pour le moment</p>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </section>
    </main>
</template>

  <style scoped>
    :root {
        --color-primary: #333;
        --color-secondary: #31732c;
    }
    
    .gradient-overlay {
        background: linear-gradient(135deg, rgba(51, 51, 51, 0.88) 0%, rgba(49, 115, 44, 0.85) 100%);
    }
    
    .job-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
    }
    
    .job-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(49, 115, 44, 0.15);
    }
    
    .badge-primary {
        background: linear-gradient(135deg, var(--color-secondary) 0%, #3d8f35 100%);
    }
    
    .badge-dark {
        background: linear-gradient(135deg, var(--color-primary) 0%, #4a4a4a 100%);
    }
    
    .detail-card {
        position: relative;
        overflow: hidden;
    }
    
    .detail-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, var(--color-secondary) 0%, var(--color-primary) 100%);
    }
    
    .fade-enter-active, .fade-leave-active {
        transition: all 0.4s ease;
    }
    .fade-enter-from, .fade-leave-to {
        opacity: 0;
        transform: translateY(-15px);
    }
    
    .pulse-dot {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: .5;
        }
    }
    </style>

