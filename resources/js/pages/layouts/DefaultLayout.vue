
<script lang="ts" setup>
import AuthUser from '../components/AuthUser.vue';

import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Logo from '../components/logo.vue';
const { props:data } = usePage()

const props = defineProps({
  step: String,
  user: Object,
})

const tabs = [
    {
        label: 'Manage Job Offers',
        icon: 'uil uil-briefcase-alt',
        path: 'offres',
    },
    {
        label: 'Manage Applications',
        icon: 'uil uil-user-check',
        path: 'candidatures'
    },
    {
        label: 'Manage Candidates',
        icon: 'uil uil-users-alt',
        path: 'candidat'
    },
    {
        label: 'Manage Documents',
        icon: 'uil uil-file-search-alt',
        path: 'documents'
    },
    {
        label: 'Settings',
        icon: 'uil uil-sliders-v',
        path: 'parametres'
    },
    // {
    //     label: 'Statistics',
    //     icon: 'uil uil-chart-bar',
    // }
];

</script>


<template>
  <div>
     <div class="w-full h-screen">
        <div class="h-screen fixed w-[245px] border-r border-gray-100 bg-gray-50">
            <div class="flex items-center py-3 px-5">
                <a href="/">
                     <Logo/>
                </a>
            </div>
            <div class="flex flex-col p-4">
                    <a 
                    :href="'/manager/'+value.path"
                    type="button" 
                    v-for="(value,index) in tabs"
                    :class="
                    [
                    step== value.path ? 'bg-gray-200' : ' hover:bg-gray-200',
                    index==0 ? ' border-l-0' : ''
                    ]
                    "
                    class=" p-2 cursor-pointer text-[13px] mb-2  rounded-lg flex items-center gap-1  border-slate-300    min-w-44 font-medium  text-gray-600">
                    <span class="text-lg">
                        <i :class="value.icon"></i>
                    </span>
                    <span> {{ value.label }}</span>
                </a>
        </div>
        </div>
        <div class="w-[calc(100%-245px)] flex flex-col relative h-screen left-[245px] overflow-hidden">
            <nav class="w-full sticky top-0  z-10 bg-white flex p-2 border-b border-gray-100">
                    <div class="flex-1 flex items-center justify-end">
                        <AuthUser :user="user" />
                    </div>
            </nav>
            <div class="flex-1 overflow-x-auto p-6">
                <slot/>
            </div>
       </div>
    </div>
  </div>
</template>


<style>

</style>