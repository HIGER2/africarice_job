
<script lang="ts" setup>
import Profile from './components/Profile.vue';
import ButtonBack from './components/ui/ButtonBack.vue';

defineProps({
  user: Object,
})
</script>


<template>
  <!-- <pre>{{ user.data }}</pre> -->
  <div class="max-w-6xl mx-auto p-8 space-y-10">
    <ButtonBack label="Retour à l'accueil" path="/"/>
    <Profile :user="user.data"/>
  </div>
</template>

<style>

</style>