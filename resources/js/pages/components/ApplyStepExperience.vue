
<script lang="ts" setup>
import { useApplyForm } from '../composables';
import FormField from './FormField.vue';


const props =defineProps({
  form: Object,
})
const {fieldExperience,initExperience}=useApplyForm()


const addReference = () => {
 if (props.form.experiences.length >= 3) {
  alert('Vous avez atteint la limite de expérience.')
  return false
}
  props.form.experiences.push({ ...initExperience })
}

// Supprimer une référence
const removeReference = (index: number) => {
  props.form.experiences.splice(index, 1)
}
</script>

<template>
  <div class="w-full">
  <h2 class="text-xl font-bold mb-4">Your Most Recent Experience</h2>

  <div class="w-full mb-2 border-b border-b-gray-300 py-3" 
       v-for="(refItem, indexRef) in form.experiences" :key="indexRef">
    <div class="w-full flex gap-2" v-for="(fieldGroup, index) in fieldExperience" :key="index">
      <template v-for="value in fieldGroup" :key="value.key">
        <FormField
          :field="value"
          :id="`${indexRef}${value.key}`"
          v-model="form.experiences[indexRef][value.key]"
          :disabled="value.key == 'end_date' && form.experiences[indexRef].current"
        />
      </template>
    </div>

    <button 
      v-if="indexRef > 0 && !form.experiences[indexRef]?.uuid" 
      type="button" 
      @click="() => removeReference(indexRef)" 
      class="p-1 w-full bg-red-50 text-red-900 text-[11px] font-bold cursor-pointer rounded-md border border-gray-200 mt-2"
    >
      Remove this experience
    </button>
  </div>

  <button 
    type="button" 
    @click="addReference" 
    class="p-1 w-full bg-gray-50 my-3 text-[11px] font-bold cursor-pointer rounded-md border border-gray-200"
  >
    Add an experience
  </button>

  <!-- <div class="w-full p-2" v-for="(field,index) in fieldExperience" :key="index">
      <div class="w-full">
        <FormField
          v-for="value in field"
          :key="value.key"
          :field="value"
          v-model="form.experience[0][value.key]"
        />
      </div>
  </div> -->
</div>

</template>

<style>

</style>