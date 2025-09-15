
<script lang="ts" setup>
import { useApplyForm } from '../composables';
import FormField from './FormField.vue';

const {fieldDiploma,form}=useApplyForm()

</script>

<template>
<div class=" w-full">
      <h2 class="text-xl font-bold mb-4">Vos 3 derniers diplômes</h2>
        <div class="w-full p-2" v-for="(field,index) in fieldDiploma" :key="index">
            <div class="w-full" >
              <FormField
              v-for="value in field"
              :key="value.key"
              :field="value"
              v-model="form.diplomas[value.key]"
            />
            </div>
        </div>
          
  </div>
</template>

<style>

</style>