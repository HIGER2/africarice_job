<script setup>
import NavBar from './components/NavBar.vue';
import { useApplyForm } from './composables';
import { usePage } from '@inertiajs/vue3'

// const page = usePage()
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

const { fields, form, payLoad,documentPreview, currentStep, handleFile, components, nextStep, prevStep, submitForm } = useApplyForm()


payLoad(props?.user?.data?.application)
// form.diplomas.push(...props.user?.application?.diplomas)
// // form.cgiar_information.assign(...props.user?.application?.cgiarInformation)
// form.experiences.push(...props.user?.application?.experiences)
// form.identification.push(...props.user?.application?.identification)
// form.origin.assign(...props.user?.application?.origin)
// form.references.push(...props.user?.application?.references)
// form.documents.push(...props.user?.application?.documents)

</script>

<template>
  <div class="w-full bg-gray-50">
    <NavBar :user="user.data" />
      <div class="max-w-xl mx-auto py-10">
       <pre>
        <!-- {{ documentPreview }} -->
        <!-- {{ props.user.data }} -->
         <!-- {{ user.application.diplomas }}
         {{ user.application.cgiar_information }}
         {{ user.application.experiences }}
         {{ user.application.identification }}
         {{ user.application.origin }}
         {{ user.application.references }} -->
         <!-- {{ user.data.application.documents }} -->
       </pre>
    <!-- Récapitulatif publication -->
   <!-- <pre>
      {{ publication.job }}
   </pre> -->
     <a href="/" class=" text-3xl max-w-max  text-center mb-2 cursor-pointer block rounded-lg">
      <i class="uil uil-arrow-left"></i>
     </a>
    <div class="bg-white  border border-gray-200 rounded-lg p-5 mb-6">
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
        <!-- <p><strong>Contrat :</strong> {{ publication.job.contract_time }}</p>
        <p><strong>Grade :</strong> {{ publication.job.grade }}</p> -->
      </div>
    </div>

    <!-- Stepper candidature -->
    <div class="bg-white  border border-gray-200 rounded-lg p-5">
      <h3 class="text-lg font-semibold mb-4">Votre candidature</h3>
      <!-- Ici ton stepper -->
      <!-- <p class="text-gray-500">Formulaire en plusieurs étapes…</p> -->
        <div class="w-full">
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
        <keep-alive>
          <component :is="components[currentStep]"  :documents="documentPreview" :form="form" />
        </keep-alive>  
        <div class="flex justify-between border-t border-t-gray-200 bg-white mt-6 sticky bottom-0  p-4">
        <button 
          @click="prevStep" 
          class="px-4 py-2 cursor-pointer bg-gray-400 text-white rounded-lg" 
          :disabled="currentStep === 0"
        >
          Précédent
        </button>

        <button 
          v-if="currentStep < components.length - 1" 
          @click="nextStep" 
          class="px-4 py-2 cursor-pointer bg-primary text-white rounded-lg"
        >
          Suivant
        </button>

        <button 
          v-else 
          @click="()=>submitForm(uuid)" 
          class="px-4 py-2 cursor-pointer bg-green-600 text-white rounded"
        >
          Envoyer
        </button>
      </div>
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
