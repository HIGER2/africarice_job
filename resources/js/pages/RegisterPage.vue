<template>

<div class="min-h-screen flex items-center justify-center bg-white px-4">
  <div class="w-full max-w-md bg-white rounded-lg shadow p-8">
    <!-- Logo -->
    <div class="flex justify-center mb-6">
      <a href="/">
        <img
          src="https://static.wixstatic.com/media/0839e4_7910df264aee46ba85347ab33684d739~mv2_d_4782_1488_s_2.png/v1/fill/w_520,h_160,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/0839e4_7910df264aee46ba85347ab33684d739~mv2_d_4782_1488_s_2.png"
          alt="Logo"
          class="w-40 object-contain"
        />
      </a>
    </div>

    <!-- Title -->
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">
      Create Your Account
    </h2>

    <!-- Form -->
    <form @submit.prevent="handleRegister" class="flex w-full flex-col gap-4">
      <!-- First Name / Last Name -->
      <div class="flex flex-col sm:flex-row gap-3 w-full">
        <div class="flex flex-col flex-1">
          <label class="text-sm font-medium text-gray-700 mb-1">Family Name / nom</label>
          <input
            v-model="formData.name"
            type="text"
            placeholder="Enter your first name"
            required
            class="p-3 border w-full rounded-lg focus:ring-2 focus:ring-primary focus:outline-none"
          />
        </div>
        <div class="flex flex-col flex-1">
          <label class="text-sm font-medium text-gray-700 mb-1">First Name / Prénoms</label>
          <input
            v-model="formData.last_name"
            type="text"
            placeholder="Enter your last name"
            required
            class="p-3 border w-full rounded-lg focus:ring-2 focus:ring-primary focus:outline-none"
          />
        </div>
      </div>

      <!-- Phone / Email -->
      <div class="  gap-3 w-full">
        <div class="flex mb-3 flex-col flex-1">
        <label class="text-sm font-medium text-gray-700 mb-1">Phone Number</label>
        
        <div class="flex">
          <!-- Sélecteur des indicatifs pays -->
          <select
            required
            v-model="formData.country_code"
            class="p-3 max-w-max border rounded-l-lg focus:ring-2 focus:ring-primary focus:outline-none"
          >
            <option v-for="country in countryCode" :key="country.code" :value="country.code">
              {{ country.abbreviation }} ({{ country.code }})
            </option>
          </select>

          <!-- Champ téléphone -->
          <input
            v-model="formData.phone"
            type="tel"
            placeholder="e.g. 07 00 00 00 00"
            required
            class="p-3 w-full border-t border-b border-r rounded-r-lg focus:ring-2 focus:ring-primary focus:outline-none"
          />
        </div>
      </div>

        <div class="flex flex-col flex-1">
          <label class="text-sm font-medium text-gray-700 mb-1">Email Address</label>
          <input
            v-model="formData.email"
            type="email"
            placeholder="example@mail.com"
            required
            class="p-3 border w-full rounded-lg focus:ring-2 focus:ring-primary focus:outline-none"
          />
        </div>
      </div>

      <!-- Info Box -->
      <div
        class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 text-sm rounded-md"
        role="alert"
      >
        <p class="font-bold">Personal Information</p>
        <p>The information you enter will be considered as personal.</p>
      </div>

      <!-- Submit Button -->
      <button
        type="submit"
        class="flex items-center justify-center cursor-pointer bg-primary text-white py-3 rounded-lg hover:bg-primary-700 transition-all"
      >
        <Spinnercomponent v-if="loading" />
        <span v-else>Create Account</span>
      </button>
    </form>

    <!-- Footer -->
    <p class="mt-5 text-center text-gray-500 text-sm">
       Déjà un compte ? /Already have an account? <br>  
        <a href="/login" class="text-indigo-600 hover:underline">Connectez-vous / Sign in</a>
    </p>

  </div>
</div>

</template>


<script setup>
import { reactive, ref } from 'vue'
import axios from 'axios'
import { Inertia } from '@inertiajs/inertia'
import Spinnercomponent from './components/Spinnercomponent.vue'
import countryCode from '../data/country.json'

const formData = reactive({
    name: '',
    last_name: '',
    email: '',
    phone:'',
    country_code:"+225",

})
const loading=ref(false)

function goLogin() {
window.location.href = '/login.html'
}

const submit = () => {
  Inertia.post('/register', formData, {
    onSuccess: (data) => {
        console.log(data);
        
    //   alert('Connexion réussie 🎉')
    },
    onError: (errors) => {
      console.log(errors)
      alert('Erreur de connexion ❌')
    }
  })
}


async function handleRegister() {
    try {
        loading.value = true
        let response = await axios.post('/register',formData)
        console.log(response);
        Inertia.visit('/verify')
    // alert('Inscription réussie ! Vérifiez votre email pour le code PIN.')
    // goLogin()
    } catch (err) {
        let message = 'Erreur lors de la vérification de l\'email.'
                
        if (err?.response?.data?.errors?.length > 0) {
            message  =  Object.values(err?.response?.data?.errors).flat().flat().join('\n')
        }else if (err?.response?.data?.message) {
            message = err?.response?.data?.message
        }
        alert(message)
    }finally{
        loading.value = false
    }
}
</script>