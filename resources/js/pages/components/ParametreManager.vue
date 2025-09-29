
<script setup>
import { ref } from "vue";
import { useApplyForm } from "../composables";
// import { usePage } from "@inertiajs/vue3";

// const page = usePage();

defineProps({
    data: Object
})
const emails = ref([]);
const {newEmail,AddEmail,AddUser,newUser}=useApplyForm()
const newAdmin = ref({
  firstName: "",
  lastName: "",
  email: "",
  phone: "",
});
const admins = ref([]);

// const addEmail = () => {
//   if (newEmail.value) {
//     emails.value.push({
//       email: newEmail.value,
//       isPrimary: isPrimary.value,
//     });
//     newEmail.value = "";
//     isPrimary.value = "no";
//   }
// };

const addAdmin = () => {
  // console.log($page.props);
  AddUser()
};
</script>

<template>
  <div class="w-full grid grid-cols-2 mx-auto p-6 divide-x divide-gray-200 gap-6">
    <!-- Section Emails -->
    <div class="pr-6">
      <h2 class="text-xl font-bold mb-4">Gestion des emails</h2>
        <!-- {{ data.emails }} -->
    <form action="" @submit.prevent="AddEmail">
      <div class="flex flex-wrap gap-2 mb-4">
        <input
          v-model="newEmail.email"
          type="email"
          required
          placeholder="Entrer un email"
          class=" rounded-lg p-2 flex-1 min-w-[200px] border border-gray-400"
        />
        <select v-model="newEmail.is_primary" class="border rounded-lg p-2 border-gray-400">
          <option :value="false">Secondaire</option>
          <option :value="true">Principal</option>
        </select>
        <button
          class="bg-primary rounded-lg text-white px-4 py-2 "
        >
          Ajouter
        </button>
      </div>
    </form>
      <ul class="space-y-2">
        <li
          v-for="({email,is_primary}, index) in data.emails"
          :key="index"
          class="flex justify-between items-center bg-white p-2 rounded shadow-sm"
        >
          <span>{{ email }}</span>
          <span
            class="px-2 py-1 text-xs rounded-lg"
            :class="is_primary ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500'"
          >
            {{ is_primary  ? 'Principal' : 'Secondaire' }}
          </span>
        </li>
      </ul>
    </div>

    <!-- Section Admins -->
    <div class="pl-6">
      <h2 class="text-xl font-bold mb-4">Gestion des administrateurs</h2>

    <form @submit.prevent="addAdmin">
      <div class="flex flex-wrap gap-2 mb-4">
        <input
          v-model="newUser.name"
          type="text"
          placeholder="Prénom"
          required
          class="border  p-2 border-gray-400 rounded-lg flex-1 min-w-[120px]"
        />
        <input
          v-model="newUser.last_name"
          type="text"
          placeholder="Nom"
          required
          class="border border-gray-400 rounded-lg p-2 flex-1 min-w-[120px]"
        />
        <input
          v-model="newUser.email"
          type="email"
          placeholder="Email"
          required
          class="border border-gray-400 rounded-lg p-2 flex-1 min-w-[180px]"
        />
        <!-- <input
          v-model="newAdmin.phone"
          type="tel"
          placeholder="Téléphone"
          class="border rounded p-2 flex-1 min-w-[140px]"
        /> -->
        <button
        type="submit"
          class="bg-primary  rounded-lg cursor-pointer text-white px-4 py-2"
        >
          Ajouter
        </button>
      </div>
    </form>

      <!-- Liste -->
      <ul class="space-y-2">
        <li
          v-for="({email,last_name,name}, index) in data.admins"
          :key="index"
          class="flex justify-between items-center bg-white p-2 rounded shadow-sm"
        >
          <span class="font-semibold">{{ name }} {{ last_name }}</span>
          <span>{{ email }}</span>
          <!-- <span>{{ phone }}</span> -->
        </li>
      </ul>
    </div>
  </div>
</template>

