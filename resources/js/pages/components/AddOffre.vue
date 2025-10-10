

<script setup lang="ts">
import { ref } from "vue";
import FormField from "./FormField.vue";
import { useApplyForm } from "../composables";
import Spinnercomponent from "./Spinnercomponent.vue";

const {fieldAddOffre,newOffre,fieldAddDocument,submitOffre}=useApplyForm()
const open = ref(false);
const loading =ref(false)


// Supprimer une référence
const removeFile = (index: number) => {
  newOffre.document.splice(index, 1)
}

const handleSubmit = async() => {
  loading.value = true
  await submitOffre(newOffre)
  open.value = false
  loading.value = false
};

</script>

<template>
  <div class="p-6">
    <!-- Bouton -->
     <button 
      @click="open = true" 
     class="border-[0.1rem] border-gray-200 p-2 px-3 rounded-lg text-primary cursor-pointer">
        <i class="uil uil-plus"></i>
        <span>Add a Job Offer</span>
    </button> 

    <!-- Overlay -->
    <transition name="fade">
      <div 
        v-if="open" 
        class="fixed inset-0 overflow-auto bg-black/50 bg-opacity-50 flex items-center justify-center z-50"
        @click.self="open = false"
      >
        <!-- Modal -->
        <form action="" @submit.prevent="handleSubmit">
          <transition name="scale">
          <div 
            v-if="open"
            class="bg-white flex flex-col overflow-hidden  h-[560px]  rounded-2xl shadow-lg p-6 w-lg relative"
          >
            <!-- Bouton fermer -->
              <div class="flex items-center justify-between mb-2">
                  <h2 class="text-xl font-semibold">
                   Add a Job Offer
                </h2>
                <button 
                type="button"
                  @click="open = false" 
                  class=" top-3 text-xl right-3 text-gray-500 hover:text-gray-700"
                >
                <i class="uil uil-times"></i>
                </button>
              </div>

            <!-- Contenu -->
              <!-- {{ newOffre }} -->
            <div class="mt-7 flex-1  overflow-y-auto p-2">
                <template 
                v-for="(groupe,index) in fieldAddOffre"
                >
                <!-- {{ groupe }} -->
                <div class="flex items-end flex-col gap-2">
                    <FormField
                    v-for="filed in groupe"
                    :label="filed.label"
                    :field="filed"
                    v-model="newOffre.offre[filed.key]"
                />
                </div>
               
                </template>
                  <div  class="w-full mb-4" v-for="value in fieldAddDocument">
                    <label class="cursor-pointer">
                          <FormField
                          :field="value"
                          @change="(e) => newOffre.document.push(e)"
                        />
                            <div class="border border-dashed p-3 flex flex-col w-full text-center cursor-pointer">
                                <span  class="text-gray-500 ">
                                      Cliquez ici pour télécharger un ficher
                                </span>
                                <span 
                                class="flex items-center justify-center space-x-2 bg-green-100 text-green-800 p-2 rounded border border-green-300"
                                >
                                <!-- Icône fichier -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><!-- Icon from Google Material Icons by Material Design Authors - https://github.com/material-icons/material-icons/blob/master/LICENSE --><path fill="currentColor" d="m19.21 12.04l-1.53-.11l-.3-1.5A5.484 5.484 0 0 0 12 6C9.94 6 8.08 7.14 7.12 8.96l-.5.95l-1.07.11A3.99 3.99 0 0 0 2 14c0 2.21 1.79 4 4 4h13c1.65 0 3-1.35 3-3c0-1.55-1.22-2.86-2.79-2.96m-5.76.96v3h-2.91v-3H8l4-4l4 4z" opacity=".3"/><path fill="currentColor" d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5c0-2.64-2.05-4.78-4.65-4.96M19 18H6c-2.21 0-4-1.79-4-4c0-2.05 1.53-3.76 3.56-3.97l1.07-.11l.5-.95A5.47 5.47 0 0 1 12 6c2.62 0 4.88 1.86 5.39 4.43l.3 1.5l1.53.11A2.98 2.98 0 0 1 22 15c0 1.65-1.35 3-3 3M8 13h2.55v3h2.9v-3H16l-4-4z"/></svg>
                                </span>
                            </div>
                    </label>
                  </div>
                  <div class="flex cursor-pointer bg-gray-100 border p-1 mb-1 border-gray-200 " 
                  v-for="(value,index) in newOffre.document"
                  @click="removeFile(index)"
                  >
                    <span class="text-sm max-w-xs truncate">{{ value.name }}</span>

                  </div>
            </div>
              <button type="submit" class="w-full flex items-center justify-center bg-primary p-2 rounded-md text-white cursor-pointer">
                <Spinnercomponent v-if="loading"/>
                <span v-else>Save</span>
              </button>
              
          </div>
        </transition>
        </form>
      </div>
    </transition>
  </div>
</template>

<style>
/* Animation fade (overlay) */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Animation scale (modal) */
.scale-enter-active, .scale-leave-active {
  transition: transform 0.3s ease, opacity 0.3s ease;
}
.scale-enter-from, .scale-leave-to {
  transform: scale(0.9);
  opacity: 0;
}
</style>
