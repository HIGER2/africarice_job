
<script lang="ts" setup>
import { onMounted, ref } from 'vue';
import { useApplyForm } from '../composables';
import AddOffre from './AddOffre.vue';
import Table from './Table.vue';
import UpdateOffre from './UpdateOffre.vue';
import StatusOngoing from './ui/StatusOngoing.vue';
import StatusOffer from './ui/StatusOffer.vue';
import Spinnercomponent from './Spinnercomponent.vue';
import { Inertia } from '@inertiajs/inertia';
import { router } from '@inertiajs/vue3'
import { onBeforeUnmount } from 'vue';


const props = defineProps({
  data: Array,
})
const columns = [
  { key: "reference", label: "# Ref" },
  { key: "assign_by", label: "Recruiter" },
  { key: "type", label: "Type" },
  { key: "manager", label: "Manager" },
  { key: "center", label: "Recruitment Entity" },
  { key: "position_title", label: "Position" },
  { key: "country_duty_station", label: "Country duty station" },
  { key: "city_duty_station", label: "City duty station" },
  { key: "division", label: "Division" },
  { key: "grade", label: "Grade" },
  { key: "program", label: "Program" },
  { key: "status", label: "Status" },
  { key: "ongoing", label: "Ongoing status" },
  { key: "comment", label: "Comment tacking" },
  { key: "date_tracking", label: "Date tracking" },
  { key: "reason", label: "Reason" },
  { key: "reason_replacement", label: "Replacement fullname" },
  { key: "published_at", label: "Published on" },
  { key: "expires_at", label: "Expires on" },
  { key: "candidates_count", label: "Number of candidates" },
];

const {exportToExcel}=useApplyForm()
const row = ref(null)
const loading= ref(false)
const isIndex= ref(null)

const search = ref(props.search || null)
let timer = null

const handleClose =()=>{
    row.value = false
}
const setRowData = (data) => {
  row.value = {
    uuid: data.uuid ?? null,
    reference: data.reference ?? null,
    position_title: data.job?.position_title ?? '',
    country_duty_station: data.job.country_duty_station ?? 'france',
    published_at: data.published_at ?? '',
    type: data.type ?? 'public',
    expires_at: data.expires_at ?? '',
    status: data.status ?? '',
    assign_by: data.job.assign_by ?? '',
    reason: data.job.reason ?? '',
    manager: data.job.manager ?? '',
    center: data.job.center ?? '',
    grade: data.job.grade ?? '',
    program: data.job.program ?? '',
    division: data.job.division ?? '',
    city_duty_station: data.job.city_duty_station ?? '',
    reason_replacement: data.job.reason_replacement ?? ''
  }
}



function fetchData() {
   // Annuler le précédent timer
        if (timer) clearTimeout(timer)
        // Lancer un nouveau timer
        timer = setTimeout(() => {
            Inertia.get(
            '/manager/offres',
            { search: search.value },
            { preserveState: true, replace: true }
            )
        }, 300) 
}


const Updated=async(data)=>{

    try {
        isIndex.value = data?.uuid
        loading.value =true
        const response = await axios.get(`/manager/offre/detail/${data?.uuid}`);
        setRowData(response.data.data)
        loading.value = false
        // Inertia.reload();
    } catch (error) {
            let message = error.response?.data?.message ? error.response?.data?.message:'Erreur lors de l\'envoi du formulaire ❌'
            if (error.response && error.response.data && error.response.data.errors) {
            // Récupère tous les messages d'erreur et les transforme en texte
                    message = Object.values(error.response.data.errors)
                .flat() // aplatit les tableaux
                .join('\n');
            alert(message);
            } else {
            alert(message);
            }
    } finally {
        console.log("Requête terminée");
    }
}

onBeforeUnmount(() => {
  // Quand vous quittez la page
  router.reload()
})

// const getDetails=(uuid)=>{

// }
</script>

<template>
  <div>
        <div class="w-full min-h-screen flex flex-col">
            <div class="flex sticky left-0 right-0  justify-between mb-6 items-center w-full">
                <h2 class="text-2xl font-semibold">Manage Job Offers</h2>
                <div class="flex gap-2 justify-between items-center">
                    <input 
                    v-model="search"
                    @input="fetchData"
                    type="search" placeholder="search..." class="border-[0.1rem] border-gray-200 p-2 px-3 rounded-lg text-primary cursor-pointer">
                    <button type="button"
                    @click="exportToExcel(columns,data.offer, 'Liste_des_offres.xlsx')"
                    class="border-[0.1rem] border-gray-200 p-2 px-3 rounded-lg text-primary cursor-pointer">
                        <i class="uil uil-export"></i>
                        <span>Export</span>
                    </button>
                    <AddOffre :data="data" />
                </div>
            
            </div>
            
            <div class=" pb-10 overflow-x-auto">
                <div class="w-ful">
                    <Table :columns="columns" :rows="data?.offer">
                    <!-- Publication -->
                    
                    <template #ongoing="{ row }">
                        <StatusOngoing :status="row.ongoing" />
                    </template>
                    <!-- Statut -->
                    <template #status="{ row }">
                    <StatusOffer  :status="row?.status" />
                    </template>

                    <!-- Actions -->
                        <template #actions="{ row }">
                          <div class="flex items-center gap-2">
                            <button @click="Updated(row)" type="button" class="px-3 cursor-pointer py-1 text-sm border rounded-md hover:bg-gray-50">
                             <Spinnercomponent v-if="loading && isIndex ==row.uuid " />
                             <span v-else>Edit</span>

                            </button>
                            <a :href="`/manager/offres/${row.uuid}`" class="px-3 py-1 text-sm border rounded-md hover:bg-gray-50">
                                View
                            </a>
                             <a :href="`/manager/offerTracker/${row.uuid}`" class="px-3 py-1 text-sm border rounded-md hover:bg-gray-50">
                                Tracking
                            </a>
                          </div>
                        </template>
                    </Table>
                </div>
                <!-- Pagination (optionnel) -->
                <!-- <div class="mt-4 flex items-center justify-between p-3">
                    <div class="text-sm text-gray-600">Affichage <span class="font-medium">1-3</span> sur <span class="font-medium">120</span></div>
                        <div class="inline-flex items-center space-x-2">
                            <button class="px-3 py-1 border rounded-md text-sm">Préc</button>
                            <button class="px-3 py-1 border rounded-md text-sm">Suiv</button>
                        </div>
                </div> -->
                <UpdateOffre :row="row" :assign="data.assign" @close="handleClose"/>
            </div>
        </div>
  </div>
</template>


<style>

</style>