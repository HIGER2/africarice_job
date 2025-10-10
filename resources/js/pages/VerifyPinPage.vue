<template>
<div class="min-h-screen flex items-center justify-center bg-white">
  <div class="w-full max-w-md bg-white rounded-lg p-8  shadow">
    <!-- Logo -->
    <div class="flex items-center w-full mb-4 gap-2 text-center justify-center">
      <a href="/">
        <img
          src="https://static.wixstatic.com/media/0839e4_7910df264aee46ba85347ab33684d739~mv2_d_4782_1488_s_2.png/v1/fill/w_520,h_160,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/0839e4_7910df264aee46ba85347ab33684d739~mv2_d_4782_1488_s_2.png"
          alt="Logo"
          class="w-40 object-contain"
        />
      </a>
    </div>

    <!-- Title -->
    <h2 class="text-2xl font-bold mb-6 text-center">Enter the login PIN code</h2>

    <!-- Alert -->
    <div class="bg-gray-100 mb-4 text-primary p-4 rounded-md mt-4" role="alert">
      <p class="font-bold mb-2">PIN code sent</p>
      <p class="text-sm">
        A PIN code has been sent to your email address. Please check your inbox and spam folder. If you haven't received it,
        <a
          href="/login"
          class="text-blue-800 underline font-semibold hover:text-blue-900 ml-1"
        >
          you can try again
        </a>.
      </p>
    </div>

    <!-- Form -->
    <form @submit.prevent="verifyPin" class="flex flex-col gap-4">
      <div class="flex flex-col gap-2">
        <label for="pin" class="font-medium text-gray-700">PIN Code</label>
        <input
          id="pin"
          v-model="pin"
          type="tel"
          placeholder="Enter your 4-digit PIN"
          required
          maxlength="4"
          class="p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
        />
      </div>

      <button
        type="submit"
        class="flex items-center justify-center cursor-pointer bg-primary text-white py-3 rounded-lg hover:bg-primary-700 transition"
      >
        <Spinnercomponent v-if="loading" />
        <span v-else>Login</span>
      </button>
    </form>

    <!-- Error message -->
    <p v-if="message" class="mt-4 text-center text-red-500">{{ message }}</p>
  </div>
</div>

</template>


<script setup>
import { ref, onMounted } from 'vue'
import Spinnercomponent from './components/Spinnercomponent.vue'
import { Inertia } from '@inertiajs/inertia'


const pin = ref('')
const message = ref('')
const loading =ref(false)

async function verifyPin() {
    try {
        loading.value = true
    await axios.post('/verify-pin', {pin: pin.value })
            Inertia.visit('/')
    } catch (err) {
        const errors = err.response?.data?.message || Object.values(err.response?.data?.errors).flat().join('\n')
        if (errors.length > 0) {
            alert(errors) // affiche toutes les erreurs dans un alert
        }
    }finally{
        loading.value = false
    }
}
</script>