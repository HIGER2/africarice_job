


<script setup>
import { ref, onMounted } from 'vue'
import Spinnercomponent from './components/Spinnercomponent.vue'
import { Inertia } from '@inertiajs/inertia'

const email = ref('')
const message = ref('')
const loading =ref(false)


    async function verifyEmail() {
        try {
                loading.value = true
            await axios.post('/verify-email', { email: email.value})
                Inertia.visit('/verify')
            } catch (err) {

                let message = 'Erreur lors de la vérification de l\'email.'
                
                if (err?.response?.data?.errors?.length > 0) {
                    message  =  Object.values(err?.response?.data?.errors).flat().flat().join('\n')
                }else if (err?.response?.data?.message) {
                    message = err?.response?.data?.message
                }
                alert(message)
                // console.log(err?.response?.data?.errors);
                // console.log(err?.response?.data?.message);
                
                // const errors = Object.values(err.response?.data?.errors).flat()
                // if (errors.length > 0) {
                //     alert(errors.join('\n')) // affiche toutes les erreurs dans un alert
                // }
            }finally{
                loading.value = false
        }
    }

function goRegister() {
window.location.href = '/register.html'
}


// async function handleLogin() {
// try {
// await axios.post('/api/login', { email: email.value })
// alert('Email envoyé avec votre code PIN.')
// window.location.href = '/verify-pin.html?email=' + encodeURIComponent(email.value)
// } catch (err) {
// alert(err.response?.data?.message || 'Erreur lors de la connexion')
// }
// }
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-white">
  <div class="w-full max-w-md bg-white rounded-lg shadow p-8">
    <!-- Logo -->
    <div class="flex items-center w-full mb-6 justify-center">
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
      Sign In
    </h2>

    <!-- Form -->
    <form @submit.prevent="verifyEmail" class="flex flex-col gap-4">
      <div class="w-full flex flex-col gap-2">
        <label for="email" class="font-medium text-gray-700">Email</label>
        <input
          id="email"
          v-model="email"
          type="email"
          placeholder="Enter your email"
          required
          class="p-3 border rounded-lg focus:ring-2 focus:ring-primary focus:outline-none"
        />
      </div>

      <button
        type="submit"
        class="flex items-center justify-center cursor-pointer bg-primary text-white py-3 rounded-lg hover:bg-primary-700 transition-colors duration-200"
      >
        <Spinnercomponent v-if="loading" />
        <span v-else>Sign in</span>
      </button>
    </form>

    <!-- Footer -->
    <p class="mt-6 text-center text-gray-500">
  Vous n'avez pas de compte ? / Don’t have an account?
  <a href="/register" class="text-indigo-600 font-medium hover:underline">
    S’inscrire / Sign up
  </a>
</p>


    <p v-if="message" class="mt-4 text-center text-red-500">{{ message }}</p>
  </div>
</div>

</template>

