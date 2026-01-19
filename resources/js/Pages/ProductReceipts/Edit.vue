<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { useForm } from '@inertiajs/vue3';
import {Link} from "@inertiajs/vue3";
import CheckIcon from "@/Components/Icons/CheckIcon.vue";
import XMarkIcon from "@/Components/Icons/XMarkIcon.vue";
import BranchProductsTable from "@/Components/BranchProductsTable.vue";
import { ref, watch } from 'vue';

// Получаем сегодняшнюю дату в формате YYYY-MM-DD
const getTodayDate = () => {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

const props = defineProps({
    productReceipt: Object,
    products: Array,
    branches: Array,
});

const form = useForm({
    branch_id: props.productReceipt.branch_id,
    receipt_date: props.productReceipt.receipt_date || getTodayDate(),
    products: [],
});

const productQuantities = ref({});

// Инициализируем количества товаров текущими значениями при загрузке
watch(() => props.products, (products) => {
    if (products && props.productReceipt.branch_id) {
        const branchProducts = products.filter(p => p.branch_id === props.productReceipt.branch_id);
        branchProducts.forEach(product => {
            productQuantities.value[product.id] = product.current_quantity || 0;
        });
    }
}, { immediate: true });

/** Сбрасываем количества при смене филиала */
watch(() => form.branch_id, () => {
    productQuantities.value = {};
    if (form.branch_id) {
        const branchProducts = props.products.filter(p => p.branch_id === form.branch_id);
        branchProducts.forEach(product => {
            productQuantities.value[product.id] = product.current_quantity || 0;
        });
    }
});

const handleSubmit = async () => {
    // Получаем только товары выбранного филиала
    const branchProducts = props.products.filter(p => p.branch_id === form.branch_id);
    const branchProductIds = new Set(branchProducts.map(p => p.id));
    
    // Создаем маппинг товаров для быстрого доступа
    const productsMap = new Map(branchProducts.map(p => [p.id, p]));

    // Формируем массив товаров с разницей между новым и старым количеством
    const products = Object.entries(productQuantities.value)
        .filter(([productId]) => {
            const id = parseInt(productId);
            return branchProductIds.has(id);
        })
        .map(([productId, newQuantity]) => {
            const id = parseInt(productId);
            const product = productsMap.get(id);
            const oldQuantity = product?.current_quantity || 0;
            const newQty = parseInt(newQuantity) || 0;
            const difference = newQty - oldQuantity;
            
            return {
                product_id: id,
                quantity: difference, // Разница между новым и старым количеством
            };
        })
        .filter(p => p.quantity > 0); // Только товары с положительной разницей (приход)

    form.products = products;
    form.put(`/product-receipts/${props.productReceipt.id}`);
};
</script>

<template>
    <MainLayout>
        <h3 class="text-xl font-bold mb-5">Редактировать приход товара</h3>
        <form class="max-w-4xl pb-7" @submit.prevent="handleSubmit">
            <div class="mb-5">
                <label for="branch_id" class="block mb-2 text-sm font-medium text-gray-900">Филиал</label>
                <select
                    id="branch_id"
                    v-model="form.branch_id"
                    :class="{ 'border-red-500': form.errors.branch_id }"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required
                >
                    <option :value="null">Выберите филиал</option>
                    <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                        {{ branch.name }}
                    </option>
                </select>
                <small v-if="form.errors.branch_id" class="text-red-600">{{ form.errors.branch_id }}</small>
            </div>

            <BranchProductsTable
                :branch-id="form.branch_id"
                :products="products"
                @update:quantities="productQuantities = $event"
            />

            <div class="mb-5">
                <label for="receipt_date" class="block mb-2 text-sm font-medium text-gray-900">Дата прихода</label>
                <input
                    type="date"
                    id="receipt_date"
                    v-model="form.receipt_date"
                    :class="{ 'border-red-500': form.errors.receipt_date }"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                />
                <small v-if="form.errors.receipt_date" class="text-red-600">{{ form.errors.receipt_date }}</small>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    <CheckIcon class="w-4 h-4" />
                    Обновить
                </button>
                <Link :href="route('product-receipts.index')"
                      class="flex items-center gap-2 text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    <XMarkIcon class="w-4 h-4" />
                    Отмена
                </Link>
            </div>
        </form>
    </MainLayout>
</template>

