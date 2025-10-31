
<script lang="ts" setup>
import { computed } from 'vue';
import AddOffre from './AddOffre.vue';
import Table from './Table.vue';
import { useApplyForm } from '../composables';
import ButtonBack from './ui/ButtonBack.vue';
import JSZip from 'jszip';
import { reactive } from 'vue';
import AddOffreTracking from './AddOffreTracking.vue';
import StatusOngoing from './ui/StatusOngoing.vue';

const props= defineProps({
  data: Array,
})
const {exportToExcel,updateOffres,downloadZip}=useApplyForm()

const labelsMap = {
    id: "#",
    status: "Status",
    comment: "Comment",
    date: "Date",
}


const dataValue = computed(()=>props.data.data[0])

const labelsExclu =['uuid']
const exportCv = reactive({
    loading: false,
    label:'Export CV'
})

  const columns = computed(() => {
//    if (!props.data?.data || props.data.data.length === 0) return [];
//       const firstRowKeys = Object.keys(props.data.data[0]?.candidat[0] || {}).filter(key => !labelsExclu.includes(key));

        return Object.keys(labelsMap).map((key) => ({
        key: key,
        label: labelsMap[key] || key, // si pas trouvé dans labelsMap → utilise la clé
        }));

    });
    
    const exporteData= (columns,data)=>{
      delete columns[columns.findIndex(col => col.key === 'documents')];
      exportToExcel(columns,data, 'Liste_des_candidatures.xlsx')
    }

    const exporteCvs= async(data)=>{
        let files=[]
        data.forEach(candidat => {
            console.log(candidat.candidat);
            
        const firstDoc = candidat.documents[0]; // index 0
        if (firstDoc) {
          files.push(firstDoc)
        }
        });
        if (files.length > 0) {
            exportCv.loading = true
            exportCv.label = 'Chargement...'
            await downloadZip(files)
            exportCv.loading = false
            exportCv.label = 'Export CV'

        }else{
            alert('Aucun document trouvé')
        }
    }


</script>

<template>
  <div>
     <div class="w-full">
            <div class="w-full">
                <!-- {{ columns }} -->
            <!-- <pre>  {{ data?.data[0].candidat }}</pre> -->

                <!-- <pre>{{ data?.data[0] }}</pre> -->
                <div class="flex   justify-between items-center w-full">
                <!-- <h4 class="font-bold">Gerer les offres</h4> -->
                 <!-- {{ data?.data?.trakings }} -->
                <h2 class="text-xl font-semibold mb-4">Job Application Tracking History</h2>
                <div class="flex gap-2 justify-between items-center">
                    <AddOffreTracking :data="data?.data"/>
                        <!-- <button
                            type="button"
                            class="bg-primary p-2 px-3 rounded-lg text-white cursor-pointer hover:bg-primary-700 transition">
                            <span>Add Tracking</span>
                        </button> -->

                        <!-- <button
                            :disabled="exportCv.loading"
                            @click="exporteCvs(data?.data[0]?.candidat)"
                            type="button"
                            class="bg-gray-50 border border-gray-200 disabled:opacity-50 p-2 px-3 rounded-lg text-primary cursor-pointer hover:bg-gray-100 transition">
                            <i class="uil uil-export"></i>
                            <span>{{ exportCv.label }}</span>
                        </button> -->
                    </div>

                </div>
                <div class="w-full ">
                    <Table :columns="columns" :rows="data?.data?.trakings">
                        <template #status="{ row }">
                              <StatusOngoing :status="row.status" />
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