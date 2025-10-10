<script setup lang="ts">
import { ref, computed, watch, onMounted } from "vue";
import FormField from "./FormField.vue";
import { useApplyForm } from "../composables";
import Spinnercomponent from "./Spinnercomponent.vue";

interface OffreData {
  uuid?: string;
  reference?: string;
  type?: string;
  position_title?: string;
  recrutement_id?: number;
  is_published?: string;
  is_closed?: string;
  published_at?: string;
  expires_at?: string;
  files?: Array<{ path: string }>;
  candidates_count?: number;
}

const props = defineProps<{
  row?: OffreData | null
}>();

const emit = defineEmits<{
  (e: 'close'): void
}>();

const { fieldAddOffre, newOffre, fieldAddDocument, submitOffre } = useApplyForm();
const loading = ref(false);

// Computed pour la visibilité de la modal
const isOpen = computed(() => !!props.row);

// Computed pour savoir si on est en mode édition
const isEditMode = computed(() => props.row && props.row.uuid);

// Pré-remplir le formulaire avec les données existantes
const populateForm = () => {
  if (props.row && isEditMode.value) {
    // Mapper les données de row vers newOffre.offre
    Object.keys(props.row).forEach(key => {
      if (key in newOffre.offre && props.row![key as keyof OffreData] !== undefined) {
         let value = props.row![key as keyof OffreData];
        // newOffre.offre[key] = props.row![key as keyof OffreData];
        if (typeof value === 'string' && /^\d{2}\/\d{2}\/\d{4}$/.test(value)) {
          // C'est une date au format dd/mm/yyyy
          const [day, month, year] = value.split('/');
          value = `${year}-${month}-${day}`; // Conversion en yyyy-mm-dd
        }

        if (key === 'is_published') {
        // Conversion du statut en booléen
        value = value === 'Publiée';
      }

          newOffre.offre[key] = value as any;
      }
    });

    // Gérer les fichiers existants si nécessaire
    if (props.row.files && props.row.files.length > 0) {
      // Vous pouvez adapter cette partie selon votre logique
      // Par exemple, convertir les chemins en objets File ou les afficher différemment
      newOffre.document = props.row.files.map(file => ({
        name: file.path.split('/').pop() || 'Document',
        path: file.path,
        isExisting: true
      }));
    }
  }
};

// Réinitialiser le formulaire
const resetForm = () => {
  // Réinitialiser tous les champs
  Object.keys(newOffre.offre).forEach(key => {
    newOffre.offre[key] = '';
  });
  newOffre.document = [];
};

// Fermer la modal
const closeModal = () => {
    resetForm();
    emit('close');
  // if (!loading.value) {
  
  // }
};

// Supprimer un fichier
const removeFile = (index: number) => {
  newOffre.document.splice(index, 1);
};

// Soumettre le formulaire
const handleSubmit = async () => {
  if (loading.value) return;
  
  loading.value = true;
  try {
    await submitOffre(newOffre);
    // resetForm();
    closeModal();
  } catch (error) {
    console.error('Erreur lors de la soumission:', error);
  } finally {
    loading.value = false;
  }
};

// Gérer l'ajout de document
const handleDocumentChange = (file: File) => {
  if (file) {
    newOffre.document.push(file);
  }
};

