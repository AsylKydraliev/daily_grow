<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import MagnifyingGlassIcon from "@/Components/Icons/MagnifyingGlassIcon.vue";
import XCircleIcon from "@/Components/Icons/XCircleIcon.vue";

const props = defineProps({
    placeholder: {
        type: String,
        default: 'Поиск...'
    },
    routeName: {
        type: String,
        required: true
    },
    minLength: {
        type: Number,
        default: 3
    },
    debounceMs: {
        type: Number,
        default: 300
    }
});

const page = usePage();
const searchQuery = ref('');
const debounceTimer = ref(null);
const isInitialized = ref(false);

// Инициализация значения из URL параметра
onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('search') || '';
    searchQuery.value = searchParam;
    isInitialized.value = true;
});

// Функция для выполнения поиска
const performSearch = (value) => {
    // Пропускаем выполнение поиска до инициализации
    if (!isInitialized.value) {
        return;
    }

    // Очищаем предыдущий таймер
    if (debounceTimer.value) {
        clearTimeout(debounceTimer.value);
    }

    // Если значение пустое или меньше минимальной длины, очищаем поиск
    if (value.length === 0 || value.length < props.minLength) {
        debounceTimer.value = setTimeout(() => {
            router.get(route(props.routeName), {}, {
                preserveState: true,
                preserveScroll: true,
                replace: true
            });
        }, props.debounceMs);
        return;
    }

    // Устанавливаем новый таймер для дебаунса
    debounceTimer.value = setTimeout(() => {
        router.get(route(props.routeName), {search: value}, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        });
    }, props.debounceMs);
};

// Отслеживание изменений поискового запроса
watch(searchQuery, (newValue) => {
    performSearch(newValue);
});

// Функция для сброса поиска
const clearSearch = () => {
    // Очищаем таймер, чтобы избежать двойных запросов
    if (debounceTimer.value) {
        clearTimeout(debounceTimer.value);
    }
    // Очищаем поле поиска - watch автоматически выполнит очистку поиска
    searchQuery.value = '';
};

// Очистка таймера при размонтировании компонента
onBeforeUnmount(() => {
    if (debounceTimer.value) {
        clearTimeout(debounceTimer.value);
    }
});
</script>

<template>
    <div class="relative my-3">
        <!-- Иконка поиска -->
        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
            <MagnifyingGlassIcon class="w-4 h-4 text-gray-500"/>
        </div>

        <!-- Поле ввода -->
        <input
            type="search"
            id="search"
            v-model="searchQuery"
            :placeholder="placeholder"
            class="search-input block w-full py-2.5 p-4 ps-10 pr-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-orange-500 focus:border-orange-500"
        />

        <!-- Кнопка сброса поиска (показывается только если есть текст) -->
        <button
            v-if="searchQuery.length > 0"
            @click="clearSearch"
            type="button"
            class="absolute inset-y-0 end-0 flex items-center pe-3 text-gray-500 hover:text-gray-700 focus:outline-none"
            aria-label="Очистить поиск"
        >
            <XCircleIcon class="w-5 h-5"/>
        </button>

        <!-- Подсказка о минимальной длине поиска -->
        <div v-if="searchQuery.length > 0 && searchQuery.length < minLength"
             class="absolute top-full left-0 mt-1 text-xs text-gray-500 ps-3">
            Введите минимум {{ minLength }} символа для поиска
        </div>
    </div>
</template>

<style scoped>
/* Скрытие стандартного крестика браузера для input[type="search"] */
.search-input::-webkit-search-cancel-button {
    -webkit-appearance: none;
    appearance: none;
    display: none;
}

.search-input::-ms-clear {
    display: none;
}

.search-input::-ms-reveal {
    display: none;
}
</style>

