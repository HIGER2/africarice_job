
<script lang="ts" setup>
import { useApplyForm } from '../composables';
import FormField from './FormField.vue';

const {fieldDocument,fieldReference,form,handleFile}=useApplyForm()

</script>

<template>
<div class=" w-full">
          <div class="w-full">
            <h2 class="text-xl font-bold mb-4">References</h2>
            <div class="w-full" v-for="(field,index) in form.documents.reference" :key="index">
                  <div class="w-full flex gap-2"  v-for="(field,index) in fieldReference" :key="index">
                    <FormField
                    v-for="value in field"
                    :key="value.key"
                    :field="value"
                    v-model="form.diplomas[value.key]"
                  />
                  </div>
              </div>
              <button type="button" class="p-2  cursor-pointer rounded-md mx-auto border border-gray-200">Ajouter une reference</button>
          </div>

            <div class="w-full">
            <h2 class="text-xl font-bold mb-4">CV and Cover letter</h2>
                <div  class="w-full mb-4" v-for="value in fieldDocument">
                  <label class="cursor-pointer">
                          <input type="file" class="hidden"   accept=".pdf,.doc,.docx,.txt"
                          @change="(e) => handleFile(e, '','')" 
                          />
                          <div class="border border-dashed p-5 flex flex-col w-full text-center cursor-pointer">
                              <span  class="text-gray-500 ">Cliquez  fichier ici</span>
                              <span 
                              class="flex items-center justify-center space-x-2 bg-green-100 text-green-800 px-3 py-2 rounded border border-green-300"
                              >
                              <!-- Icône fichier -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M7 7h10M7 11h10M7 15h10M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                              </svg>

                              <!-- Nom du fichier -->
                              <span class="font-medium  max-w-xs truncate">
                                  <!-- {{ form[fields[currentStep].key][value.key].name }} -->
                              </span>
                              </span>
                          </div>
                  </label>
                </div>
            </div>
          
  </div>
</template>

<style>

</style>