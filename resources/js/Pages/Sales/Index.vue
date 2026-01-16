<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link, router} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import PlusIcon from "@/Components/Icons/PlusIcon.vue";

const props = defineProps({
    sales: Object,
    salesCount: Number
});

const deleteSale = (id) => {
    if (confirm('Вы уверены, что хотите удалить эту продажу?')) {
        router.delete(`/sales/${id}`);
    }
};
</script>

<template>
    <MainLayout>
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-xl font-bold">Продажи</h3>

            <div class="flex items-center gap-2 justify-center">
                <Link :href="route('sales.create')"
                      class="flex items-center gap-2 py-2 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                    <PlusIcon class="w-4 h-4" />
                    Добавить продажу
                </Link>
            </div>
        </div>

        <div class="font-medium text-gray-600 mb-6">Всего продаж: {{ salesCount }}</div>

        <div class="relative overflow-x-auto mb-6">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Товар
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Контрагент
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Филиал
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Количество
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Цена
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Дата продажи
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Действия
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="sale in sales.data" :key="sale.id" class="border-b">
                    <th scope="row"
                        class="px-6 py-4 font-medium whitespace-nowrap">
                        {{ sale.product?.name || 'N/A' }}
                    </th>
                    <td class="px-6 py-4">
                        {{ sale.counterparty?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ sale.branch?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ sale.quantity }}
                    </td>
                    <td class="px-6 py-4">
                        {{ parseFloat(sale.price).toLocaleString('ru-RU', { style: 'currency', currency: 'RUB' }) }}
                    </td>
                    <td class="px-6 py-4">
                        {{ new Date(sale.sale_date).toLocaleDateString('ru-RU') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <Link :href="route('sales.show', sale.id)"
                                  class="text-blue-600 hover:text-blue-800">Просмотр</Link>
                            <Link :href="route('sales.edit', sale.id)"
                                  class="text-green-600 hover:text-green-800">Редактировать</Link>
                            <button @click="deleteSale(sale.id)"
                                    class="text-red-600 hover:text-red-800">Удалить</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <Pagination :links="sales.links" class="flex justify-end items-end"/>
    </MainLayout>
</template>

