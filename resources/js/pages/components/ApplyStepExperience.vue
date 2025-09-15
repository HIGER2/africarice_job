
<script lang="ts" setup>
import { useApplyForm } from '../composables';
import FormField from './FormField.vue';

const {fieldExperience,form}=useApplyForm()

</script>

<template>
<div class=" w-full">
      <h2 class="text-xl font-bold mb-4">Votre plus récente expérience</h2>
        <div class="w-full p-2" v-for="(field,index) in fieldExperience" :key="index">
            <div class="w-full" >
              <FormField
              v-for="value in field"
              :key="value.key"
              :field="value"
              v-model="form.experience[0][value.key]"
            />
                <!-- <div 
                v-for="(value,index) in field"
                :class="value.type=='checkbox' ? 'flex-row items-center':'flex-col items-start justify-end' " 
                class="flex gap-1 w-full cursor-pointer mb-2" >
                    <label :for="value.key" class="block text-[13px]  cursor-pointer  text-primary font-medium mb-1">
                        {{ value.label }}
                    </label>
                      <input 
                      :id="value.key"
                      :placeholder="value.label"
                        :class="value.type=='checkbox' ? '':'w-full' "
                        :type="value.type"
                          class=" border border-gray-300 p-2 rounded placeholder:text-[11px] placeholder:font-medium" 
                      />
                </div> -->
            </div>
        </div>
          
  </div>
</template>

<style>

</style>