// Empêcher le scroll du body quand la modal est ouverte
watch(isOpen, (value) => {
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
          <form 
            v-if="isOpen"
            @submit.prevent="handleSubmit"
            class="w-full max-w-2xl"
          >
         
            <div 
              class="relative flex h-[90vh]  w-lg flex-col overflow-hidden rounded-2xl bg-white p-6 shadow-xl"
            >
              <!-- En-tête -->
              <div class="mb-4 flex items-center justify-between">
                <div>
                  <h2 class="text-xl font-semibold text-gray-800">
                    {{ isEditMode ? 'Edit Publication' : 'Ajouter une publication' }}
                  </h2>
                  <p v-if="isEditMode && row?.reference" class="text-sm text-gray-500 mt-1">
                    Référence: {{ row.reference }}
                  </p>
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

              <!-- Badge de statut (mode édition) -->
              <div v-if="isEditMode" class="mb-4 flex gap-2 flex-wrap">
                <span 
                  v-if="row?.is_published"
                  class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                  :class="row.is_published === 'Publiée' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                >
                  {{ row.is_published }}
                </span>
                <span 
                  v-if="row?.is_closed"
                  class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                  :class="row.is_closed === 'Ouverte' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'"
                >
                  {{ row.is_closed }}
                </span>
                <span 
                  v-if="row?.candidates_count !== undefined"
                  class="inline-flex items-center rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800"
                >
                  {{ row.candidates_count }} candidat{{ row.candidates_count > 1 ? 's' : '' }}
                </span>
              </div>

              <!-- Contenu scrollable -->
              <div class="flex-1 space-y-4 overflow-y-auto px-2">
                <!-- Groupes de champs -->
                 <!-- <pre>{{ newOffre.offre }}</pre> -->
                <template v-for="(groupe, index) in fieldAddOffre" :key="`groupe-${index}`">
                  <div class="flex flex-col gap-3">
                    <FormField
                      v-for="(field, fieldIndex) in groupe"
                      :key="`field-${index}-${fieldIndex}`"
                      :label="field.label"
                      :field="field"
                      v-model="newOffre.offre[field.key]"
                    />
                  </div>
                </template>

                <!-- Zone d'upload de documents -->
                <div 
                  v-for="(value, index) in fieldAddDocument" 
                  :key="`doc-${index}`"
                  class="w-full"
                >
                  <label class="cursor-pointer">
                    <FormField
                      :field="value"
                      @change="handleDocumentChange"
                    />
                    <div class="flex w-full flex-col border border-dashed border-gray-300 p-4 text-center transition-colors hover:border-primary hover:bg-gray-50">
                      <span class="mb-2 text-sm text-gray-600">
                        Cliquez ici pour télécharger un fichier
                      </span>
                      <span class="mx-auto flex items-center justify-center rounded border border-green-300 bg-green-100 p-2 text-green-800">
                        <svg 
                          xmlns="http://www.w3.org/2000/svg" 
                          width="24" 
                          height="24" 
                          viewBox="0 0 24 24"
                          aria-hidden="true"
                        >
                          <path 
                            fill="currentColor" 
                            d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5c0-2.64-2.05-4.78-4.65-4.96M19 18H6c-2.21 0-4-1.79-4-4c0-2.05 1.53-3.76 3.56-3.97l1.07-.11l.5-.95A5.47 5.47 0 0 1 12 6c2.62 0 4.88 1.86 5.39 4.43l.3 1.5l1.53.11A2.98 2.98 0 0 1 22 15c0 1.65-1.35 3-3 3M8 13h2.55v3h2.9v-3H16l-4-4z"
                          />
                        </svg>
                      </span>
                    </div>
                  </label>
                </div>

                <!-- Liste des fichiers ajoutés -->
                <TransitionGroup name="list" tag="div" class="space-y-2">
                  <div 
                    v-for="(file, index) in newOffre.document"
                    :key="`file-${file.name || index}`"
                    class="group flex cursor-pointer items-center justify-between rounded border p-3 transition-colors"
                    :class="file.isExisting ? 'border-blue-200 bg-blue-50 hover:bg-blue-100' : 'border-gray-200 bg-gray-50 hover:bg-red-50 hover:border-red-300'"
                    @click="removeFile(index)"
                  >
                    <div class="flex items-center gap-2 flex-1 min-w-0">
                      <i 
                        class="uil text-lg"
                        :class="file.isExisting ? 'uil-file-check-alt text-blue-600' : 'uil-file text-gray-600'"
                      ></i>
                      <span class="truncate text-sm" :class="file.isExisting ? 'text-blue-700' : 'text-gray-700'">
                        {{ file.name }}
                      </span>
                      <span v-if="file.isExisting" class="text-xs text-blue-600 whitespace-nowrap">
                        (existant)
                      </span>
                    </div>
                    <!-- <i class="uil uix  l-trash-alt text-red-500 opacity-0 transition-opacity group-hover:opacity-100"></i> -->
                  </div>
                </TransitionGroup>
              </div>

              <!-- Bouton de soumission -->
              <button 
                type="submit" 
                :disabled="loading"
                class="mt-4 flex w-full items-center justify-center rounded-md bg-primary p-3 text-white transition-opacity hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-70"
              >
                <Spinnercomponent v-if="loading" />
                <span v-else>
                  {{ isEditMode ? 'Update' : 'Enregistrer' }}
                </span>
              </button>
            </div>
          </form>
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