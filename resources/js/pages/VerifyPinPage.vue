<template>
<div class="min-h-screen flex items-center justify-center">
<div class="w-full max-w-md bg-white rounded-lg  p-8">
<h2 class="text-2xl font-bold mb-6 text-center">Saisir le code PIN de connexion</h2>

 <div class="bg-gray-100  mb-4  text-primary p-4 rounded-md mt-4" role="alert">
    <p class="font-bold mb-2">Code PIN envoyé</p>
    <p class="text-sm">
      Un code PIN de connexion a été envoyé à votre adresse email. Veuillez vérifier votre boîte de réception ainsi que le dossier spam.
      Si vous ne l'avez pas reçu, vous pouvez
      <a href="/login"  class="text-blue-800 underline font-semibold hover:text-blue-900 ml-1">
        réessayer
      </a>.
    </p>
  </div>
<form @submit.prevent="verifyPin" class="flex flex-col gap-4">
<input v-model="pin"  type="tel" placeholder="Code PIN" required maxlength="4" class="p-3 border rounded-lg" />
    <button type="submit" class="flex items-center justify-center cursor-pointer bg-primary text-white py-3 rounded-lg hover:bg-primary-700">
            <Spinnercomponent v-if="loading"/>
            <span v-else>Connexion</span>
    </button>
</form>
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