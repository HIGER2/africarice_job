



<template>
<div class="min-h-screen flex items-center justify-center bg-white">
    <div class="w-full max-w-md bg-white rounded-lg  p-8">
        <div class="flex items-center w-full mb-4 gap-2 text-center">
            <a href="/">
                 <img src="https://static.wixstatic.com/media/0839e4_7910df264aee46ba85347ab33684d739~mv2_d_4782_1488_s_2.png/v1/fill/w_520,h_160,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/0839e4_7910df264aee46ba85347ab33684d739~mv2_d_4782_1488_s_2.png" alt="Logo" 
             class="w-40 object-contain" />
            </a>
        </div>
        <h2 class="text-2xl font-bold mb-6 ">Connexion</h2>
        <form @submit.prevent="verifyEmail" class="flex flex-col gap-4">
        <div class="w-full flex flex-col gap-2">
            <label for="" >Email</label>
            <input v-model="email" type="email" placeholder="Email" required class="p-3 border rounded-lg" />
        </div>
        <input v-if="active" v-model="pin" type="text" placeholder="Code PIN" required maxlength="4" class="p-3 border rounded-lg" />
            <button type="submit" class="flex items-center justify-center cursor-pointer bg-primary text-white py-3 rounded-lg hover:bg-primary-700">
                <Spinnercomponent v-if="loading"/>
                <span v-else>Continuer</span>
            </button>
        </form>
        <p class="mt-4 text-center text-gray-500">
            Pas de compte ? <a href="/register" class="text-indigo-600 hover:underline">S'inscrire</a>
        </p>
        <p v-if="message" class="mt-4 text-center text-red-500">{{ message }}</p>
    </div>
</div>
</template>


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
                const errors = Object.values(err.response?.data?.errors).flat()
                if (errors.length > 0) {
                    alert(errors.join('\n')) // affiche toutes les erreurs dans un alert
                }
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