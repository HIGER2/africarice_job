
<script lang="ts" setup>
import { Inertia } from '@inertiajs/inertia';


const props= defineProps({
    path: {
        type: String,
        default: '/',
    },
    label:{
        type: String,
        default: 'Retour à la liste',
    }
})
const onBack=()=>{
      Inertia.visit(props.path);
}

</script>

<template>
   <div class="my-4">
        <button type="button"
        @click="onBack"
            class="px-4 text-[13px]  cursor-pointer font-medium py-2 bg-gray-100 text-primary rounded-xl hover:shadow hover:bg-gray-200 inline-flex items-center gap-2 mb-3">
            ← {{label}}
        </button>
    </div>
</template>


<style>

</style>