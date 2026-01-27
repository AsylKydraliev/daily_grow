<script setup>
import { computed, ref, watch } from 'vue';

const props = defineProps({
    branchId: {
        type: Number,
        default: null
    },
    products: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['update:receiptQuantities', 'update:wholesalePrices']);

const receiptQuantities = ref({});
const wholesalePrices = ref({});

/** Фильтруем товары по выбранному филиалу */
const branchProducts = computed(() => {
    if (!props.branchId) {
        return [];
    }
    return props.products.filter(product => product.branch_id === props.branchId);
});

/** Обновляем количества прихода при изменении */
watch(receiptQuantities, (newQuantities) => {
    emit('update:receiptQuantities', newQuantities);
}, { deep: true });

/** Обновляем оптовые цены при изменении */
watch(wholesalePrices, (newPrices) => {
    emit('update:wholesalePrices', newPrices);
}, { deep: true });

/** Инициализируем количества прихода и оптовые цены для всех товаров */
watch(() => props.branchId, () => {
    receiptQuantities.value = {};
    wholesalePrices.value = {};
    branchProducts.value.forEach(product => {
        // Количество прихода инициализируем пустым (пользователь вводит)
        receiptQuantities.value[product.id] = '';
        // Подтягиваем оптовую цену из продукта
        wholesalePrices.value[product.id] = product.wholesale_price_usd ? parseFloat(product.wholesale_price_usd).toFixed(2) : '';
    });
}, { immediate: true });
</script>

<template>
    <div v-if="branchId && branchProducts.length > 0" class="mt-6 mb-6">
        <h4 class="text-lg font-semibold mb-4">Товары филиала</h4>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg products-list">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Название товара
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Цена закупочная (USD)
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Цена оптовая (USD)
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Текущее количество
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Количество прихода
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Итого количества
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="product in branchProducts"
                        :key="product.id"
                        class="bg-white border-b hover:bg-gray-50"
                    >
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ product.name }}
                        </th>
                        <td class="px-6 py-4 text-center">
                            <span v-if="product.purchase_price_usd">
                                {{ parseFloat(product.purchase_price_usd).toFixed(2) }} $
                            </span>
                            <span v-else class="text-gray-400">—</span>
                        </td>
                        <td class="px-6 py-4">
                            <input
                                type="number"
                                step="0.01"
                                :id="`wholesale-price-${product.id}`"
                                v-model="wholesalePrices[product.id]"
                                min="0"
                                class="w-32 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2"
                                placeholder="0.00"
                            />
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-semibold text-gray-900">
                                {{ product.current_quantity || 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <input
                                type="number"
                                :id="`receipt-quantity-${product.id}`"
                                v-model="receiptQuantities[product.id]"
                                min="0"
                                step="1"
                                class="w-32 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 font-semibold"
                                placeholder="0"
                            />
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-lg text-blue-600">
                                {{ (product.current_quantity || 0) + (parseInt(receiptQuantities[product.id]) || 0) }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div v-else-if="branchId && branchProducts.length === 0" class="mt-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-sm text-yellow-800">У выбранного филиала нет товаров</p>
        </div>
    </div>
</template>

<style scoped>
.products-list {
    max-height: 600px;
}
</style>
