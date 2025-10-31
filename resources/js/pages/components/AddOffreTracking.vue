

<script setup lang="ts">
import { ref } from "vue";
import FormField from "./FormField.vue";
import { useApplyForm } from "../composables";
import Spinnercomponent from "./Spinnercomponent.vue";
import { usePaylod } from "../composables/usePayload";

  
const props =defineProps({
  data: Object,
})
  const {
    fieldAddOffreTracker,
    newOffreTrakinck,
    addTrackingOffer
  }=
  useApplyForm()

const open = ref(false);
const loading =ref(false)


const handleSubmit = async() => {
  loading.value = true
  newOffreTrakinck.uuid = props.data?.uuid
  await addTrackingOffer(newOffreTrakinck)
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
        <span>Add Tracking</span>
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
            class="bg-white flex flex-col overflow-hidden  max-h-[560px]  rounded-lg shadow-lg p-6 w-lg relative"
          >
            <!-- Bouton fermer -->
              <div class="flex items-center justify-between mb-2">
                  <h2 class="text-xl font-semibold">
                  Add a Tracking
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
                v-for="(groupe,index) in fieldAddOffreTracker"
                >
                <!-- {{ groupe }} -->
                <div class="flex items-end flex-col gap-2">
                    <FormField
                    required
                    v-for="filed in groupe"
                    :label="filed.label"
                    :field="filed"
                    v-model="newOffreTrakinck[filed.key]"
                />
                </div>
               
                </template>
              
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
