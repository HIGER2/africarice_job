

<script lang="ts" setup>
import { ref } from 'vue';
import CandidatureManager from './components/CandidatureManager.vue';
import OffreManager from './components/OffreManager.vue';
import CandidatManager from './components/CandidatManager.vue';
import DoucumentManager from './components/DoucumentManager.vue';
import DefaultLayout from './layouts/DefaultLayout.vue';

const props = defineProps({
  step: String,
  data: Object,
  user: Object,
})


const isActive= ref(0)

const setActive=(state:any)=>{
    isActive.value = state
}

const components={
    offres:OffreManager,
    candidatures:CandidatureManager,
    candidat:CandidatManager,
    documents: DoucumentManager,
}

</script>

<template>
  <div>
    <DefaultLayout :step="step" :user="user">
        <div class="flex-1 overflow-auto py-5  bg-white">
            <!-- <pre>{{ data }}</pre> -->
            <component :is="components[step]" :data="data"/>
        </div>
       
    </DefaultLayout>
  
  </div>
</template>


<style>

</style>