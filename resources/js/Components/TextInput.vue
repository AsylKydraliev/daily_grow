<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

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
        class="border-orange-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm"
        v-model="model"
        ref="input"
    />
</template>

<style scoped>
input[type="text"],
input[type="password"],
input[type="email"],
input[type="number"] {
    background-color: white !important;
}
</style>
