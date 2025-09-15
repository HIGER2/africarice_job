
<script setup>
import { ref, computed,defineProps } from 'vue'

const sampleJobs = ref([
  {
    id: 1,
    title: 'Frontend Developer',
    company: 'AfricariCe Labs',
    location: "Abidjan, Côte d'Ivoire",
    type: 'Full-time',
    posted: '2025-09-01',
    excerpt: 'We build beautiful UIs with Vue and Tailwind — join our product team to ship polished web apps.'
  },
  {
    id: 2,
    title: 'Backend Engineer',
    company: 'AgriTech Co.',
    location: 'Remote',
    type: 'Part-time',
    posted: '2025-08-20',
    excerpt: 'Work on APIs, data pipelines and integrations.'
  },
  {
    id: 3,
    title: 'Product Manager',
    company: 'AfricariCe Labs',
    location: "Yamoussoukro, Côte d'Ivoire",
    type: 'Full-time',
    posted: '2025-09-08',
    excerpt: 'Lead product discovery and collaborate with engineering to launch features users love.'
  },
    {
    id: 3,
    title: 'Product Manager',
    company: 'AfricariCe Labs',
    location: "Yamoussoukro, Côte d'Ivoire",
    type: 'Full-time',
    posted: '2025-09-08',
    excerpt: 'Lead product discovery and collaborate with engineering to launch features users love.'
  },
    {
    id: 3,
    title: 'Product Manager',
    company: 'AfricariCe Labs',
    location: "Yamoussoukro, Côte d'Ivoire",
    type: 'Full-time',
    posted: '2025-09-08',
    excerpt: 'Lead product discovery and collaborate with engineering to launch features users love.'
  },
    {
    id: 3,
    title: 'Product Manager',
    company: 'AfricariCe Labs',
    location: "Yamoussoukro, Côte d'Ivoire",
    type: 'Full-time',
    posted: '2025-09-08',
    excerpt: 'Lead product discovery and collaborate with engineering to launch features users love.'
  }
])

const q = ref('')
const props = defineProps({
  user: Object
})
const filterType = ref('Tous')
const types = ['Tous', 'Full-time', 'Part-time', 'Contract', 'Internship']
const activeJob = ref(null)
const showApply = ref(false)

const form = ref({ name: '', email: '', phone: '', message: '', file: null, position: '' })

function initials(company) {
  return company.split(' ').map(w => w[0]).join('').slice(0,2).toUpperCase()
}

function clearFilters() {
  q.value = ''
  filterType.value = 'Tous'
}

const filtered = computed(() => {
  return sampleJobs.value.filter(job => {
    const text = [job.title, job.company, job.location, job.excerpt].join(' ').toLowerCase()
    const matchesQ = text.includes(q.value.toLowerCase())
    const matchesType = filterType.value === 'Tous' || job.type === filterType.value
    return matchesQ && matchesType
  })
})

function openJob(job) {
  activeJob.value = job
  showApply.value = false
}

function openApply(job) {
  activeJob.value = job
  form.value.position = job.title
  showApply.value = true
}

function closeApply() {
  showApply.value = false
  form.value = { name: '', email: '', phone: '', message: '', file: null, position: '' }
}

function onFileChange(e) {
  form.value.file = e.target.files[0]
}

function submitApplication() {
  // ici vous pouvez appeler votre API pour envoyer la candidature
  // Exemple: use fetch/axios pour poster form-data (incl. fichier)
  console.log('Envoi candidature', form.value)
  alert('Candidature envoyée — ceci est un mock. Connectez votre API.')
  closeApply()
}
</script>



<template>
  <div class="min-h-screen">
    <nav class="bg-white shadow sticky top-0 z-10 px-4 p-2 flex justify-between items-center">
        <!-- Bouton Connexion à gauche -->
        <!-- Logo à droite -->
        <div class="flex items-center gap-2">
             <img src="https://static.wixstatic.com/media/0839e4_7910df264aee46ba85347ab33684d739~mv2_d_4782_1488_s_2.png/v1/fill/w_520,h_160,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/0839e4_7910df264aee46ba85347ab33684d739~mv2_d_4782_1488_s_2.png" alt="Logo" 
             class="w-40 object-contain" />
        </div>
        <template v-if="user">
      <!-- Utilisateur connecté -->
      <div class="px-2 flex cursor-pointer items-center gap-1 py-1 border  rounded-full">
        <span class="block w-8 h-8 bg-primary text-white leading-8 text-center rounded-full"><i class="uil uil-user"></i></span>
        <span class="uppercase font-black text-sm cursor">{{ user.name }}</span>
      </div>
    </template>
    <template v-else>
      <!-- Pas connecté -->
      <a href="/login" class="px-4 py-2 bg-primary text-white rounded-lg  transition">
        Connexion
      </a>
    </template>
    </nav>
    <section class="relative bg-black text-white">
        <!-- Image en arrière-plan -->
        <div class="absolute inset-0 overflow-hidden">
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
        <h1 class="text-2xl font-black">Offres d'emploi disponible(12)</h1>
        <div v-for="job in filtered" :key="job.id" 
        class="w-full bg-white p-5 shadow hover:shadow-lg hover:bg-gray-100  cursor-cell rounded-lg  border border-primary flex justify-between items-start gap-4">
          <div class="flex gap-4 items-start">
            <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center">
              <span class="font-bold text-gray-600">{{ initials(job.company) }}</span>
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
        </div>
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
