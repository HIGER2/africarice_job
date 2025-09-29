
<script lang="ts" setup>
import { computed } from 'vue';
import AddOffre from './AddOffre.vue';
import Table from './Table.vue';
import { useApplyForm } from '../composables';
import ButtonBack from './ui/ButtonBack.vue';

// import { usePage } from '@inertiajs/vue3'


// const page = usePage()
// const flash = page.props.flash

const props= defineProps({
  data: Array,
})
const {exportToExcel,updateOffres}=useApplyForm()

const labelsMap ={
    candidat: "Candidat",
    user_email: "Email",
    user_phone: "Téléphone",
    origin_nationality: "Nationalité",
    origin_country: "Pays",
    origin_city: "Ville",
    origin_experience_years: "Expérience",
    origin_french_level: "Niveau français",
    origin_english_level: "Niveau Anglais",
}

const dataValue = computed(()=>props.data.data[0])

const labelsExclu =['uuid']

  const columns = computed(() => {
   if (!props.data?.data || props.data.data.length === 0) return [];
      const firstRowKeys = Object.keys(props.data.data[0]?.candidat[0] || {}).filter(key => !labelsExclu.includes(key));

      return firstRowKeys.map((key) => ({
        key,
        label: labelsMap[key] || key, // si pas trouvé dans labelsMap → utilise la clé
      }));
    });
    
    const exporteData= (columns,data)=>{
      delete columns[columns.findIndex(col => col.key === 'documents')];
      exportToExcel(columns,data, 'Liste_des_candidatures.xlsx')
    }

// const columns = [
// //   { key: "uuid", label: "# UUID" },
//   { key: "candidat", label: "Nom complet" },
//   { key: "user_email", label: "Email" },
//   { key: "user_phone", label: "Téléphone" },
//   { key: "origin_nationality", label: "Nationalité" },
//   { key: "origin_country", label: "Pays" },
//   { key: "origin_city", label: "Ville" },
//   { key: "origin_experience_years", label: "Expérience" },
//   { key: "origin_french_level", label: "Niveau français" },
//   { key: "origin_english_level", label: "Niveau Anglais" },

// ];

</script>

