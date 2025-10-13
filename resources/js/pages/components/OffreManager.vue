
<script lang="ts" setup>
import { ref } from 'vue';
import { useApplyForm } from '../composables';
import AddOffre from './AddOffre.vue';
import Table from './Table.vue';
import UpdateOffre from './UpdateOffre.vue';

defineProps({
  data: Array,
})
const columns = [
  { key: "reference", label: "# Ref" },
  { key: "type", label: "Type" },
  { key: "position_title", label: "Position" },
  { key: "is_published", label: "Published" },
  { key: "is_closed", label: "Status" },
  { key: "published_at", label: "Published on" },
  { key: "expires_at", label: "Expires on" },
  { key: "candidates_count", label: "Number of candidates" },
];

const {exportToExcel}=useApplyForm()
const row = ref(null)


const handleClose =()=>{
    row.value = false
}
const Updated=(data)=>{
    row.value = data
    console.log(data);
}
</script>

<template>
  <div>
        <div class="w-full">
            <div class="flex px-6 py-4  justify-between items-center w-full">
                <!-- <pre>{{data.data }}</pre> -->
                <!-- <h4 class="font-bold">Gerer les offres</h4> -->
                <h2 class="text-2xl font-semibold mb-4">Manage Job Offers</h2>
                <div class="flex justify-between items-center">
                    <AddOffre />
                    <button type="button"
                    @click="exportToExcel(columns,data.data, 'Liste_des_offres.xlsx')"
                    class="bg-primary p-2 px-3 rounded-lg text-white cursor-pointer">
                        <i class="uil uil-export"></i>
                        <span>Export</span>
                    </button>
                </div>
            </div>
            <div class="w-full">
                <div class="overflow-x-auto border border-slate-200 bg-white ">
                    <Table :columns="columns" :rows="data.data">
                    <!-- Publication -->
                    <template #is_published="{ row }">
                        <span
                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                        :class="row.is_published === 'Publiée'
                            ? 'bg-green-100 text-green-800'
                            : 'bg-gray-100 text-gray-800'"
                        >
                        {{ row.is_published }}
                        </span>
                    </template>

                    <!-- Statut -->
                    <template #is_closed="{ row }">
                        <span
                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                        :class="row.is_closed === 'Ouverte'
                            ? 'bg-blue-100 text-blue-800'
                            : 'bg-red-100 text-red-800'"
                        >
                        {{ row.is_closed }}
                        </span>
                    </template>

                    <!-- Actions -->
                        <template #actions="{ row }">
                          <div class="flex items-center gap-2">
                            <button @click="Updated(row)" type="button" class="px-3 cursor-pointer py-1 text-sm border rounded-md hover:bg-gray-50">
                             Edit
                            </button>
                            <a :href="`/manager/offres/${row.uuid}`" class="px-3 py-1 text-sm border rounded-md hover:bg-gray-50">
                                View
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

                <UpdateOffre :row="row" @close="handleClose"/>
            </div>
        </div>
  </div>
</template>


<style>

</style>