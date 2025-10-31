

<script lang="ts" setup>
defineProps({
    status : String,
})

const getStatusClasses = (status) => {
  switch (status) {
    case 'pending': 
      return 'bg-gray-100 text-gray-700';
    case 'published': 
      return 'bg-blue-100 text-blue-700';
    case 'in_review': 
      return 'bg-yellow-100 text-yellow-700';
    case 'shortlisted': 
      return 'bg-indigo-100 text-indigo-700';
    case 'interview_scheduled': 
      return 'bg-purple-100 text-purple-700';
    case 'interview_completed': 
      return 'bg-teal-100 text-teal-700';
    case 'offer_sent': 
      return 'bg-orange-100 text-orange-700';
    case 'offer_accepted': 
      return 'bg-green-100 text-green-700';
    case 'closed': 
      return 'bg-red-100 text-red-700';
    case 'archived': 
      return 'bg-gray-200 text-gray-800';
    default: 
      return 'bg-gray-100 text-gray-700';
  }
};


    const statusList = [
  { label: 'Pending', value: 'pending' },
  { label: 'Published', value: 'published' },
  { label: 'In Review', value: 'in_review' },
  { label: 'Shortlisted', value: 'shortlisted' },
  { label: 'Interview Scheduled', value: 'interview_scheduled' },
  { label: 'Interview Completed', value: 'interview_completed' },
  { label: 'Offer Sent', value: 'offer_sent' },
  { label: 'Offer Accepted', value: 'offer_accepted' },
  { label: 'Closed', value: 'closed' },
  { label: 'Archived', value: 'archived' },
];

const getStatusLabel = (status) => {
  const item = statusList.find((s) => s.value === status);
  return item ? item.label : status;
};

</script>


<template>
  <div>
    <span
        class="px-2 py-1 rounded-md text-xs font-semibold"
        :class="getStatusClasses(status)"
    >
        {{ getStatusLabel(status) }}
    </span>
  </div>
</template>

<style>

</style>