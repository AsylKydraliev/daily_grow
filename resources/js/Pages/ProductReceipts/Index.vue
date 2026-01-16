<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link, router} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import PlusIcon from "@/Components/Icons/PlusIcon.vue";

const props = defineProps({
    productReceipts: Object,
    productReceiptsCount: Number
});

const deleteProductReceipt = (id) => {
    if (confirm('Вы уверены, что хотите удалить этот приход товара?')) {
        router.delete(`/product-receipts/${id}`);
    }
};
</script>

<template>
    <MainLayout>
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-xl font-bold">Приход товара</h3>

            <div class="flex items-center gap-2 justify-center">
                <Link :href="route('product-receipts.create')"
                      class="flex items-center gap-2 py-2 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                    <PlusIcon class="w-4 h-4" />
                    Добавить приход
                </Link>
            </div>
        </div>

        <div class="font-medium text-gray-600 mb-6">Всего приходов: {{ productReceiptsCount }}</div>

        <div class="relative overflow-x-auto mb-6">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Товар
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Филиал
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Количество
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Дата прихода
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Пользователь
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Действия
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="receipt in productReceipts.data" :key="receipt.id" class="border-b">
                    <th scope="row"
                        class="px-6 py-4 font-medium whitespace-nowrap">
                        {{ receipt.product?.name || 'N/A' }}
                    </th>
                    <td class="px-6 py-4">
                        {{ receipt.branch?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ receipt.quantity }}
                    </td>
                    <td class="px-6 py-4">
                        {{ new Date(receipt.receipt_date).toLocaleDateString('ru-RU') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ receipt.user?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <Link :href="route('product-receipts.show', receipt.id)"
                                  class="text-blue-600 hover:text-blue-800">Просмотр</Link>
                            <Link :href="route('product-receipts.edit', receipt.id)"
                                  class="text-green-600 hover:text-green-800">Редактировать</Link>
                            <button @click="deleteProductReceipt(receipt.id)"
                                    class="text-red-600 hover:text-red-800">Удалить</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <Pagination :links="productReceipts.links" class="flex justify-end items-end"/>
    </MainLayout>
</template>

