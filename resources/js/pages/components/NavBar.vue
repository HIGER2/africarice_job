
<script lang="ts" setup>
import AuthUser from './AuthUser.vue';
import Logo from './logo.vue';

defineProps({
  user: Object
})
</script>

<template>
   <nav class="bg-white border-b border-b-gray-200  sticky top-0 z-20 px-4 p-2 flex justify-between items-center">
        <!-- Bouton Connexion à gauche -->
        <!-- Logo à droite -->
        <div class="flex items-center gap-2">
            <a href="/">
                   <Logo/>
            </a>
        </div>
        <template v-if="user">
        <!-- Utilisateur connecté -->
        <AuthUser :user />
        </template>
        <template v-else>
        <!-- Pas connecté -->
        <a href="/login" class="px-4 py-2 bg-primary text-white rounded-lg  transition">
            Sign In
        </a>
        </template>
    </nav>
</template>


<style>

</style>