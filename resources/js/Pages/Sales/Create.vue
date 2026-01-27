<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { useForm } from '@inertiajs/vue3';
import {Link} from "@inertiajs/vue3";
import CheckIcon from "@/Components/Icons/CheckIcon.vue";
import XMarkIcon from "@/Components/Icons/XMarkIcon.vue";
import BranchProductsSaleTable from "@/Components/BranchProductsSaleTable.vue";
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
    products: Array,
    counterparties: Array,
    branches: Array,
});

const form = useForm({
    branch_id: null,
    counterparty_id: null,
    sale_date: getTodayDate(),
    sales: [],
});

const productSales = ref({});

/** Сбрасываем продажи при смене филиала */
watch(() => form.branch_id, () => {
    productSales.value = {};
});

const handleSubmit = async () => {
    // Получаем только товары выбранного филиала
    const branchProducts = props.products.filter(p => p.branch_id === form.branch_id);
    const branchProductIds = new Set(branchProducts.map(p => p.id));

    // Формируем массив продаж для товаров с количеством > 0
    const sales = Object.entries(productSales.value)
        .filter(([productId, saleData]) => {
            const id = parseInt(productId);
            return branchProductIds.has(id) && saleData && saleData.quantity > 0;
        })
        .map(([productId, saleData]) => ({
            product_id: parseInt(productId),
            quantity: saleData.quantity,
            price: saleData.price || branchProducts.find(p => p.id === parseInt(productId))?.wholesale_price_usd || 0,
        }));

    form.sales = sales;
    form.post('/sales');
};
</script>

<template>
    <MainLayout>
        <h3 class="text-xl font-bold mb-5">Создать продажу</h3>
        <form class="max-w-5xl pb-7" @submit.prevent="handleSubmit">
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

            <BranchProductsSaleTable
                :branch-id="form.branch_id"
                :products="products"
                @update:sales="productSales = $event"
            />

            <div class="mb-5">
                <label for="counterparty_id" class="block mb-2 text-sm font-medium text-gray-900">Контрагент</label>
                <select
                    id="counterparty_id"
                    v-model="form.counterparty_id"
                    :class="{ 'border-red-500': form.errors.counterparty_id }"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required
                >
                    <option :value="null">Выберите контрагента</option>
                    <option v-for="counterparty in counterparties" :key="counterparty.id" :value="counterparty.id">
                        {{ counterparty.name }}
                    </option>
                </select>
                <small v-if="form.errors.counterparty_id" class="text-red-600">{{ form.errors.counterparty_id }}</small>
            </div>

            <div class="mb-5">
                <label for="sale_date" class="block mb-2 text-sm font-medium text-gray-900">Дата продажи</label>
                <input
                    type="date"
                    id="sale_date"
                    v-model="form.sale_date"
                    :class="{ 'border-red-500': form.errors.sale_date }"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                />
                <small v-if="form.errors.sale_date" class="text-red-600">{{ form.errors.sale_date }}</small>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    <CheckIcon class="w-4 h-4" />
                    Сохранить
                </button>
                <Link :href="route('sales.index')"
                      class="flex items-center gap-2 text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    <XMarkIcon class="w-4 h-4" />
                    Отмена
                </Link>
            </div>
        </form>
    </MainLayout>
</template>

