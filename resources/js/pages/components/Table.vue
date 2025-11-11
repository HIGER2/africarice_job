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
    <thead class="bg-white">
      <tr>
        <th
          v-for="col in columns"
          :key="col.key"
          scope="col"
          class="px-6 bg-white border-b text-nowrap border-gray-200 py-4 text-left text-[10px] font-bold text-primary uppercase tracking-wider"
        >
          {{ col.label }}
        </th>
          <!-- Colonne actions si slot existe -->
        <th
          v-if="slots.actions"
          scope="col"
          class="px-6 bg-white sticky right-0  border-b border-gray-200  py-4 text-right text-[11px] font-bold text-primary uppercase"
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
        class=" group cursor-pointer"
      >
        <!-- Cellule dynamique ou slot -->
        <td
          v-for="col in columns"
          :key="col.key"
          class="px-6 border-b group-hover:bg-gray-100 border-gray-200 py-3 whitespace-nowrap text-[12px] text-gray-700"
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
          class="px-6 sticky border-b border-gray-200 right-0 group-hover:bg-gray-100 bg-white  py-3 whitespace-nowrap text-right text-sm"
        >
          <slot name="actions" :row="row" />
        </td>
      </tr>
    </tbody>
  </table>
 </div>
</template>
