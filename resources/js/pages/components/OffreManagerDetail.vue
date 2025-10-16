
<script lang="ts" setup>
import { computed } from 'vue';
import AddOffre from './AddOffre.vue';
import Table from './Table.vue';
import { useApplyForm } from '../composables';
import ButtonBack from './ui/ButtonBack.vue';
import JSZip from 'jszip';
import { reactive } from 'vue';

// import { usePage } from '@inertiajs/vue3'


// const page = usePage()
// const flash = page.props.flash

const props= defineProps({
  data: Array,
})
const {exportToExcel,updateOffres,downloadZip}=useApplyForm()

const labelsMap = {
    candidat: "Candidate",
    user_email: "Email",
    user_phone: "Phone",
    origin_nationality: "Nationality",
    second_nationality: "nationality_2",
    origin_country: "Country",
    origin_city: "City",
    origin_experience_years: "Experience",
    origin_french_level: "French Level",
    origin_english_level: "English Level",
}


const dataValue = computed(()=>props.data.data[0])

const labelsExclu =['uuid']
const exportCv = reactive({
    loading: false,
    label:'Export CV'
})

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
       
        <!-- <div v-if="$page.props.flash.message" class="alert">
            {{ $page.props.flash.message }}
        </div> -->

                <div class="w-full mx-auto bg-white rounded-2xl px-6">
    <div class="flex justify-between items-center py-4">
        <ButtonBack path="/manager/offres" />
        <div class="flex items-center gap-2">
            <!-- Publish Button -->
            <Button 
                @click="() => updateOffres(data?.data[0]?.is_published ? 0 : 1, data?.data[0]?.uuid, 'is_published')"
                type="button"
                class="max-w-xs text-[12px] px-4 py-2 cursor-pointer font-medium rounded-lg hover:shadow inline-flex items-center gap-2 mb-3"
                :class="data?.data[0]?.is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'"
            >
                <span v-if="data?.data[0]?.is_published">
                    <i class="uil uil-eye"></i>
                    Unpublish Offer
                </span>
                <span v-else>
                    <i class="uil uil-eye-slash"></i>
                    Publish Offer
                </span>
            </Button>

            <!-- Close Button -->
            <Button 
                @click="() => updateOffres(data?.data[0]?.is_closed ? 0 : 1, data?.data[0]?.uuid, 'is_closed')"
                type="button"
                class="max-w-xs text-[12px] px-4 py-2 cursor-pointer font-medium rounded-lg hover:shadow inline-flex items-center gap-2 mb-3"
                :class="data?.data[0]?.is_closed ? 'bg-gray-100 text-gray-700' : 'bg-red-100 text-red-700'"
            >
                <span v-if="data?.data[0]?.is_closed">
                    <i class="uil uil-check-circle"></i>
                    Reopen Offer
                </span>
                <span v-else>
                    <i class="uil uil-times-circle"></i>
                    Close Offer
                </span>
            </Button>
        </div>
    </div>

    <h1 class="text-2xl font-bold text-gray-800 mb-4">
        Recruitment Offer: {{ data?.data[0]?.job.position_title }}
    </h1>

    <!-- General Information -->
    <div class="grid grid-cols-2 gap-6 text-sm">
        <div>
            <p class="text-gray-500">Reference</p>
            <p class="font-medium">{{ data?.data[0]?.reference }}</p>
        </div>
        <div>
            <p class="text-gray-500">Type</p>
            <p class="font-medium capitalize">{{ data?.data[0]?.type }}</p>
        </div>
        <div>
            <p class="text-gray-500">Published?</p>
            <p class="font-medium">
                <span class="px-2 py-1 rounded-full text-xs font-semibold"
                      :class="data?.data[0]?.is_published ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                    {{ data?.data[0]?.is_published ? 'Yes' : 'No' }}
                </span>
            </p>
        </div>
        <div>
            <p class="text-gray-500">Closed?</p>
            <p class="font-medium">
                <span class="px-2 py-1 rounded-full text-xs font-semibold"
                      :class="data?.data[0]?.is_closed ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'">
                    {{ data?.data[0]?.is_closed ? 'Yes' : 'No' }}
                </span>
            </p>
        </div>
        <div>
            <p class="text-gray-500">Published on</p>
            <p class="font-medium">{{ data?.data[0]?.published_at }}</p>
        </div>
        <div>
            <p class="text-gray-500">Expires on</p>
            <p class="font-medium">{{ data?.data[0]?.expires_at }}</p>
        </div>
    </div>

    <!-- Job Details -->
    <h2 class="text-xl font-semibold text-gray-800 mt-8 mb-4">Job Details</h2>
    <div class="grid grid-cols-2 gap-6 text-sm">
        <div>
            <p class="text-gray-500">Position Title</p>
            <p class="font-medium">{{ data?.data[0]?.job.position_title }}</p>
        </div>
        <div>
            <p class="text-gray-500">Duty Country</p>
            <p class="font-medium">{{ data?.data[0]?.job.country_duty_station }}</p>
        </div>
        <div>
            <p class="text-gray-500">City</p>
            <p class="font-medium">{{ data?.data[0]?.job.city_duty_station ?? 'Not specified' }}</p>
        </div>
        <div>
            <p class="text-gray-500">Grade</p>
            <p class="font-medium">{{ data?.data[0]?.job.grade ?? 'Not specified' }}</p>
        </div>
        <div>
            <p class="text-gray-500">Salary</p>
            <p class="font-medium">{{ data?.data[0]?.job.salary_post ?? 'Not specified' }}</p>
        </div>
    </div>

    <!-- Documents -->
    <div class="max-w-max mt-5">
        <h2 class="text-lg font-semibold mb-4">Documents</h2>
        <div v-if="data.data[0].files.length > 0" class="space-y-2">
            <div v-for="(file, index) in data.data[0].files" :key="index" class="flex items-center justify-between p-3 border rounded hover:bg-gray-50">
                <span class="truncate max-w-xs">{{ file.name }}</span>
                <a :href="`/${file.url}`" download class="text-blue-600 hover:underline flex items-center space-x-1">
                    <i class="uil uil-export"></i>
                    <span>Download</span>
                </a>
            </div>
        </div>
        <div v-else class="text-gray-500">No documents available.</div>
    </div>
