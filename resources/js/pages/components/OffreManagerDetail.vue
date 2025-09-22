
<script lang="ts" setup>
import AddOffre from './AddOffre.vue';
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
                <!-- <div class="flex px-6 py-4  justify-between items-center w-full">
                    <h2 class="text-2xl font-semibold mb-4">
                        {{ data?.data[0].job.position_title }}
                    </h2>
                    
                </div> -->
                 <div class="p-6 space-y-6">
                        <!-- <pre>
                            {{ data?.data[0].job }}
                        </pre> -->
                        <!-- Card Publication & Job -->
            <div class="bg-white ">
                        <!-- Header -->
                        <div class=" px-6 py-4 border-b border-gray-100">
                            <h2 class="text-2xl font-bold text-primary">
                            {{ data?.data[0].reference }}
                            </h2>
                            <p class="text-sm text-gray-600">Type : {{ data?.data[0].type }}</p>
                        </div>

  <!-- Job Details -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
                <!-- Left column -->
                <div class="space-y-3">
                <p>
                    <span class="font-semibold text-gray-900">Code :</span>
                    {{ data?.data[0].job.code_recrutement }}
                </p>
                <p>
                    <span class="font-semibold text-gray-900">Position :</span>
                    {{ data?.data[0].job.position_title }}
                </p>
                        <!-- <p>
                            <span class="font-semibold text-gray-900">Centre :</span>
                            {{ data?.data[0].job.center }}
                        </p> -->
                </div>

                <!-- Right column -->
                <div class="space-y-3">
                <p>
                    <span class="font-semibold text-gray-900"> Pays :</span>
                     {{ data?.data[0].job.country_duty_station }}
                </p>
                    <!-- <p>
                        <span class="font-semibold text-gray-900">Grade :</span>
                        {{ data?.data[0].job.grade }}
                    </p>
                    <p>
                        <span class="font-semibold text-gray-900">Salaire :</span>
                        {{ data?.data[0].job.salary_post }} CFA
                    </p> -->
                </div>
            </div>
            </div>


                        </div>

                <div class="w-full">

                    <!-- <pre>{{ data?.data[0]?.candidat }}</pre> -->
                    <div class="flex px-6 py-4  justify-between items-center w-full">
                    <!-- <h4 class="font-bold">Gerer les offres</h4> -->
                    <h2 class="text-2xl font-semibold mb-4">Liste des candidats</h2>
                    <div class="flex justify-between items-center">
                        <button class="bg-secondary p-2 px-3 rounded-lg text-white cursor-pointer">
                            <i class="uil uil-export"></i>
                            <span> Importer</span>
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto border border-slate-200 bg-white ">
                    <Table :columns="columns" :rows="data?.data[0]?.candidat">
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