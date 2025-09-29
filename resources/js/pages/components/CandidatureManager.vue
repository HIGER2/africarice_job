
<script lang="ts" setup>
import { computed } from 'vue';
import Table from './Table.vue';
import { useApplyForm } from '../composables';

const props = defineProps({
  data: {
    type: Array,
    default: () => [],
  },
});

const {exportToExcel}=useApplyForm()

const labelsMap = {
  candidature_id: "N° Candidature",
  publication_reference: "Référence Publication",
  //   user_id: "ID Utilisateur",
  candidat: "Candidat",
  user_email: "Email",
  user_phone: "Téléphone",
//   candidature_uuid: "UUID Candidature",
//   status: "Statut",
  date: "Date",
//   publication_id: "ID Publication",
//   publication_type: "Type Publication",
//   publication_is_published: "Publication Publiée",
  publication_is_closed: "Publication Clôturée",

//   job_id: "ID Poste",
  job_position_title: "Intitulé Poste",
  // job_center: "Centre",
  // job_city: "Ville",
  job_country: "Pays",
//   job_grade: "Grade",
//   job_contract_from: "Début Contrat",
//   job_contract_to: "Fin Contrat",
//   job_salary_post: "Salaire",

//   application_id: "ID Application",
//   application_uuid: "UUID Application",
  origin_nationality: "Nationalité",
  origin_country: "Pays d'origine",
  origin_city: "Ville d'origine",
  origin_experience_years: "Années Expérience",
  origin_french_level: "Niveau Français",
  origin_english_level: "Niveau Anglais",
//   document_id: "ID Document",
  documents: "Document",
//   document_path: "Chemin Document",
};

  const columns = computed(() => {
   if (!props.data?.data || props.data.data.length === 0) return [];
      const firstRowKeys = Object.keys(props.data.data[0]);

      return firstRowKeys.map((key) => ({
        key,
        label: labelsMap[key] || key, // si pas trouvé dans labelsMap → utilise la clé
      }));
    // const firstRowKeys = Object.keys(props.data.data[0]);
    // return Object.keys(labelsMap)
    //     .filter(key => firstRowKeys.includes(key))
    //     .map(key => ({ key, label: labelsMap[key] }));
    });

    console.log(columns.value);
    
    const exporteData= (columns,data)=>{
      delete columns[columns.findIndex(col => col.key === 'documents')];
      exportToExcel(columns,data, 'Liste_des_candidatures.xlsx')
    }
</script>


<template>
  <div>
     <div class="w-full">
                <div class="flex px-6 py-4  justify-between items-center w-full">
                    <!-- <pre>{{ data }}</pre> -->
                    <!-- <h4 class="font-bold">Gerer les offres</h4> -->
                        <h2 class="text-2xl font-semibold mb-4">
                            Gerer les candidatures
                        </h2>
                    <div class="flex justify-between items-center gap-2">
                        <!-- <button class="border-[0.1rem] border-gray-200 p-2 px-3 rounded-lg text-primary cursor-pointer">Ajouter une publication</button> -->
                        <button
                        type="button"
                        @click="exporteData(columns,data?.data)"
                        class="bg-primary p-2 px-3 rounded-lg text-white cursor-pointer">
                          <i class="uil uil-export"></i>
                          Exporter</button>
                    </div>
                </div>
                <div class="w-full">
                    
                    <div class="overflow-x-auto border border-slate-200 bg-white ">
                    <Table :columns="columns" :rows="data?.data">
                            <!-- Slot document (avec lien téléchargeable) -->
                            <template #documents="{ row }">
                               <div class="flex flex-col gap-2">
                                    <a
                                    v-for="(value,index) in row.documents"
                                    :href="`${value.path}`"
                                    download
                                    class="text-blue-600 hover:underline"
                                    >
                                    {{ value.name }}
                                    </a>
                               </div>
                            </template>
                                <template #publication_is_closed="{ row }">
                                    <span
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                    :class="row.publication_is_closed === 'Ouverte'
                                        ? 'bg-blue-100 text-blue-800'
                                        : 'bg-red-100 text-red-800'"
                                    >
                                    {{ row.publication_is_closed }}
                                    </span>
                                </template>

                                <!-- Slot actions -->
                                <!-- <template #actions="{ row }">
                                    <button class="px-3 py-1 text-sm border rounded-md hover:bg-gray-50">
                                    Voirds
                                    </button>
                                </template> -->
                            </Table>

                    </div>

                    <!-- Pagination (optionnel) -->
                      <!-- <div class="mt-4 flex items-center justify-between p-3">
                            <div class="text-sm text-gray-600">Affichage <span class="font-medium">1-3</span> sur <span class="font-medium">120</span>
                            </div>
                              <div class="inline-flex items-center space-x-2">
                                  <button class="px-3 py-1 border rounded-md text-sm">Préc</button>
                                  <button class="px-3 py-1 border rounded-md text-sm">Suiv</button>
                              </div>
                        </div> -->
                    </div>
            </div>
  </div>
</template>


<style>

</style>