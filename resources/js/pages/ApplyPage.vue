<script setup>
import { computed, ref } from 'vue';
import NavBar from './components/NavBar.vue';
import OverlayLoader from './components/OverlayLoader.vue';
import { useApplyForm } from './composables';
import { usePage } from '@inertiajs/inertia-vue3';
// import { usePage } from '@inertiajs/vue3'

const page = usePage()

const notApply = computed(() => {
  const url = page.url.value || ''
  const query = new URLSearchParams(url.split('?')[1] || '')
  return query.get('not_apply') || false
})

// const user = page.props?.auth?.user

const props = defineProps({
  uuid: String,
  publication: Object,
  user: Object,
})

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const { fields, form, payLoad,documentPreview,setStep, currentStep, handleFile, components, nextStep, prevStep, submitForm } = useApplyForm()

const isLoading =ref(false)

payLoad(props?.user?.data?.application)


const handleSubmit=async(uuid,notApply=false)=>{
  isLoading.value =true
  await submitForm(uuid)
  isLoading.value = false
}
// form.diplomas.push(...props.user?.application?.diplomas)
// // form.cgiar_information.assign(...props.user?.application?.cgiarInformation)
// form.experiences.push(...props.user?.application?.experiences)
// form.identification.push(...props.user?.application?.identification)
// form.origin.assign(...props.user?.application?.origin)
// form.references.push(...props.user?.application?.references)
// form.documents.push(...props.user?.application?.documents)

</script>

