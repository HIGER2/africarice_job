<script setup>
import { useApplyForm } from './composables';

const { fields, form, currentStep, handleFile,components, nextStep, prevStep, submitForm } = useApplyForm()

</script>

<template>
  <div class="max-w-xl mx-auto p-6  rounded-lg ">
    <!-- Stepper navigation -->
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
    <!-- Contenu du step actuel -->
    
    <div class="w-full">
        <keep-alive>
        <component :is="components[currentStep]"/>
      </keep-alive>  
    </div>
    <!-- Boutons navigation -->
    <div class="flex justify-between mt-6 sticky bottom-0 bg-white py-4">
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
    </div>
  </div>
</template>
