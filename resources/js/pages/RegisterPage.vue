<template>
    <div class="min-h-screen flex items-center justify-center ">
        <div class="w-full max-w-md bg-white rounded-lg  p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Inscription</h2>
        <form @submit.prevent="handleRegister" class="flex flex-col gap-4">
        <div class="flex flex-col">
            <label for="">Nom</label>
            <input v-model="formData.name" type="text" placeholder="Prénom" required class="p-3 border rounded-lg" />
        </div>
        <div class="flex flex-col">
            <label for="">Prénoms</label>
            <input v-model="formData.last_name" type="text" placeholder="Nom" required class="p-3 border rounded-lg" />
        </div>
        <div class="flex flex-col">
            <label for="">Téléphone</label>
            <input v-model="formData.phone" required type="tel" placeholder="Téléphone"  class="p-3 border rounded-lg" />
        </div>
        <div class="flex flex-col">
            <label for="">Email</label>
            <input v-model="formData.email" type="email" placeholder="Email" required class="p-3 border rounded-lg" />
        </div>
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mt-4" role="alert">
        <p class="font-bold">Information personnelle</p>
        <p>Les informations que vous saisissez seront considérées comme personnelles.</p>
        </div>

        <button type="submit" class="flex items-center justify-center cursor-pointer bg-primary text-white py-3 rounded-lg hover:bg-primary-700">
            <Spinnercomponent v-if="loading"/>
            <span v-else>S'inscrire</span>
        </button>
        </form>
            <p class="mt-4 text-center text-gray-500">
            Déjà un compte ? <a href="/login" class="text-indigo-600 hover:underline">Connectez-vous</a>
            </p>
        </div>
</div>
</template>


<script setup>
import { reactive, ref } from 'vue'
import axios from 'axios'
import { Inertia } from '@inertiajs/inertia'
import Spinnercomponent from './components/Spinnercomponent.vue'


const formData = reactive({
    name: 'douma',
    last_name: 'armand',
    email: 'ddd@gmail.com',
    phone:'0778618454'
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
        const errors = Object.values(err.response?.data?.errors).flat()
      if (errors.length > 0) {
        alert(errors.join('\n')) // affiche toutes les erreurs dans un alert
      }
        // console.log(err.response?.data?.errors/);
        
    // alert(err.response?.data?.errors+'' || 'Erreur lors de l\'inscription')
    }finally{
        loading.value = false
    }
}
</script>