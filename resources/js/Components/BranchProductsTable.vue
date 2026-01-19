<script setup>
import { computed, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

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

const emit = defineEmits(['update:quantities']);

const quantities = ref({});

/** Фильтруем товары по выбранному филиалу */
const branchProducts = computed(() => {
    if (!props.branchId) {
        return [];
    }
    return props.products.filter(product => product.branch_id === props.branchId);
});


/** Обновляем количества при изменении */
watch(quantities, (newQuantities) => {
    emit('update:quantities', newQuantities);
}, { deep: true });

/** Инициализируем количества для всех товаров текущим количеством */
watch(() => props.branchId, () => {
    quantities.value = {};
    branchProducts.value.forEach(product => {
        quantities.value[product.id] = product.current_quantity || 0;
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
                            Цена
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Количество
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
                        <td class="px-6 py-4">
                            {{ parseFloat(product.price).toFixed(2) }} ₽
                        </td>
                        <td class="px-6 py-4">
                            <input
                                type="number"
                                :id="`quantity-${product.id}`"
                                v-model="quantities[product.id]"
                                min="0"
                                step="1"
                                class="w-32 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 font-semibold"
                            />
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