</div>

                <div class="w-full mt-6">
                <!-- <pre>  {{ data?.data[0].candidat }}</pre> -->

                    <!-- <pre>{{ data?.data[0] }}</pre> -->
                    <div class="flex px-6 py-4  justify-between items-center w-full">
                    <!-- <h4 class="font-bold">Gerer les offres</h4> -->
                    <h2 class="text-2xl font-semibold mb-4">List of candidates</h2>
                    <div class="flex gap-2 justify-between items-center">
                            <button
                                @click="exporteData(columns, data?.data[0]?.candidat)"
                                type="button"
                                class="bg-primary p-2 px-3 rounded-lg text-white cursor-pointer hover:bg-primary-700 transition">
                                <i class="uil uil-export"></i>
                                <span>Export List</span>
                            </button>

                            <button
                                :disabled="exportCv.loading"
                                @click="exporteCvs(data?.data[0]?.candidat)"
                                type="button"
                                class="bg-gray-50 border border-gray-200 disabled:opacity-50 p-2 px-3 rounded-lg text-primary cursor-pointer hover:bg-gray-100 transition">
                                <i class="uil uil-export"></i>
                                <span>{{ exportCv.label }}</span>
                            </button>
                        </div>

                </div>
                <div class="overflow-x-auto border border-slate-200 bg-white ">
                    <Table :columns="columns" :rows="data?.data[0]?.candidat">
                           <template #documents="{ row }">
                                <div class="flex flex-col gap-2">
                                    <a
                                    v-for="(value,index) in row.documents"
                                    :href="`${value.path}`"
                                    :download="value.name"
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