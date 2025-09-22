
<script lang="ts" setup>
import AuthUser from './AuthUser.vue';

defineProps({
  user: Object
})
</script>

<template>
   <nav class="bg-white border-b border-b-gray-200  sticky top-0 z-20 px-4 p-2 flex justify-between items-center">
        <!-- Bouton Connexion à gauche -->
        <!-- Logo à droite -->
        <div class="flex items-center gap-2">
            <img src="https://static.wixstatic.com/media/0839e4_7910df264aee46ba85347ab33684d739~mv2_d_4782_1488_s_2.png/v1/fill/w_520,h_160,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/0839e4_7910df264aee46ba85347ab33684d739~mv2_d_4782_1488_s_2.png" alt="Logo" 
            class="w-40 object-contain" />
        </div>
        <template v-if="user">
        <!-- Utilisateur connecté -->
        <AuthUser :user />
        </template>
        <template v-else>
        <!-- Pas connecté -->
        <a href="/login" class="px-4 py-2 bg-primary text-white rounded-lg  transition">
            Connexion
        </a>
        </template>
    </nav>
</template>


<style>

</style>