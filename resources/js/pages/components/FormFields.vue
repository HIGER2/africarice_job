<template>
  <div class="w-full mb-3">
    <label class="text-sm font-medium text-gray-700 mb-1">
    {{ field.label }}
    </label>

    <div class="flex">
      <!-- Country code -->
      <select
        v-model="local.country_code"
        @change="emitValue"
        class="p-3 max-w-max border border-gray-300 rounded-l disabled:opacity-50 disabled:bg-gray-200"
      >
        <option
          v-for="country in field.options"
          :key="country.value"
          :value="country.value"
        >
          {{ country.label }}
        </option>
      </select>

      <!-- Phone -->
      <input
        v-model="local.phone"
        @input="emitValue"
        type="tel"
        placeholder="Ex: 0700000000"
        class="p-3 w-full border border-gray-300  rounded-r disabled:opacity-50 disabled:bg-gray-200"
      />
    </div>
  </div>
</template>
<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  field: Object,
  modelValue: {
    type: Object,
    default: () => ({
      phone: '',
      country_code: '+225'
    })
  },
  countryCode: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['update:modelValue'])

// ✅ Toujours sécurisé même si modelValue est undefined
const local = reactive({
  phone: props.modelValue?.phone ?? '',
  country_code: props.modelValue?.country_code ?? '+225'
})

// ✅ Émettre au parent
const emitValue = () => {
  emit('update:modelValue', {
    ...props.modelValue,           // si modelValue est undefined, ça ne crash plus
    phone: local.phone,
    country_code: local.country_code
  })
}

// ✅ Sync si le parent change (sécurisé aussi)
watch(
  () => props.modelValue,
  (val) => {
    local.phone = val?.phone ?? ''
    local.country_code = val?.country_code ?? '+225'
  },
  { deep: true }
)
</script>
