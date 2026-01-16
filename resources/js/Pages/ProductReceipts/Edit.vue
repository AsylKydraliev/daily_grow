<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { useForm } from '@inertiajs/vue3';
import {Link} from "@inertiajs/vue3";
import CheckIcon from "@/Components/Icons/CheckIcon.vue";
import XMarkIcon from "@/Components/Icons/XMarkIcon.vue";

const props = defineProps({
    productReceipt: Object,
    products: Array,
    branches: Array,
    users: Array,
});

const form = useForm({
    product_id: props.productReceipt.product_id,
    branch_id: props.productReceipt.branch_id,
    user_id: props.productReceipt.user_id,
    quantity: props.productReceipt.quantity,
    receipt_date: props.productReceipt.receipt_date,
})

const handleSubmit = async () => {
    form.put(`/product-receipts/${props.productReceipt.id}`);
};
</script>

<template>
    <MainLayout>
        <h3 class="text-xl font-bold mb-5">Редактировать приход товара</h3>
        <form class="sm:max-w-lg" @submit.prevent="handleSubmit">
            <div class="mb-5">
                <label for="product_id" class="block mb-2 text-sm font-medium text-gray-900">Товар</label>
                <select
                    id="product_id"
                    v-model="form.product_id"
                    :class="{ 'border-red-500': form.errors.product_id }"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                >
                    <option value="">Выберите товар</option>
                    <option v-for="product in products" :key="product.id" :value="product.id">
                        {{ product.name }}
                    </option>
                </select>
                <small v-if="form.errors.product_id" class="text-red-600">{{ form.errors.product_id }}</small>
            </div>

            <div class="mb-5">
                <label for="branch_id" class="block mb-2 text-sm font-medium text-gray-900">Филиал</label>
                <select
                    id="branch_id"
                    v-model="form.branch_id"
                    :class="{ 'border-red-500': form.errors.branch_id }"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                >
                    <option value="">Выберите филиал</option>
                    <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                        {{ branch.name }}
                    </option>
                </select>
                <small v-if="form.errors.branch_id" class="text-red-600">{{ form.errors.branch_id }}</small>
            </div>

            <div class="mb-5">
                <label for="user_id" class="block mb-2 text-sm font-medium text-gray-900">Пользователь</label>
                <select
                    id="user_id"
                    v-model="form.user_id"
                    :class="{ 'border-red-500': form.errors.user_id }"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                >
                    <option value="">Выберите пользователя (необязательно)</option>
                    <option v-for="user in users" :key="user.id" :value="user.id">
                        {{ user.name }}
                    </option>
                </select>
                <small v-if="form.errors.user_id" class="text-red-600">{{ form.errors.user_id }}</small>
            </div>

            <div class="mb-5">
                <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900">Количество</label>
                <input
                    type="number"
                    id="quantity"
                    v-model="form.quantity"
                    :class="{ 'border-red-500': form.errors.quantity }"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="0"
                    min="1"
                />
                <small v-if="form.errors.quantity" class="text-red-600">{{ form.errors.quantity }}</small>
            </div>

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

