<template>
  <div class="mb-3 w-full"
    :class="field.type =='checkbox' ? 'flex items-center gap-1': ''"
  >
    <label :for="field.key" class="font-medium mb-3">{{ field.label }}</label>
    <input
    v-bind="$attrs"
      v-if="field.type !== 'select' && field.type !== 'file' && field.type !== 'textarea' && field.type !== 'checkbox'"
      :type="field.type"
      :id="field.key"
      :value="modelValue"
      :placeholder="field.label"
      @input="$emit('update:modelValue', $event.target.value)"
      class="border border-gray-300 p-3 rounded  text-[13px] text-gray-700  disabled:opacity-50 disabled:bg-gray-200 placeholder:text-[12px]"
      :class="field.type =='checkbox' ? '': 'w-full'"
    />
    <textarea
    v-bind="$attrs"
      v-else-if="field.type === 'textarea'"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      class="border border-gray-300 p-3  rounded w-full  text-[13px] text-gray-700 disabled:opacity-50 disabled:bg-gray-200"
    />
    <select
    v-bind="$attrs"
      v-else-if="field.type === 'select'"
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
      class="border border-gray-300 p-3  rounded w-full  text-[13px] text-gray-700 disabled:opacity-50 disabled:bg-gray-200"
    >
      <option value="" disabled>Sélectionnez</option>
      <option v-for="opt in field.options || []" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
    </select>
    <input
    v-bind="$attrs"
      v-else-if="field.type === 'file'"
      type="file"
      :id="field.key"
      @change="onFileChange"
      class="hidden"
    />
    <input
    v-bind="$attrs"
      v-else-if="field.type === 'checkbox'"
      type="checkbox"
      :id="field.key"
      :value="modelValue"
      :checked="modelValue"
     @change="$emit('update:modelValue', $event.target.value)"
    />
    <!-- <label v-if="field.type === 'file'" :for="field.key">
      {{ modelValue ? modelValue.name : 'Choisir un fichier' }}
    </label> -->
  </div>
</template>

<script setup>
const props = defineProps({
  field: Object,
  modelValue: [String, Object, Number, Boolean],
})
const emit = defineEmits(['update:modelValue','change'])

const onFileChange = (e) => {
  let file = e.target.files[0]
  if (file) {
    emit('update:modelValue', file)
    emit('change',file)
    file=null
  }
}
</script>
