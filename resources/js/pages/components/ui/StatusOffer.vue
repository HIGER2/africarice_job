

<script lang="ts" setup>
defineProps({
    status : String,
})
const getStatusClasses = (status) => {
  switch (status) {
    case 'pending': 
      return 'bg-gray-100 text-gray-700';
    case 'open': 
      return 'bg-green-100 text-green-700';
    case 'in_review': 
      return 'bg-yellow-100 text-yellow-700';
    case 'on_hold': 
      return 'bg-purple-100 text-purple-700';
    case 'closed': 
      return 'bg-red-100 text-red-700';
    case 'cancelled': 
      return 'bg-orange-200 text-orange-800';
    default: 
      return 'bg-gray-100 text-gray-700';
  }
};

</script>


<template>
  <div>
    <span class="px-2 py-1 rounded-md text-xs font-semibold tracking-wide"
      :class="getStatusClasses(status)">
  {{ status }}
</span>

  </div>
</template>

<style>

</style>