<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    branches: {
        type: Array,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(['update:filters']);

const localFilters = ref({
    branch_id: props.filters?.branch_id || '',
    date_from: props.filters?.date_from || '',
    date_to: props.filters?.date_to || '',
});

// Обновляем локальные фильтры при изменении props
watch(() => props.filters, (newFilters) => {
    localFilters.value = {
        branch_id: newFilters?.branch_id || '',
        date_from: newFilters?.date_from || '',
        date_to: newFilters?.date_to || '',
    };
}, { deep: true });

const applyFilters = () => {
    emit('update:filters', { ...localFilters.value });
    router.get(route('warehouse.index'), localFilters.value, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilters = () => {
    localFilters.value = {
        branch_id: '',
        date_from: '',
        date_to: '',
    };
    applyFilters();
};
</script>

<template>
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="branch_filter" class="block mb-2 text-sm font-medium text-gray-900">Филиал</label>
                <select
                    id="branch_filter"
                    v-model="localFilters.branch_id"
                    @change="applyFilters"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                >
                    <option value="">Все филиалы</option>
                    <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                        {{ branch.name }}
                    </option>
                </select>
            </div>
            <div>
                <label for="date_from" class="block mb-2 text-sm font-medium text-gray-900">Дата от</label>
                <input
                    type="date"
                    id="date_from"
                    v-model="localFilters.date_from"
                    @change="applyFilters"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                />
            </div>
            <div>
                <label for="date_to" class="block mb-2 text-sm font-medium text-gray-900">Дата до</label>
                <input
                    type="date"
                    id="date_to"
                    v-model="localFilters.date_to"
                    @change="applyFilters"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                />
            </div>
            <div class="flex items-end">
                <button
                    @click="resetFilters"
                    class="w-full text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                >
                    Сбросить
                </button>
            </div>
        </div>
    </div>
</template>

