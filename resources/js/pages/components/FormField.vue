<template>
  <div
    :class="[
      'flex gap-1 w-full cursor-pointer mb-2',
      field.type === 'checkbox'
        ? 'flex-row items-center'
        : 'flex-col items-start justify-end'
    ]"
  >
    <label
      :for="field.key"
      class="block text-[13px] cursor-pointer text-primary font-medium mb-1"
    >
      {{ field.label }}
    </label>

    <!-- Input classique -->
    <input
      v-if="field.type !== 'select' && field.type !== 'file' && field.type !== 'textarea'"
      :id="field.key"
      :type="field.type"
      :placeholder="field.label"
      v-model="localValue"
      :class="field.type === 'checkbox' ? '' : 'w-full'"
      class="border border-gray-300 p-2 rounded placeholder:text-[11px] placeholder:font-medium"
    />

    <!-- Textarea -->
    <textarea
      v-else-if="field.type === 'textarea'"
      :id="field.key"
      :placeholder="field.label"
      v-model="localValue"
      class="w-full border border-gray-300 p-2 rounded placeholder:text-[11px] placeholder:font-medium"
    />

    <!-- Select -->
    <select
      v-else-if="field.type === 'select'"
      :id="field.key"
      v-model="localValue"
      class="w-full border border-gray-300 p-2 rounded text-sm"
    >
      <option value="">Sélectionnez</option>
      <option
        v-for="(opt, i) in field.options || []"
        :key="i"
        :value="opt.value"
      >
        {{ opt.label }}
      </option>
    </select>

    <!-- Upload fichier -->
    <input
      v-else-if="field.type === 'file'"
      :id="field.key"
      type="file"
      class="hidden"
      @change="onFileChange"
    />
    <label
      v-if="field.type === 'file'"
      :for="field.key"
      class="w-full border border-dashed p-3 rounded text-center text-sm text-gray-600 cursor-pointer hover:bg-gray-50"
    >
      {{ localValue ? `Fichier : ${localValue.name}` : 'Cliquez pour choisir un fichier' }}
    </label>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  field: { type: Object, required: true },
  modelValue: { type: [String, Boolean, Object, Number], default: '' }
})

const emit = defineEmits(['update:modelValue'])

const localValue = ref(props.modelValue)

// synchro parent → enfant
watch(
  () => props.modelValue,
  (val) => {
    localValue.value = val
  }
)

// synchro enfant → parent
watch(localValue, (val) => {
  emit('update:modelValue', val)
})

const onFileChange = (e) => {
  const file = e.target.files[0]
  if (file) localValue.value = file
}
</script>
