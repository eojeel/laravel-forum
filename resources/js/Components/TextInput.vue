<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel();

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm"
        v-model="model"
    >
</template>
