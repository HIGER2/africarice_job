
<script lang="ts" setup>
import { useApplyForm } from '../composables';
import FormField from './FormField.vue';
import FormFields from './FormFields.vue';


const props =defineProps({
  form: Object,
  documents: Array,
  notApply: Boolean
})

const initReference={
        id:null,
        full_name:'',
        function:'',
        phone:'',
        email:'',
        country_code:"+225",
    }

const {fieldDocument,fieldReference,handleFile,documentPreview}=useApplyForm()

const addReference = () => {
 if (props.form.references.length >= 3) {
  alert('Vous avez atteint la limite de références.')
  return false
}
  props.form.references.push({ ...initReference })
}

// Supprimer une référence
const removeReference = (index: number) => {
  props.form.references.splice(index, 1)
}

</script>

<template>
<div class="w-full">
  <!-- References Section -->
   <!-- <pre>{{ form.references }}</pre> -->
  <div v-if="!notApply">
        <h2 class="text-xl font-bold mb-4">References</h2>

      <div class="w-full mb-2 border-b border-b-gray-300 py-3" 
          v-for="(refItem, indexRef) in form.references" :key="indexRef">

        <div class="w-full flex gap-2" v-for="(fieldGroup, index) in fieldReference" :key="index">
          <template v-for="value in fieldGroup" :key="value.key">
            <FormFields
              v-if="value.type=='select-input'"
              :field="value"
              :id="`${indexRef}${value.key}`"
              v-model="form.references[indexRef]"
            />

            <FormField
              v-else
              :field="value"
              v-model="form.references[indexRef][value.key]"
            />
          </template>
        </div>

        <button 
          v-if="indexRef > 0 && !form.references[indexRef]?.uuid" 
          type="button" 
          @click="() => removeReference(indexRef)" 
          class="p-1 w-full bg-red-50 text-red-900 text-[11px] font-bold cursor-pointer rounded-md border border-gray-200 mt-2"
        >
          Remove this reference
        </button>
      </div>

      <button 
        type="button" 
        @click="addReference" 
        class="p-1 w-full bg-gray-50 my-3 text-[11px] font-bold cursor-pointer rounded-md border border-gray-200"
      >
        Add a reference
      </button>

  </div>
  <!-- CV Upload Section -->
  <div class="w-full" v-if="!documents?.length > 0">
    <h2 class="text-xl font-bold mb-4">CV</h2>
    <div class="w-full mb-4" v-for="(value, index) in fieldDocument" :key="index">
      <label class="cursor-pointer">
        <FormField
          :accept="value.accept"
          :field="value"
          v-model="form.documents[index].file"
        />
        <div class="border border-dashed p-5 flex flex-col w-full text-center cursor-pointer">
          <span class="text-gray-500">
            Click here to upload a file
          </span>
          <span class="flex items-center justify-center space-x-2 bg-green-100 text-green-800 px-3 py-2 rounded border border-green-300">
            <!-- File Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
              <path fill="currentColor" d="m19.21 12.04l-1.53-.11l-.3-1.5A5.484 5.484 0 0 0 12 6C9.94 6 8.08 7.14 7.12 8.96l-.5.95l-1.07.11A3.99 3.99 0 0 0 2 14c0 2.21 1.79 4 4 4h13c1.65 0 3-1.35 3-3c0-1.55-1.22-2.86-2.79-2.96m-5.76.96v3h-2.91v-3H8l4-4l4 4z" opacity=".3"/>
              <path fill="currentColor" d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5c0-2.64-2.05-4.78-4.65-4.96M19 18H6c-2.21 0-4-1.79-4-4c0-2.05 1.53-3.76 3.56-3.97l1.07-.11l.5-.95A5.47 5.47 0 0 1 12 6c2.62 0 4.88 1.86 5.39 4.43l.3 1.5l1.53.11A2.98 2.98 0 0 1 22 15c0 1.65-1.35 3-3 3M8 13h2.55v3h2.9v-3H16l-4-4z"/>
            </svg>

            <!-- File Name -->
            <span class="font-medium max-w-xs truncate">
              {{ form.documents[index].file?.name }}
            </span>
          </span>
        </div>
      </label>
    </div>
  </div>

  <!-- List of Uploaded Documents -->
  <div v-else class="w-full">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">📂 Document List</h2>
    <ul class="divide-y divide-gray-200">
      <li
        v-for="doc in documents"
        :key="doc.uuid"
        class="flex items-center justify-between py-3"
      >
        <!-- File Name -->
        <span class="text-gray-700 text-sm truncate">{{ doc.name }}</span>

        <!-- Download Button -->
        <a
          :href="doc.path"
          download
          target="_blank"
          class="text-blue-600 hover:underline text-sm font-medium"
        >
          Download
        </a>
      </li>
    </ul>
  </div>
</div>

</template>

<style>

</style>