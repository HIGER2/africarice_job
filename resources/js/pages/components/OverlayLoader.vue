

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: { type: Boolean, default: false },
  text: { type: String, default: 'Chargement…' }
})

const emit = defineEmits(['update:modelValue'])
const visible = ref(props.modelValue)

watch(() => props.modelValue, v => {
  visible.value = v
  document.documentElement.style.overflow = v ? 'hidden' : ''
})

function close() {
  emit('update:modelValue', false)
}
</script>


<template>
  <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 ">
    <div class="flex flex-col items-center gap-4">
         <div class="w-16 h-16 border-4 border-white/30 border-t-white rounded-full animate-spin"></div>
        <p role="status" aria-live="polite" class="text-white text-sm">{{ text }}</p>
    </div>
  </div>
</template>
