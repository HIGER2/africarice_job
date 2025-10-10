
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
  
    <!-- <nav class="bg-white/95 backdrop-blur-md border-b border-gray-200 sticky top-0 z-20 px-4 p-3 shadow-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <img src="https://static.wixstatic.com/media/0839e4_7910df264aee46ba85347ab33684d739~mv2_d_4782_1488_s_2.png/v1/fill/w_520,h_160,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/0839e4_7910df264aee46ba85347ab33684d739~mv2_d_4782_1488_s_2.png" alt="Logo" 
                class="w-40 object-contain" />
            </div>
            <template v-if="user">
                <AuthUser :user />
            </template>
            <template v-else>
                <a href="/login" class="px-6 py-2.5 bg-gradient-to-r from-primary to-se text-white rounded-full font-medium hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                    Connexion
                </a>
            </template>
        </div>
    </nav> -->
    <NavBar :user />

    <section class="relative bg-black text-white">
        <div class="absolute inset-0 overflow-hidden">
            <img src="https://static.wixstatic.com/media/0839e4_237664be86134106b6ff95a1becaeb9a~mv2_d_4608_2166_s_2.jpg/v1/fill/w_1898,h_750,al_c,q_85,usm_2.00_1.00_0.00,enc_avif,quality_auto/0839e4_237664be86134106b6ff95a1becaeb9a~mv2_d_4608_2166_s_2.jpg" 
                alt="Hero Illustration" class="w-full h-full object-cover" />
            <div class="absolute inset-0 gradient-overlay"></div>
        </div>

        <div class="relative z-10 flex flex-col h-[450px] items-center justify-center text-center py-20 px-6">
            <h1 class="text-5xl md:text-7xl font-extrabold mb-7 drop-shadow-lg">
                Our Vacancies
                <!-- / <span class="text-white">Nos postes vacants</span>  -->
            </h1>
            <!-- <p class="text-xl text-blue-100 mb-6 max-w-2xl">
                Découvrez les meilleures opportunités de carrière
            </p> -->
            <div class="flex gap-4 justify-center flex-wrap">
                <div class="bg-white/20 backdrop-blur-md px-6 py-3 rounded-full text-white font-medium">
                    <i class="uil uil-briefcase mr-2"></i>{{ publications?.data?.length }} Positions
                </div>
                <div class="bg-white/20 backdrop-blur-md px-6 py-3 rounded-full text-white font-medium">
                    <i class="uil uil-map-marker mr-2"></i>Multiple Countries
                </div>
            </div>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <section class="w-full space-y-6">
            <div class="mb-8">
                <h1 class="text-4xl font-black text-gray-900 mb-2">
                    Job offers 
                    <span class="text-secondary">({{ publications?.data?.length }})</span>
                </h1>
               <p class="text-gray-600 text-lg">Click on a job offer to see the details</p>
            </div>

            <div 
  v-for="pub in publications?.data" 
  :key="pub?.uuid" 
  class="job-card w-full bg-white p-6 shadow hover:shadow-md cursor-pointer rounded-2xl border border-gray-100"
