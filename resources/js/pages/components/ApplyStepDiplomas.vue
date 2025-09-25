
<script lang="ts" setup>
import { useApplyForm } from '../composables';
import FormField from './FormField.vue';

const props =defineProps({
  form: Object,
  fieldDiploma: Array
})

const {fieldDiploma,initDiploma} =useApplyForm()


const addReference = () => {

 if (props.form.diplomas.length >= 3) {
  alert('Vous avez atteint la limite de diplôme.')
  return false
}
  props.form.diplomas.push({ ...initDiploma })
}

// Supprimer une référence
const removeReference = (index: number) => {
  props.form.diplomas.splice(index, 1)
}

</script>

<template>
<div class=" w-full">
      <h2 class="text-xl font-bold mb-4">Vos 3 derniers diplômes</h2>
        <div class="w-full mb-2 border-b border-b-gray-300 py-3" v-for="(refItem, indexRef) in form.diplomas" :key="indexRef">
              <div class="w-full flex gap-2" v-for="(fieldGroup, index) in fieldDiploma" :key="index">
                <template v-for="value in fieldGroup" :key="value.key">
                  <FormField
                    :field="value"
                    v-model="form.diplomas[indexRef][value.key]"
                  />
                </template>
              </div>
              <button v-if="indexRef>0 && !form.diplomas[indexRef]?.uuid" type="button" @click="() => removeReference(indexRef)" 
                class="p-1 w-full bg-red-50 text-red-900 text-[11px] font-bold cursor-pointer rounded-md border border-gray-200 mt-2">
                Supprimer ce diplôme 
              </button>
          </div>
          <button type="button" @click="addReference" class="p-1 w-full bg-gray-50 my-3  text-[11px] font-bold cursor-pointer rounded-md border border-gray-200 ">Ajouter un diplôme</button>
      

        <!-- <div class="w-full p-2" v-for="(field,index) in fieldDiploma" :key="index">
            <div class="w-full" >
              <template v-for="(value,indexKey) in field"
              :key="indexKey + index">
               
                <FormField
                :field="value"
                v-model="form.diplomas[value.key]"
              />
              </template>
             
            </div>
        </div> -->
          
  </div>
</template>

<style>

</style>