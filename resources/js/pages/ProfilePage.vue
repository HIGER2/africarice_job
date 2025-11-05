
<script lang="ts" setup>
import Profile from './components/Profile.vue';
import ButtonBack from './components/ui/ButtonBack.vue';
// import { useApplyForm } from './composables';

defineProps({
  user: Object,
})

</script>


<template>
  <!-- <pre>{{ user.data }}</pre> -->
  <div class="max-w-4xl mx-auto p-8 space-y-4">
    <div class="flex justify-between items-center">
      <ButtonBack label="Retour à l'accueil" path="/"/>
      <!-- <a href="/apply-job" class="text-blue-800 hover:text-blue-900 underline font-medium">
        Mettre à jour mes informations <br />
        <span class="text-gray-600 text-sm">Update my information</span>
      </a> -->

    </div>
    <Profile :user="user.data"/>
  </div>
</template>

<style>

</style>