>
  <div class="flex flex-col md:flex-row justify-between items-start gap-6">
    <div class="flex gap-5 items-start flex-1 w-full">
      <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
        <span class="font-bold text-secondary text-xl">
          {{ initials(pub?.job?.position_title || 'N/A') }}
        </span>
      </div>

      <div class="flex-1 w-full">
        <div class="flex flex-col md:flex-row md:items-start justify-between mb-3 gap-2">
          <div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ pub.job.position_title }}</h3>
            <div class="flex flex-wrap md:flex-nowrap items-center gap-3 text-sm text-gray-600">
              <span class="flex items-center gap-1">
                <i class="uil uil-building text-blue-600"></i>
                {{ pub.job.center }}
              </span>
              <span class="flex items-center gap-1">
                <i class="uil uil-map-marker text-amber-500"></i>
                {{ pub.job.city_duty_station }}, {{ pub.job.country_duty_station }}
              </span>
            </div>
          </div>
        </div>

        <div class="flex flex-wrap gap-2 mb-4">
          <span class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium border border-blue-200">
            <i class="uil uil-briefcase-alt"></i> {{ pub.type }}
          </span>
          <span class="px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-xs font-medium border border-green-200">
            <i class="uil uil-calendar-alt"></i> Published: {{ formatDate(pub.published_at) }}
          </span>
          <span class="px-3 py-1.5 bg-orange-50 text-orange-700 rounded-lg text-xs font-medium border border-orange-200">
            <i class="uil uil-clock"></i> Expires: {{ formatDate(pub.expires_at) }}
          </span>
        </div>
      </div>
    </div>

    <div class="flex flex-col md:items-end gap-3 w-full md:w-auto">
      <button 
        @click="toggleDetails(pub.uuid)" 
        class="px-3 py-2 bg-white border-2 border-secondary text-secondary rounded-xl font-medium hover:bg-secondary cursor-pointer hover:text-white transition-all duration-200 w-full md:min-w-[120px]"
      >
        <i class="uil uil-eye"></i>
        {{ opened.includes(pub.uuid) ? 'Close' : 'View' }}
      </button>
      <a 
        :href="`/apply-job/${pub.uuid}`" 
        class="px-3 py-2 bg-gradient-to-r bg-primary text-white rounded-xl font-medium hover:shadow-lg transform hover:scale-105 transition-all duration-200 w-full md:min-w-[120px] text-center"
      >
        <i class="uil uil-message"></i> Apply
      </a>
    </div>
  </div>

  <transition name="fade">
    <div 
      v-if="opened.includes(pub.uuid)" 
      class="mt-4 border-t pt-3 text-sm text-gray-600 space-y-2"
    >
      <p><strong>Type:</strong> {{ pub.type }}</p>
      <p><strong>Published on:</strong> {{ formatDate(pub.published_at) }}</p>
      <p><strong>Expires on:</strong> {{ formatDate(pub.expires_at) }}</p>
      <p><strong>Status:</strong> 
        <span v-if="pub.is_closed" class="text-red-500">Closed</span>
        <span v-else class="text-green-600">Active</span>
      </p>

      <div class="max-w-max">
        <h2 class="text-lg font-semibold mb-4">Documents</h2>

        <div v-if="pub.files.length > 0" class="space-y-2">
          <div 
            v-for="(file, index) in pub.files" 
            :key="index" 
            class="flex flex-col md:flex-row items-start md:items-center justify-between p-3 border rounded hover:bg-gray-50"
          >
            <span class="truncate max-w-xs">{{ file.name }}</span>
            <a 
              :href="`/${file.url}`" 
              :download="file.name"
              class="text-blue-600 hover:underline flex items-center space-x-1 mt-2 md:mt-0"
            >
              <i class="uil uil-export"></i>
              <span>Download</span>
            </a>
          </div>
        </div>

        <div v-else class="text-gray-500">No documents available.</div>
      </div>
    </div>
  </transition>
