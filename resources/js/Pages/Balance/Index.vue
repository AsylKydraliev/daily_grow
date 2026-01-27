<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import EmptyState from "@/Components/EmptyState.vue";

const props = defineProps({
    branches: Array,
});
</script>

<template>
    <MainLayout>
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-xl font-bold">Баланс ТМЗ по филиалам</h3>
        </div>

        <div class="relative overflow-x-auto mb-6">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Филиал
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Адрес
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            Общее количество товаров
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            Сумма итогового остатка (USD)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <EmptyState v-if="branches.length === 0" :colspan="4" />
                    <tr
                        v-for="branch in branches"
                        :key="branch.id"
                        class="bg-white border-b hover:bg-gray-50"
                    >
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ branch.name }}
                        </th>
                        <td class="px-6 py-4">
                            {{ branch.address }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-semibold text-gray-900">
                                {{ branch.total_quantity || 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-semibold text-blue-600">
                                {{ branch.total_amount.toLocaleString('ru-RU', { style: 'currency', currency: 'USD' }) }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </MainLayout>
</template>

