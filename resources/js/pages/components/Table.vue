<script setup lang="ts">
import { defineProps, useSlots } from "vue";

const props = defineProps<{
  columns: { key: string; label: string }[];
  rows: any[];
}>();

const slots = useSlots();
</script>

<template>
 <div class="w-full   rounded-md">
    <table class="w-full border-collapse ">
    <!-- En-tête -->
    <thead class=" bg-gray-50">
      <tr>
        <th
          v-for="col in columns"
          :key="col.key"
          scope="col"
          class="px-6 border-b text-nowrap border-gray-300 py-4 text-left text-[10px] font-bold text-primary uppercase tracking-wider"
        >
          {{ col.label }}
        </th>

        <!-- Colonne actions si slot existe -->
        <th
          v-if="slots.actions"
          scope="col"
          class="px-6 sticky right-0  border-b border-gray-300 bg-gray-50 py-4 text-right text-[11px] font-bold text-primary uppercase"
        >
          Actions
        </th>
      </tr>
    </thead>

    <!-- Corps -->
    <tbody class="bg-white divide-y divide-gray-100">
      <tr
        v-for="(row, i) in rows"
        :key="i"
        class="hover:bg-gray-50 cursor-pointer"
      >
        <!-- Cellule dynamique ou slot -->
        <td
          v-for="col in columns"
          :key="col.key"
          class="px-6 border-b border-gray-300 py-4 whitespace-nowrap text-sm text-gray-700"
        >
          <!-- Vérifie si un slot existe pour cette colonne -->
          <slot
            :name="col.key"
            :row="row"
          >
            <!-- Sinon valeur par défaut -->
            {{ row[col.key] }}
          </slot>
        </td>
        <!-- Slot Actions -->
        <td
          v-if="slots.actions"
          class="px-6 sticky right-0 bg-white  py-4 whitespace-nowrap text-right text-sm"
        >
          <slot name="actions" :row="row" />
        </td>
      </tr>
    </tbody>
  </table>
 </div>
</template>