</div>


            <!-- <div 
              v-for="pub in publications?.data" 
              :key="pub?.uuid" 
              class="job-card w-full bg-white p-6 shadow  hover:shadow-md cursor-pointer rounded-2xl border border-gray-100"
            >
            <div class="flex justify-between items-start gap-6">
                  <div class="flex gap-5 items-start flex-1">
                      <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                          <span class="font-bold text-secondary text-xl">
                              {{ initials(pub?.job?.position_title || 'N/A') }}
                          </span>
                      </div>
                      
                      <div class="flex-1">
                          <div class="flex items-start justify-between mb-3">
                              <div>
                                  <h3 class="text-xl font-bold text-gray-900 mb-2">{{ pub.job.position_title }}</h3>
                                  <div class="flex items-center gap-3 text-sm text-gray-600">
                                      <span class="flex items-center gap-1">
                                          <i class="uil uil-building text-blue-600"></i>
                                          {{ pub.job.center }}
                                      </span>
                                      <span class="flex items-center gap-1">
                                          <i class="uil uil-map-marker text-amber-500"></i>
                                          {{ pub.job.city_duty_station }}, {{ pub.job.country_duty_station }}
                                      </span>
                                  </div>
                              </div>
                          </div>
                          
                          <div class="flex flex-wrap gap-2 mb-4">
                              <span class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium border border-blue-200">
                                  <i class="uil uil-briefcase-alt"></i> {{ pub.type }}
                              </span>
                              <span class="px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-xs font-medium border border-green-200">
                                  <i class="uil uil-calendar-alt"></i> Published: {{ formatDate(pub.published_at) }}
                              </span>
                              <span class="px-3 py-1.5 bg-orange-50 text-orange-700 rounded-lg text-xs font-medium border border-orange-200">
                                  <i class="uil uil-clock"></i> Expires: {{ formatDate(pub.expires_at) }}
                              </span>
                          </div>
                      </div>
                  </div>

                  <div class="flex flex-col gap-3 items-end">
                      <button 
                          @click="toggleDetails(pub.uuid)" 
                          class="px-3 py-2 bg-white border-2 border-secondary text-secondary rounded-xl font-medium hover:bg-secondary cursor-pointer hover:text-white transition-all duration-200 min-w-[120px]"
                      >
                          <i class="uil uil-eye"></i>
                          {{ opened.includes(pub.uuid) ? 'Close' : 'View' }}
                      </button>
                      <a 
                          :href="`/apply-job/${pub.uuid}`" 
                          class="px-3 py-2 bg-gradient-to-r bg-primary text-white rounded-xl font-medium hover:shadow-lg transform hover:scale-105 transition-all duration-200 min-w-[120px] text-center"
                      >
                          <i class="uil uil-message"></i> Apply
                      </a>
                  </div>
              </div>

              <transition name="fade">
                  <div 
                      v-if="opened.includes(pub.uuid)" 
                      class="mt-4 border-t pt-3 text-sm text-gray-600 space-y-2"
                  >
                      <p><strong>Type:</strong> {{ pub.type }}</p>
                      <p><strong>Published on:</strong> {{ formatDate(pub.published_at) }}</p>
                      <p><strong>Expires on:</strong> {{ formatDate(pub.expires_at) }}</p>
                      <p><strong>Status:</strong> 
                          <span v-if="pub.is_closed" class="text-red-500">Closed</span>
                          <span v-else class="text-green-600">Active</span>
                      </p>
                      <div class="max-w-max">
                          <h2 class="text-lg font-semibold mb-4">Documents</h2>

                          <div v-if="pub.files.length > 0" class="space-y-2">
                              <div 
                                  v-for="(file, index) in pub.files" 
                                  :key="index" 
                                  class="flex items-center justify-between p-3 border rounded hover:bg-gray-50"
                              >
                                  <span class="truncate max-w-xs">{{ file.name }}</span>
                                  <a 
                                      :href="`/${file.url}`" 
                                      :download="file.name"
                                      class="text-blue-600 hover:underline flex items-center space-x-1"
                                  >
                                      <i class="uil uil-export"></i>
                                      <span>Download</span>
                                  </a>
                              </div>
                          </div>

                          <div v-else class="text-gray-500">No documents available.</div>
                      </div>
                  </div>
              </transition>

              
            </div> -->
        </section>
    </main>
</template>
  <style scoped>
    .gradient-overlay {
        background: linear-gradient(135deg, rgba(30, 64, 175, 0.55) 0%, rgba(124, 58, 237, 0.45) 100%);
    }
    .job-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .job-card:hover {
        transform: translateY(-4px);
    }
    .fade-enter-active, .fade-leave-active {
        transition: all 0.3s ease;
    }
    .fade-enter-from, .fade-leave-to {
        opacity: 0;
        transform: translateY(-10px);
    }
    </style>

