
<script lang="ts" setup>
import { Inertia } from '@inertiajs/inertia';
import { ref } from 'vue';

defineProps({
  user: Object,
})

const isOpen = ref(false);
const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};


function logout() {
  Inertia.post('/logout');
}
</script>

<template>
  <div>
    <div @click="toggleDropdown"  class="px-2 relative flex cursor-pointer items-center gap-1 py-1 border  rounded-full">
        <span class="block w-8 h-8 bg-primary text-white leading-8 text-center rounded-full"><i class="uil uil-user"></i></span>
        <span class="uppercase font-black text-sm cursor">{{ user.name }}</span>
        <ul v-if="isOpen" class="absolute p-2 top-12 right-0 bg-white shadow-lg rounded-md w-40 py-2 z-10">
              <li class="text-sm hover:bg-gray-300 w-full px-3 py-2 rounded-md cursor-pointer">
                <a href="/" class="w-full block">Accueil</a>
            </li>
            <li  class="text-sm hover:bg-gray-300 w-full px-3 py-2 rounded-md cursor-pointer">
                <a href="/manager/offres" class="w-full block">
                    Admin Panel
                </a>
            </li>

             <!-- <li v-if="user.role=='admin'" class="text-sm hover:bg-gray-300 w-full px-3 py-2 rounded-md cursor-pointer">
                <a href="/manager/offres" class="w-full block">
                    Admin Panel
                </a>
            </li> -->
          
            <!-- <li class="text-sm hover:bg-gray-100 px-3 py-2 rounded-md cursor-pointer">Parametres</li> -->
            <li @click="logout" class="text-sm hover:bg-gray-300 w-full px-3 py-2 rounded-md cursor-pointer">Deconnexion</li>
        </ul>
      </div>
  </div>
</template>


<style>

</style>