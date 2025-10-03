
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
  <div class="min-h-screen">
    <NavBar :user />
    <section class="relative bg-black text-white">
        <!-- Image en arrière-plan -->
        <div class="absolute  inset-0 overflow-hidden">
            <img src="https://static.wixstatic.com/media/0839e4_237664be86134106b6ff95a1becaeb9a~mv2_d_4608_2166_s_2.jpg/v1/fill/w_1898,h_750,al_c,q_85,usm_2.00_1.00_0.00,enc_avif,quality_auto/0839e4_237664be86134106b6ff95a1becaeb9a~mv2_d_4608_2166_s_2.jpg" 
                alt="Hero Illustration" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-black opacity-50"></div>
        </div>

        <!-- Texte centré au-dessus de l'image -->
        <div class="relative z-10 flex flex-col items-center justify-center text-center py-20 px-6">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-6">Découvrez les opportunités chez AfricaRice</h1>
            <p class="text-lg md:text-xl mb-6 max-w-2xl">Consultez nos offres d'emploi et rejoignez notre équipe pour contribuer à nos projets innovants et à notre croissance.</p>
            <div class="flex gap-4 justify-center">
            <!-- <button class="bg-white text-primbg-primary font-semibold px-6 py-3 rounded-lg shadow hover:bg-gray-100">Voir les offres</button>
            <button class="border border-white text-white font-semibold px-6 py-3 rounded-lg hover:bg-white hover:text-primbg-primary">Publier une offre</button> -->
            </div>
            </div>
    </section>
    <main class="w-full mx-auto  gap-6 mt-2 p-20">
    <section class="w-full space-y-4">
        <h1 class="text-2xl font-black">Offres d'emploi disponible({{ publications?.data?.length }})</h1>
        <p class="text-gray-600 ">Cliquer sur une offre d'emploi pour commencer</p>
        <!-- <pre>{{ publications }}</pre> -->

        <div 
          v-for="pub in publications?.data" 
          :key="pub?.uuid" 
          class="w-full bg-white mt-4 p-5 shadow-sm hover:shadow-sm hover:border-secondary hover:bg-gray-100 cursor-pointer rounded-lg border border-primary"
        >
        <!-- <pre>{{ pub }}</pre> -->
          <div class="flex justify-between items-start gap-4">
            <div class="flex gap-4 items-start">
              <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center">
                <span class="font-bold text-gray-600">
                  {{ initials(pub?.job?.position_title || 'N/A') }}
                  </span>
              </div>
              <div>
                <h3 class="text-lg font-semibold">{{ pub.job.position_title }}</h3>
                <div class="text-sm text-gray-500">
                  {{ pub.job.center }} • {{ pub.job.city_duty_station }}, {{ pub.job.country_duty_station }}
                </div>
                <!-- <p class="mt-2 text-gray-600 text-sm">
                  Référence : {{ pub.reference }}
                </p> -->
                <div class="mt-3 flex gap-2 items-center text-xs">
                  <span class="px-2 py-1 bg-gray-100 rounded">{{ pub.type }}</span>
                  <span class="px-2 py-1 bg-gray-100 rounded">
                    Publié: {{ formatDate(pub.published_at) }}
                  </span>
                  <span class="px-2 py-1 bg-gray-100 rounded">
                    Expire: {{ formatDate(pub.expires_at) }}
                  </span>
                </div>
                      
              </div>
            </div>

            <div class="flex items-center gap-2">
              <button 
                @click="toggleDetails(pub.uuid)" 
                class="px-3 min-w-[100px] py-2 text-center bg-white cursor-pointer border border-secondary text-secondary rounded-lg"
              >
                {{ opened.includes(pub.uuid) ? 'Fermer' : 'Voir' }}
              </button>
              <a 
                :href="`/apply-job/${pub.uuid}`" 
                class="px-3 py-2 bg-primary min-w-[100px] text-center text-white rounded-lg shadow"
              >
                Postuler
              </a>
            </div>
          </div>

          <!-- Accordéon détails -->
          <transition name="fade">
            <div 
              v-if="opened.includes(pub.uuid)" 
              class="mt-4 border-t pt-3 text-sm text-gray-600 space-y-2"
            >
              <!-- <p><strong>UUID :</strong> {{ pub.uuid }}</p>   -->
              <p><strong>Type :</strong> {{ pub.type }}</p>
              <p><strong>Publié le :</strong> {{ formatDate(pub.published_at) }}</p>
              <p><strong>Expire le :</strong> {{ formatDate(pub.expires_at) }}</p>
              <p><strong>Statut :</strong> 
                <span v-if="pub.is_closed" class="text-red-500">Fermée</span>
                <span v-else class="text-green-600">Active</span>
              </p>
              <div class=" max-w-max">
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
                      <span>Télécharger</span>
                    </a>
                  </div>
                </div>

                <div v-else class="text-gray-500">Aucun document disponible.</div>
              </div>
            </div>
         
          </transition>
        </div>

        <!-- <div v-for="job in publications" :key="job.id" 
        class="w-full bg-white p-5 shadow hover:shadow-lg hover:bg-gray-100  cursor-cell rounded-lg  border border-primary flex justify-between items-start gap-4">
          <div class="flex gap-4 items-start">
            <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center">
              <span class="font-bold text-gray-600">{{ `initials(job.company)` }}</span>
            </div>
            <div>
              <h3 class="text-lg font-semibold">{{ job.title }}</h3>
              <div class="text-sm text-gray-500">{{ job.company }} • {{ job.location }}</div>
              <p class="mt-2 text-gray-600 text-sm">{{ job.excerpt }}</p>
              <div class="mt-3 flex gap-2 items-center text-xs">
                <span class="px-2 py-1 bg-gray-100 rounded">{{ job.type }}</span>
                <span class="px-2 py-1 bg-gray-100 rounded">Publié: {{ job.posted }}</span>
              </div>
            </div>
          </div>

          <div class="flex flex-col gap-2">
            <a href="/" @click="openJob(job)" class="px-3 py-2 bg-white border border-secondary text-secondary rounded-lg">Voir</a>
            <a href="/apply-job" @click="openApply(job)" class="px-3 py-2 bg-primary text-white rounded-lg shadow">Postuler</a>
          </div>
        </div> -->
      </section>

    </main>
  </div>
</template>

<style scoped>
.modal-enter-active, .modal-leave-active {
  transition: opacity .2s;
}
.modal-enter-from, .modal-leave-to {
  opacity: 0;
}
</style>
