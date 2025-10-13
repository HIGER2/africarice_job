
<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { Inertia } from '@inertiajs/inertia'
import { useApplyForm } from '../composables'

const fileInput = ref(null)
const isUploading = ref(false)
const {uploadCv}=useApplyForm()
const triggerFileSelect = () => {
  fileInput.value.click()
}

const handleFileSelect = async (event) => {
    const file = event.target.files[0]
    if (!file) return
  // Allowed file types
    const allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg',
        'image/png',
    ]
        if (!allowedTypes.includes(file.type)) {
            alert('Invalid file type. Please select a valid CV (PDF, Word, or Image).')
            return
        }
        const confirmed = confirm(`Are you sure you want to upload "${file.name}" as your CV?`)
        if (!confirmed) return

        isUploading.value = true
        await uploadCv(file)

        isUploading.value = false
        event.target.value = '' // reset file input
}
</script>



<template>
  <div class="flex items-center justify-center mt-3">
    <button
      @click="triggerFileSelect" 
      :disabled="isUploading"
      class="px-4 text-[13px] border border-gray-200  cursor-pointer font-medium py-2 bg-gray-100 text-primary rounded-xl hover:shadow hover:bg-gray-200 inline-flex items-center gap-2 mb-3"
    >
      <!-- Spinner -->
      <svg
        v-if="isUploading"
        class="animate-spin h-5 w-5 text-primary"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
      >
        <circle
          class="opacity-25"
          cx="12"
          cy="12"
          r="10"
          stroke="currentColor"
          stroke-width="4"
        ></circle>
        <path
          class="opacity-75"
          fill="currentColor"
          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
        ></path>
      </svg>

      <!-- Button text -->
      <span>
        {{ isUploading ? 'Uploading CV...' : 'Upload or update your CV' }}
      </span>
    </button>

    <!-- Hidden file input -->
    <input
      ref="fileInput"
      type="file"
      accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
      class="hidden"
      @change="handleFileSelect"
    />
  </div>
</template>
