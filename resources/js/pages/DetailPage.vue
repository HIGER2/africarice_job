
<script lang="ts" setup>
import CandidatManagerDetail from './components/CandidatManagerDetail.vue';
import OfferTracker from './components/OfferTracker.vue';
import OffreManagerDetail from './components/OffreManagerDetail.vue';
import DefaultLayout from './layouts/DefaultLayout.vue';

const props = defineProps({
  step: String,
  data: Object,
  user: Object,
})


const components={
    offres:OffreManagerDetail,
    // candidatures:CandidatureManager,
    candidat:CandidatManagerDetail,
    // documents: DoucumentManager,
    offerTracker:OfferTracker
}

</script>

<template>
  <div>
    <DefaultLayout :step="step" :user="user">
        <div class="flex-1 overflow-auto py-5  bg-white">
        <!-- <pre>{{ step }}</pre> -->
        <!-- <pre>{{ data }}</pre> -->
        <component :is="components[step]" :data="data"/>
    </div>
    </DefaultLayout>
  </div>
</template>


<style>

</style>