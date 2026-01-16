<script setup>
import { computed, onMounted, ref } from 'vue';
import XMarkIcon from '@/Components/Icons/XMarkIcon.vue';

const props = defineProps({
    id: {
        type: String,
        required: true
    },
    type: {
        type: String,
        default: 'success',
        validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    },
    message: {
        type: String,
        required: true
    },
    duration: {
        type: Number,
        default: 5000
    }
});

const emit = defineEmits(['close']);

const show = ref(false);
const timer = ref(null);

// Цвета для разных типов уведомлений
const toastClasses = computed(() => {
    const classes = {
        success: 'text-green-500 bg-green-100 dark:bg-green-800 dark:text-green-200',
        error: 'text-red-500 bg-red-100 dark:bg-red-800 dark:text-red-200',
        warning: 'text-orange-500 bg-orange-100 dark:bg-orange-800 dark:text-orange-200',
        info: 'text-blue-500 bg-blue-100 dark:bg-blue-800 dark:text-blue-200'
    };
    return classes[props.type] || classes.success;
});

const close = () => {
    show.value = false;
    if (timer.value) {
        clearTimeout(timer.value);
    }
    setTimeout(() => {
        emit('close', props.id);
    }, 300);
};

onMounted(() => {
    // Плавное появление
    setTimeout(() => {
        show.value = true;
    }, 10);

    // Автоматическое закрытие через duration
    timer.value = setTimeout(() => {
        close();
    }, props.duration);
});
</script>

<template>
    <transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
    >
        <div
            v-if="show"
            :id="`toast-${id}`"
            class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 rounded-lg shadow"
            :class="toastClasses"
            role="alert"
        >
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg">
                <!-- Success icon -->
                <svg v-if="type === 'success'" class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
                <!-- Error icon -->
                <svg v-else-if="type === 'error'" class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                </svg>
                <!-- Warning icon -->
                <svg v-else-if="type === 'warning'" class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                </svg>
                <!-- Info icon -->
                <svg v-else-if="type === 'info'" class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
            </div>
            <div class="ms-3 text-sm font-normal">{{ message }}</div>
            <button
                type="button"
                @click="close"
                class="ms-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 inline-flex items-center justify-center h-8 w-8 hover:bg-gray-200 dark:hover:bg-gray-600"
                :class="{
                    'text-gray-400 hover:text-gray-900 dark:hover:text-gray-300': type === 'success',
                    'text-red-400 hover:text-red-900 dark:hover:text-red-300': type === 'error',
                    'text-orange-400 hover:text-orange-900 dark:hover:text-orange-300': type === 'warning',
                    'text-blue-400 hover:text-blue-900 dark:hover:text-blue-300': type === 'info'
                }"
                aria-label="Close"
            >
                <XMarkIcon />
            </button>
        </div>
    </transition>
</template>

