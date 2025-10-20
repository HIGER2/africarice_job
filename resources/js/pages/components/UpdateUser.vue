<script setup lang="ts">
import { ref, computed, watch, onMounted, reactive } from "vue";
import FormField from "./FormField.vue";
import { useApplyForm } from "../composables";
import Spinnercomponent from "./Spinnercomponent.vue";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
import countryCode from '../../data/country.json'

const props = defineProps<{
  row?: boolean | null
}>();

const emit = defineEmits<{
  (e: 'close'): void
}>();


const formData = reactive({
    name: '',
    last_name: '',
    email: '',
    phone:'',
    country_code:"",

})

const { fieldAddOffre, newOffre, fieldAddDocument, submitOffre } = useApplyForm();
const loading = ref(false);

// Computed pour la visibilité de la modal
const isOpen = computed(() => !!props.row);

// Computed pour savoir si on est en mode édition

// Fermer la modal
const closeModal = () => {
    emit('close');
};


async function handleRegister() {
    try {
        loading.value = true
        let response = await axios.post('/register',formData)
        Inertia.reload()
        emit('close');
    } catch (err) {
        let message = 'Erreur lors de la vérification de l\'email.'
        if (err?.response?.data?.errors?.length > 0) {
            message  =  Object.values(err?.response?.data?.errors).flat().flat().join('\n')
        }else if (err?.response?.data?.message) {
            message = err?.response?.data?.message
        }
        alert(message)
    }finally{
        loading.value = false
    }
}

const populateForm = ()=>{
    Object.keys(props.row).forEach(key => {
          let value = props.row![key];
          formData[key] = value as any;
    });
}
// Empêcher le scroll du body quand la modal est ouverte
watch(isOpen, (value) => {
console.log(props.row);

  if (value) {
    document.body.style.overflow = 'hidden';
    populateForm();
  } else {
    document.body.style.overflow = '';
  }
});


</script>

<template>
  <Teleport to="body">
    <Transition name="fade">
      <div 
        v-if="isOpen" 
        class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black/50 p-4"
        @click.self="closeModal"
      >
        <Transition name="scale">
          <div 
            v-if="isOpen"
            class="w-full max-w-2xl"
          >
         
            <div 
              class="relative flex h-auto m-auto  w-2/3 flex-col overflow-hidden rounded-lg bg-white p-6 shadow-xl"
            >
              <!-- En-tête -->
              <div class="mb-12 flex items-center justify-between">
                <div>
                  <h2 class="text-xl font-semibold text-gray-800">
                    Edit Profile
                  </h2>
                </div>
                <button 
                  type="button"
                  :disabled="loading"
                  @click="closeModal" 
                  class="text-gray-500 transition-colors hover:text-gray-700 disabled:opacity-50"
                  aria-label="Fermer"
                >
                  <i class="uil uil-times text-2xl"></i>
                </button>
              </div>
              <!-- Contenu scrollable -->
              <div class="flex-1 space-y-4  px-2">
                <!-- Groupes de champs -->
                 <!-- <pre>{{ newOffre.offre }}</pre> -->
                <!-- Liste des fichiers ajoutés -->
                <TransitionGroup name="list" tag="div" class="space-y-2">
                   <form @submit.prevent="handleRegister" class="flex w-full flex-col gap-4">
                        <!-- First Name / Last Name -->
                        <div class="flex flex-col sm:flex-row gap-3 w-full">
                            <div class="flex flex-col flex-1">
                                <label class="text-sm font-medium text-gray-700 mb-1">Family Name / nom</label>
                                <input
                                    v-model="formData.name"
                                    type="text"
                                    placeholder="Enter your first name"
                                    required
                                    class="p-3 border w-full rounded-lg focus:ring-2 focus:ring-primary focus:outline-none"
                                />
                            </div>
                            <div class="flex flex-col flex-1">
                                <label class="text-sm font-medium text-gray-700 mb-1">First Name / Prénoms</label>
                                <input
                                    v-model="formData.last_name"
                                    type="text"
                                    placeholder="Enter your last name"
                                    required
                                    class="p-3 border w-full rounded-lg focus:ring-2 focus:ring-primary focus:outline-none"
                                />
                            </div>
                        </div>

                        <!-- Phone / Email -->
                          <div class="  gap-3 w-full">
                              <div class="flex mb-3 flex-col flex-1">
                              <label class="text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                              
                              <div class="flex">
                                <!-- Sélecteur des indicatifs pays -->
                                <select
                                  required
                                  v-model="formData.country_code"
                                  class="p-3 max-w-max border rounded-l-lg focus:ring-2 focus:ring-primary focus:outline-none"
                                >
                                  <option v-for="country in countryCode" :key="country.code" :value="country.code">
                                    {{ country.abbreviation }} ({{ country.code }})
                                  </option>
                                </select>

                                <!-- Champ téléphone -->
                                <input
                                  v-model="formData.phone"
                                  type="tel"
                                  placeholder="e.g. 07 00 00 00 00"
                                  required
                                  class="p-3 w-full border-t border-b border-r rounded-r-lg focus:ring-2 focus:ring-primary focus:outline-none"
                                />
                              </div>
                            </div>

                              <div class="flex flex-col flex-1">
                                <label class="text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input
                                  v-model="formData.email"
                                  type="email"
                                  placeholder="example@mail.com"
                                  required
                                  class="p-3 border w-full rounded-lg focus:ring-2 focus:ring-primary focus:outline-none"
                                />
                              </div>
                            </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="flex items-center justify-center cursor-pointer bg-primary text-white py-3 rounded-lg hover:bg-primary-700 transition-all"
                        >
                            <Spinnercomponent v-if="loading" />
                            <span v-else>Save</span>
                        </button>
                        </form>
                </TransitionGroup>
              </div>

            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
/* Animations fade pour l'overlay */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Animations scale pour la modal */
.scale-enter-active,
.scale-leave-active {
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), 
              opacity 0.3s ease;
}

.scale-enter-from,
.scale-leave-to {
  transform: scale(0.95);
  opacity: 0;
}

/* Animations pour la liste de fichiers */
.list-enter-active,
.list-leave-active {
  transition: all 0.3s ease;
}

.list-enter-from {
  opacity: 0;
  transform: translateX(-20px);
}

.list-leave-to {
  opacity: 0;
  transform: translateX(20px);
}

.list-move {
  transition: transform 0.3s ease;
}
</style>