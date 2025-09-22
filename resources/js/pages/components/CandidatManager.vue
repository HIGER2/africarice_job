
<script lang="ts" setup>
import Table from './Table.vue';


defineProps({
  data: Array,
})

const columns = [
//   { key: "uuid", label: "# UUID" },
  { key: "user", label: "Nom complet" },
  { key: "nationality", label: "Nationalité" },
  { key: "country", label: "Pays" },
  { key: "city", label: "Ville" },
  { key: "experience_years", label: "Expérience" },
//   { key: "french_level", label: "Français" },
//   { key: "english_level", label: "Anglais" },
  { key: "documents", label: "document" },
//   { key: "created_at", label: "Créé le" },
//   { key: "actions", label: "Actions" }, // colonne pour slot
];

</script>


<template>
  <div>
     <div class="w-full">
                <div class="flex px-6 py-4  justify-between items-center w-full">
                    <!-- <pre>{{ data }}</pre> -->
                    <!-- <h4 class="font-bold">Gerer les offres</h4> -->
                        <h2 class="text-2xl font-semibold mb-4">
                            Gerer les candidats
                        </h2>
                    <div class="flex justify-between items-center gap-2">
                        <!-- <button class="border-[0.1rem] border-gray-200 p-2 px-3 rounded-lg text-primary cursor-pointer">Ajouter une publication</button> -->
                        <button class="bg-primary p-2 px-3 rounded-lg text-white cursor-pointer">
                            <i class="uil uil-export"></i>
                            Exporter
                        </button>
                    </div>
                </div>
                <div class="w-full">
                    
                    <div class="overflow-x-auto border border-slate-200 bg-white ">
                        <Table :columns="columns" :rows="data?.data">
                           <template #documents="{ row }">
                               <div class="flex flex-col gap-2">
                                    <a
                                    v-for="(value,index) in row.documents"
                                    :href="`/storage/${value.path}`"
                                    target="_blank"
                                    class="text-blue-600 hover:underline"
                                    >
                                    {{ value.name }}
                                    </a>
                               </div>
                            </template>

                                <template #actions="{ row }">
                                    <a :href="`/manager/candidat/${row.uuid}`" class="px-3 py-1 text-sm border rounded-md hover:bg-gray-50">
                                    Voir
                                    </a>
                                </template>
                        </Table>

                    </div>

                    <!-- Pagination (optionnel) -->
                    <div class="mt-4 flex items-center justify-between p-3">
                        <div class="text-sm text-gray-600">Affichage <span class="font-medium">1-3</span> sur <span class="font-medium">120</span></div>
                            <div class="inline-flex items-center space-x-2">
                                <button class="px-3 py-1 border rounded-md text-sm">Préc</button>
                                <button class="px-3 py-1 border rounded-md text-sm">Suiv</button>
                            </div>
                        </div>
                    </div>
            </div>
  </div>
</template>


<style>

</style>