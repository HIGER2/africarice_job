
<script lang="ts" setup>
import AddOffre from './AddOffre.vue';
import Profile from './Profile.vue';
import Table from './Table.vue';

defineProps({
  data: Array,
})

// const columns = [
// //   { key: "uuid", label: "# UUID" },
//   { key: "user", label: "Nom complet" },
//   { key: "nationality", label: "Nationalité" },
//   { key: "country", label: "Pays" },
//   { key: "city", label: "Ville" },
//   { key: "experience_years", label: "Expérience" },
// //   { key: "french_level", label: "Français" },
// //   { key: "english_level", label: "Anglais" },
//   { key: "documents", label: "document" },
// //   { key: "created_at", label: "Créé le" },
// //   { key: "actions", label: "Actions" }, // colonne pour slot
// ];

</script>

<template>
 <div>
  <!-- <pre>{{ data.data }}</pre> -->
   <Profile :user="data.data"/>

 </div>
</template>


<style>

</style>