<script setup>
import { watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Toast from '@/Components/Toast.vue';
import { useToast } from '@/Composables/useToast';

const { toasts, remove, success, error, warning, info } = useToast();
const page = usePage();

// Обработка flash сообщений из сервера
watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            success(flash.success);
        }
        if (flash?.error) {
            error(flash.error);
        }
        if (flash?.warning) {
            warning(flash.warning);
        }
        if (flash?.info) {
            info(flash.info);
        }
    },
    { deep: true, immediate: true }
);
</script>

<template>
    <div
        class="fixed top-5 right-5 z-50 flex flex-col items-end space-y-2"
        aria-live="polite"
        aria-atomic="true"
    >
        <Toast
            v-for="toast in toasts"
            :key="toast.id"
            :id="toast.id"
            :type="toast.type"
            :message="toast.message"
            :duration="toast.duration"
            @close="remove"
        />
    </div>
</template>

