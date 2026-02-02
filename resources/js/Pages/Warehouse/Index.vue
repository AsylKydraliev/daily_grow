<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import WarehouseFilters from "@/Components/WarehouseFilters.vue";
import EmptyState from "@/Components/EmptyState.vue";
import { ref, watch } from 'vue';

const props = defineProps({
    products: Array,
    totalProfit: Number,
    branches: Array,
    filters: Object,
});

const currentFilters = ref({
    branch_id: props.filters?.branch_id || '',
    date_from: props.filters?.date_from || '',
    date_to: props.filters?.date_to || '',
});

// Синхронизируем фильтры при изменении props
watch(() => props.filters, (newFilters) => {
    currentFilters.value = {
        branch_id: newFilters?.branch_id || '',
        date_from: newFilters?.date_from || '',
        date_to: newFilters?.date_to || '',
    };
}, { deep: true });

const handleFiltersUpdate = (filters) => {
    currentFilters.value = filters;
};
</script>

<template>
    <MainLayout>
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-xl font-bold">Склад</h3>
        </div>

        <WarehouseFilters
            :branches="branches"
            :filters="filters"
            @update:filters="handleFiltersUpdate"
        />

        <div class="relative overflow-x-auto mb-6">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Название товара
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Филиал
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            Текущее количество
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            Количество продажи
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            Оставшееся количество
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            Цена прихода
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            Цена продажи
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            Прибыль
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <EmptyState v-if="products.length === 0" :colspan="8" />
                    <tr
                        v-for="product in products"
                        :key="product.id"
                        class="bg-white border-b hover:bg-gray-50"
                    >
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ product.name }}
                        </th>
                        <td class="px-6 py-4">
                            {{ product.branch_name }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-semibold text-gray-900">
                                {{ product.current_quantity || 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{ product.sale_quantity }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span
                                class="font-semibold"
                                :class="{
                                    'text-red-600': product.remaining_quantity < 0,
                                    'text-green-600': product.remaining_quantity > 0,
                                    'text-gray-600': product.remaining_quantity === 0
                                }"
                            >
                                {{ product.remaining_quantity }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{ product.receipt_price.toLocaleString('ru-RU', { style: 'currency', currency: 'USD' }) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span v-if="product.sale_price > 0">
                                {{ product.sale_price.toLocaleString('ru-RU', { style: 'currency', currency: 'USD' }) }}
                            </span>
                            <span v-else class="text-gray-400">—</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span
                                v-if="product.sale_price > 0"
                                class="font-semibold text-lg"
                                :class="{
                                    'text-green-600': product.profit > 0,
                                    'text-red-600': product.profit < 0,
                                    'text-gray-600': product.profit === 0
                                }"
                            >
                                {{ product.profit.toLocaleString('ru-RU', { style: 'currency', currency: 'USD' }) }}
                            </span>
                            <span v-else class="text-gray-400">—</span>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="products.length > 0">
                    <tr class="bg-gray-100 border-t-2 border-gray-300 font-bold">
                        <td colspan="7" class="px-6 py-4 text-xl text-right text-gray-900">
                            Итоговая прибыль:
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span
                                class="text-lg"
                                :class="{
                                    'text-green-600': totalProfit > 0,
                                    'text-red-600': totalProfit < 0,
                                    'text-gray-600': totalProfit === 0
                                }"
                            >
                                {{ (totalProfit ?? 0).toLocaleString('ru-RU', { style: 'currency', currency: 'USD' }) }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </MainLayout>
</template>