<template>
  <div>
     <div class="w-full">
        <!-- <pre>{{ data }}</pre> -->
        <!-- <div v-if="$page.props.flash.message" class="alert">
            {{ $page.props.flash.message }}
        </div> -->
            <div class="w-full mx-auto bg-white rounded-2xl  px-6">
                <div class="flex justify-between items-center py-4 ">
                    <ButtonBack path="/manager/offres" />
                    <div class="flex items-center gap-2">
                        <!-- Bouton Publication -->
                        <Button 
                            @click="() => updateOffres(data?.data[0]?.is_published ? 0 : 1, data?.data[0]?.uuid, 'is_published')"
                            type="button"
                            class="max-w-xs text-[12px] px-4 py-2 cursor-pointer font-medium rounded-lg hover:shadow inline-flex items-center gap-2 mb-3"
                            :class="data?.data[0]?.is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'"
                        >
                            <span v-if="data?.data[0]?.is_published">
                                <i class="uil uil-eye"></i>
                                Dépublier l’offre
                            </span>
                            <span v-else>
                                <i class="uil uil-eye-slash"></i>
                                Publier l’offre
                            </span>
                        </Button>

                            <!-- Bouton Clôture -->
                            <Button 
                                @click="() => updateOffres(data?.data[0]?.is_closed ? 0 : 1, data?.data[0]?.uuid, 'is_closed')"
                                type="button"
                                class="max-w-xs text-[12px] px-4 py-2 cursor-pointer font-medium rounded-lg  hover:shadow inline-flex items-center gap-2 mb-3"
                                :class="data?.data[0]?.is_closed ? 'bg-gray-100 text-gray-700' : 'bg-red-100 text-red-700'"
                            >
                                <span v-if="data?.data[0]?.is_closed">
                                    <i class="uil uil-check-circle"></i>
                                    Réouvrir l’offre
                                </span>
                                <span v-else>
                                    <i class="uil uil-times-circle"></i>
                                    Fermer l’offre
                                </span>
                            </Button>

                    </div>
                </div>
                <!-- <pre>  {{ data?.data[0] }}</pre> -->
                <h1 class="text-2xl font-bold text-gray-800 mb-4">
                Offre de Recrutement : {{ data?.data[0]?.job.position_title }}
                </h1>

                <!-- Informations générales -->
                <div class="grid grid-cols-2 gap-6 text-sm">
                <!-- <div>
                    <p class="text-gray-500">UUID</p>
                    <p class="font-medium">{{ uuid }}</p>
                </div> -->
                <div>
                    <p class="text-gray-500">Référence</p>
                    <p class="font-medium">{{ data?.data[0]?.reference }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Type</p>
                    <p class="font-medium capitalize">{{ data?.data[0]?.type }}</p>
                </div>
                <!-- <div>
                    <p class="text-gray-500">Recrutement ID</p>
                    <p class="font-medium">{{ recrutement_id }}</p>
                </div> -->
                <div>
                    <p class="text-gray-500">Publié ?</p>
                    <p class="font-medium">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold "
                    :class="data?.data[0]?.is_published ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                    >
                        {{ data?.data[0]?.is_published ? 'Oui' : 'Non' }}
                    </span>
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Clôturé ?</p>
                    <p class="font-medium">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold "
                    :class="data?.data[0]?.is_closed ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'">
                        {{ data?.data[0]?.is_closed ? 'Oui' : 'Non' }}
                    </span>
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Publié le</p>
                    <p class="font-medium">{{ data?.data[0]?.published_at }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Expire le</p>
                    <p class="font-medium">{{ data?.data[0]?.expires_at }}</p>
                </div>
                    <!-- <div>
                        <p class="text-gray-500">Créé le</p>
                        <p class="font-medium">{{ created_at }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Mis à jour le</p>
                        <p class="font-medium">{{ updated_at }}</p>
                    </div> -->
                </div>

               

                <!-- Job details -->
                <h2 class="text-xl font-semibold text-gray-800 mt-8 mb-4">Détails du poste</h2>
                <div class="grid grid-cols-2 gap-6 text-sm">
                <div>
                    <p class="text-gray-500">Titre du poste</p>
                    <p class="font-medium">{{ data?.data[0]?.job.position_title }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Pays d’affectation</p>
                    <p class="font-medium">{{ data?.data[0]?.job.country_duty_station }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Ville</p>
                    <p class="font-medium">{{ data?.data[0]?.job.city_duty_station ?? 'Non précisé' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Grade</p>
                    <p class="font-medium">{{ data?.data[0]?.job.grade ?? 'Non précisé' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Salaire</p>
                    <p class="font-medium">{{ data?.data[0]?.job.salary_post ?? 'Non précisé' }}</p>
                </div>
                </div>

                <!-- Bouton retour -->
                 <div class=" max-w-max mt-5">
                    <h2 class="text-lg font-semibold mb-4">Documents</h2>

                    <div v-if="dataValue.files.length > 0" class="space-y-2">
                    <div 
                        v-for="(file, index) in dataValue.files" 
                        :key="index" 
                        class="flex items-center justify-between p-3 border rounded hover:bg-gray-50"
                    >
                        <span class="truncate max-w-xs">{{ file.name }}</span>
                        <a 
                        :href="file.url" 
                        download
                        class="text-blue-600 hover:underline flex items-center space-x-1"
                        >
                        <i class="uil uil-export"></i>
                        <span>Télécharger </span>
                        </a>
                    </div>
                    </div>

                    <div v-else class="text-gray-500">Aucun document disponible.</div>
                </div>
            </div>
                
                <div class="w-full mt-6">
                    <!-- <pre>{{ data?.data[0] }}</pre> -->
                    <div class="flex px-6 py-4  justify-between items-center w-full">
                    <!-- <h4 class="font-bold">Gerer les offres</h4> -->
                    <h2 class="text-2xl font-semibold mb-4">Liste des candidats</h2>
                    <div class="flex justify-between items-center">
                        <button
                        @click="exporteData(columns,data?.data[0]?.candidat)"
                        type="button"
                        class="bg-primary  p-2 px-3 rounded-lg text-white cursor-pointer">
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
                <!-- <div class="mt-4 flex items-center justify-between p-3">
                    <div class="text-sm text-gray-600">
                        Affichage <span class="font-medium">1-3</span> sur <span class="font-medium">120</span>
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