<template>
  <div class="w-full ">
    <NavBar :user="user.data" />
            <OverlayLoader v-model="isLoading" text="Traitement..."/>

      <div class="max-w-xl mx-auto mt-4">
        <a href="/" 
          class="px-4 py-2 text-sm font-medium bg-gray-100 text-primary rounded-xl hover:shadow-lg hover:bg-gray-200 inline-flex items-center gap-2 transition-all duration-200 mb-3"
        >
            <i class="uil uil-arrow-left"></i> Back to Home
        </a>
      <!-- <a href="/" class="px-4 text-[13px]  cursor-pointer font-medium py-2 bg-gray-100 text-primary rounded-xl hover:shadow hover:bg-gray-200 inline-flex items-center gap-2 mb-3">
        <i class="uil uil-arrow-left"></i> Retour a laccuile
      </a> -->
      <!-- <div class="bg-white  border border-gray-200 rounded-lg p-5 mb-6">
        <h2 class="text-xl font-semibold">{{ publication.job.position_title }}</h2>
        <p class="text-gray-600">
          {{ publication.job.center }} • {{ publication.job.city_duty_station }},
          {{ publication.job.country_duty_station }}
        </p>

        <div class="mt-3 grid grid-cols-2 gap-4 text-sm text-gray-700">
          <p><strong>Référence :</strong> {{ publication.reference }}</p>
          <p><strong>Type :</strong> {{ publication.type }}</p>
          <p><strong>Publié le :</strong> {{ formatDate(publication.published_at) }}</p>
          <p><strong>Expire le :</strong> {{ formatDate(publication.expires_at) }}</p>
        </div>
      </div> -->
        <div v-if="publication" class="bg-white border border-gray-200 rounded-lg p-5 mb-6">
          <h2 class="text-xl font-semibold mb-3">{{ publication.job.position_title }}</h2>
          <p class="text-gray-600">
            {{ publication.job.center }} • {{ publication.job.city_duty_station }},
            {{ publication.job.country_duty_station }}
          </p>

          <div class="mt-3 grid grid-cols-2 gap-4 text-sm text-gray-700">
            <p><strong>Reference:</strong> {{ publication.reference }}</p>
            <p><strong>Type:</strong> {{ publication.type }}</p>
            <p><strong>Published on:</strong> {{ formatDate(publication.published_at) }}</p>
            <p><strong>Expires on:</strong> {{ formatDate(publication.expires_at) }}</p>
          </div>
        </div>
        <div v-else>
          <!-- Version bilingue (français + anglais) -->
          <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">
            Complétez ou mettez à jour vos informations<br />
            <span class="text-gray-500 text-base">Complete or update your information</span>
          </h3>

          <!-- <h3 class="text-xl font-semibold text-gray-800 mb-4">
            Complétez ou mettez à jour vos informations
          </h3> -->

        </div>
      <div class="bg-white ">
          <h3  v-if="publication" class="text-lg font-semibold text-gray-800 mb-4">
            Votre candidature / Your Application 
          </h3>

          <!-- Here your stepper -->
          <!-- <p class="text-gray-500">Multi-step form…</p> -->
          
          <div class="w-full border border-gray-200 rounded-lg p-5">
            <div class="flex items-center w-full mb-6">
              <template v-for="(section, index) in components" :key="index">
                <div 
                  @click="setStep(index)"
                  class="w-10 h-10 cursor-pointer flex items-center justify-center rounded-full font-semibold"
                  :class="index <= currentStep ? 'bg-secondary text-white' : 'bg-gray-300 text-gray-700'"
                >
                  {{ index + 1 }}
                </div>

                <div 
                  v-if="index < components.length - 1" 
                  class="flex-1 h-1"
                  :class="index < currentStep ? 'bg-secondary' : 'bg-gray-300'"
                >
              </div>
              </template>
            </div>

            <keep-alive>
              <component 
              :is="components[currentStep]" 
              :documents="documentPreview" 
              :form="form"
              :notApply="notApply"
              />
            </keep-alive>  
          </div>
          
            <div class="w-full flex justify-between  border-t-gray-200 bg-white mt-6  bottom-0 p-4">
              <button 
                @click="prevStep" 
                class="px-4 py-2 cursor-pointer bg-gray-400 text-white rounded-lg" 
                :disabled="currentStep === 0"
              >
                Previous
              </button>

              <button 
                v-if="currentStep < components.length - 1" 
                @click="()=>nextStep(currentStep,form)" 
                class="px-4 py-2 cursor-pointer bg-primary text-white rounded-lg"
              >
                Next
              </button>

              <button 
                v-else 
                @click="handleSubmit(uuid,notApply)" 
                class="px-4 py-2 cursor-pointer bg-green-600 text-white rounded-lg"
              >
                Submit
              </button>
            </div>
        </div>

  
  </div>

    <!-- <pre>{{ form }}</pre> -->
    <!-- <div class="max-w-xl mx-auto p-6  rounded-lg ">
      <h1 class="text-2xl font-bold mb-2">Lorem ipsum dolor sit, amet consectetur adipisicing elit.</h1>
      <div class="flex items-center w-full mb-6">
          <template v-for="(section, index) in components" :key="index">
                  <div 
                  class="w-10 h-10 flex items-center justify-center rounded-full font-semibold"
                  :class="index <= currentStep ? 'bg-secondary text-white' : 'bg-gray-300 text-gray-700'"
                  >
                  {{ index + 1 }}
                  </div>

                  <div 
                  v-if="index < components.length - 1" 
                  class="flex-1 h-1"
                  :class="index < currentStep ? 'bg-secondary' : 'bg-gray-300'"
                  ></div>
          </template>
      </div>
      
      <div class="w-full">
          <keep-alive>
          <component :is="components[currentStep]"     :form="form" />
        </keep-alive>  
      </div>
    
    </div> -->
      <!-- Boutons navigation -->
      <!-- <div class="flex justify-between border-t border-t-gray-200 bg-white mt-6 sticky bottom-0  p-4">
        <button 
          @click="prevStep" 
          class="px-4 py-2 bg-gray-400 text-white rounded" 
          :disabled="currentStep === 0"
        >
          Précédent
        </button>

        <button 
          v-if="currentStep < components.length - 1" 
          @click="nextStep" 
          class="px-4 py-2 bg-blue-600 text-white rounded"
        >
          Suivant
        </button>

        <button 
          v-else 
          @click="submitForm" 
          class="px-4 py-2 bg-green-600 text-white rounded"
        >
          Envoyer
        </button>
      </div> -->
  </div>
</template>

<style>
.is-invalid {
  border: 1px solid red !important;
  /* box-shadow: 0 0 5px rgba(255,0,0,0.3); */
}

</style>