<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { useForm } from '@inertiajs/vue3';
import {Link} from "@inertiajs/vue3";
import CheckIcon from "@/Components/Icons/CheckIcon.vue";
import XMarkIcon from "@/Components/Icons/XMarkIcon.vue";

const props = defineProps({
    product: Object,
    branches: Array,
});

const form = useForm({
    branch_id: props.product.branch_id,
    name: props.product.name,
    price: props.product.price,
    purchase_date: props.product.purchase_date,
})

const handleSubmit = async () => {
    form.put(`/products/${props.product.id}`);
};
</script>

<template>
    <MainLayout>
        <h3 class="text-xl font-bold mb-5">Редактировать товар</h3>
        <form class="sm:max-w-lg" @submit.prevent="handleSubmit">
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
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Название</label>
                <input
                    type="text"
                    id="name"
                    v-model="form.name"
                    :class="{ 'border-red-500': form.errors.name }"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Введите название товара"
                />
                <small v-if="form.errors.name" class="text-red-600">{{ form.errors.name }}</small>
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
                />
                <small v-if="form.errors.price" class="text-red-600">{{ form.errors.price }}</small>
            </div>

            <div class="mb-5">
                <label for="purchase_date" class="block mb-2 text-sm font-medium text-gray-900">Дата закупки</label>
                <input
                    type="date"
                    id="purchase_date"
                    v-model="form.purchase_date"
                    :class="{ 'border-red-500': form.errors.purchase_date }"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                />
                <small v-if="form.errors.purchase_date" class="text-red-600">{{ form.errors.purchase_date }}</small>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    <CheckIcon class="w-4 h-4" />
                    Обновить
                </button>
                <Link :href="route('products.index')"
                      class="flex items-center gap-2 text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    <XMarkIcon class="w-4 h-4" />
                    Отмена
                </Link>
            </div>
        </form>
    </MainLayout>
</template>

