<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link, router} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import SearchInput from "@/Components/SearchInput.vue";
import PlusIcon from "@/Components/Icons/PlusIcon.vue";

const props = defineProps({
    products: Object,
    productsCount: Number
});

const deleteProduct = (id) => {
    if (confirm('Вы уверены, что хотите удалить этот товар?')) {
        router.delete(`/products/${id}`);
    }
};
</script>

<template>
    <MainLayout>
        <SearchInput
            route-name="products.index"
            placeholder="Поиск по названию"
        />

        <div class="flex justify-between items-center mb-2">
            <h3 class="text-xl font-bold">Товары</h3>

            <div class="flex items-center gap-2 justify-center">
                <Link :href="route('products.create')"
                      class="flex items-center gap-2 py-2 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                    <PlusIcon class="w-4 h-4" />
                    Добавить товар
                </Link>
            </div>
        </div>

        <div class="font-medium text-gray-600 mb-6">Всего товаров: {{ productsCount }}</div>

        <div class="relative overflow-x-auto mb-6">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Название
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Филиал
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Цена
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Дата закупки
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Действия
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="products.data.length === 0" class="border-b">
                    <td :colspan="5" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-lg font-medium">Результаты не найдены</p>
                            <p class="text-sm mt-1">Попробуйте изменить параметры поиска</p>
                        </div>
                    </td>
                </tr>
                <tr v-for="product in products.data" :key="product.id" class="border-b">
                    <th scope="row"
                        class="px-6 py-4 font-medium whitespace-nowrap">
                        {{ product.name }}
                    </th>
                    <td class="px-6 py-4">
                        {{ product.branch?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ parseFloat(product.price).toLocaleString('ru-RU', { style: 'currency', currency: 'RUB' }) }}
                    </td>
                    <td class="px-6 py-4">
                        {{ new Date(product.purchase_date).toLocaleDateString('ru-RU') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <Link :href="route('products.show', product.id)"
                                  class="text-blue-600 hover:text-blue-800">Просмотр</Link>
                            <Link :href="route('products.edit', product.id)"
                                  class="text-green-600 hover:text-green-800">Редактировать</Link>
                            <button @click="deleteProduct(product.id)"
                                    class="text-red-600 hover:text-red-800">Удалить</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <Pagination :links="products.links" class="flex justify-end items-end"/>
    </MainLayout>
</template>

