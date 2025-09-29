
<script lang="ts" setup>
import AuthUser from '../components/AuthUser.vue';

import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
const { props:data } = usePage()

const props = defineProps({
  step: String,
  user: Object,
})


const tabs=[

    {
        label:'Gerer les offres',
        icon:'uil uil-briefcase-alt' ,
        path:'offres',
    },
    {
        label:'Gerer les candidatures',
        icon:'uil uil-user-check',
        path:'candidatures'
    },
   
    {
        label:'Gerer les candidats',
        icon:'uil uil-users-alt',
        path:'candidat'
    },
    {
        label:'Gerer les documents',
        icon:'uil uil-file-search-alt',
        path:'documents'
    },
    {
        label:'Parametres',
        icon:'uil uil-sliders-v',
        path:'parametres'
    },
    // {
    //     label:'Statistiques',
    //     icon:'uil uil-chart-bar',
    // }
]
</script>


<template>
  <div>
     <div class="w-full h-screen eoverflow-hidden flex flex-col bg-slate-100">
        <!-- {{ data }}dqwd -->
        <nav class="w-full flex  ">
                <div class="flex items-center">
                    <a 
                    :href="'/manager/'+value.path"
                    type="button" 
                    v-for="(value,index) in tabs"
                    :class="
                    [
                    step== value.path ? 'bg-white border-r border-l border-slate-300' : 'border-b hover:bg-slate-200',
                    index==0 ? ' border-l-0' : ''
                    ]
                    "
                    class=" p-3 cursor-pointer text-[13px] justify-center  rounded-tr-2xl flex items-center gap-1  border-slate-300 rounded-tl-2xl   min-w-44 font-medium  text-gray-600">
                    <span class="text-lg">
                        <i :class="value.icon"></i>
                    </span>
                    <span> {{ value.label }}</span>
                </a>
                </div>
                <div class="flex-1 flex items-center justify-end px-4 border-b border-slate-300">
                    <AuthUser :user="user" />
                </div>
        </nav>
        <!-- <pre>{{ data }}</pre> -->
        <slot/>
    </div>
  </div>
</template>


<style>

</style>