
<script lang="ts" setup>
import { useApplyForm } from '../composables';
import FormField from './FormField.vue';


defineProps({
  form: Object,
})
const {cgiarFiled}=useApplyForm()

</script>

<template>
   <div class="w-full">
    <h2 class="text-xl font-bold mb-4">CGIAR Information</h2>

    <div class="w-full p-2" v-for="(field, index) in cgiarFiled" :key="index">
      <div class="w-full">
        <FormField
          v-for="value in field"
          :key="value.key"
          :id="value.key"
          :field="value"
          v-model="form.cgiar_information[value.key]"
          :disabled="value.type !== 'checkbox' && !form.cgiar_information['current']"
        />
      </div>
    </div>
  </div>

</template>

<style>

</style>