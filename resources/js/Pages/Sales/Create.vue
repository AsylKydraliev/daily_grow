<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { useForm } from '@inertiajs/vue3';
import {Link} from "@inertiajs/vue3";
import CheckIcon from "@/Components/Icons/CheckIcon.vue";
import XMarkIcon from "@/Components/Icons/XMarkIcon.vue";

const props = defineProps({
    products: Array,
    counterparties: Array,
    branches: Array,
    users: Array,
});

const form = useForm({
    product_id: null,
    counterparty_id: null,
    branch_id: null,
    user_id: null,
    quantity: null,
    price: null,
    sale_date: null,
})

const handleSubmit = async () => {
    form.post('/sales');
};
</script>

<template>
    <MainLayout>
        <h3 class="text-xl font-bold mb-5">Создать продажу</h3>
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
                <label for="counterparty_id" class="block mb-2 text-sm font-medium text-gray-900">Контрагент</label>
                <select
                    id="counterparty_id"
                    v-model="form.counterparty_id"
                    :class="{ 'border-red-500': form.errors.counterparty_id }"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                >
                    <option value="">Выберите контрагента</option>
                    <option v-for="counterparty in counterparties" :key="counterparty.id" :value="counterparty.id">
                        {{ counterparty.name }}
                    </option>
                </select>
                <small v-if="form.errors.counterparty_id" class="text-red-600">{{ form.errors.counterparty_id }}</small>
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
                <label for="price" class="block mb-2 text-sm font-medium text-gray-900">Цена</label>
                <input
                    type="number"
                    step="0.01"
                    id="price"
                    v-model="form.price"
                    :class="{ 'border-red-500': form.errors.price }"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="0.00"
                    min="0"
                />
                <small v-if="form.errors.price" class="text-red-600">{{ form.errors.price }}</small>
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

