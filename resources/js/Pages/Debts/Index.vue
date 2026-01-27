<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link, router} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import PlusIcon from "@/Components/Icons/PlusIcon.vue";
import EmptyState from "@/Components/EmptyState.vue";

const props = defineProps({
    debts: Object,
    debtsCount: Number
});

const deleteDebt = (id) => {
    if (confirm('Вы уверены, что хотите удалить этот долг?')) {
        router.delete(`/debts/${id}`);
    }
};
</script>

<template>
    <MainLayout>
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-xl font-bold">Долги</h3>

            <div class="flex items-center gap-2 justify-center">
                <Link :href="route('debts.create')"
                      class="flex items-center gap-2 py-2 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                    <PlusIcon class="w-4 h-4" />
                    Добавить долг
                </Link>
            </div>
        </div>

        <div class="font-medium text-gray-600 mb-6">Всего долгов: {{ debtsCount }}</div>

        <div class="relative overflow-x-auto mb-6">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Филиал
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Название долга
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Сумма
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Дата долга
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Действия
                    </th>
                </tr>
                </thead>
                <tbody>
                <EmptyState v-if="debts.data.length === 0" :colspan="5" />
                <tr v-for="debt in debts.data" :key="debt.id" class="border-b">
                    <td class="px-6 py-4">
                        {{ debt.branch?.name || 'N/A' }}
                    </td>
                    <th scope="row"
                        class="px-6 py-4 font-medium whitespace-nowrap">
                        {{ debt.name }}
                    </th>
                    <td class="px-6 py-4 text-center">
                        <span class="font-semibold text-gray-900">
                            {{ parseFloat(debt.amount).toLocaleString('ru-RU', { style: 'currency', currency: 'USD' }) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{ new Date(debt.debt_date).toLocaleDateString('ru-RU') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <Link :href="route('debts.show', debt.id)"
                                  class="text-blue-600 hover:text-blue-800">Просмотр</Link>
                            <Link :href="route('debts.edit', debt.id)"
                                  class="text-green-600 hover:text-green-800">Редактировать</Link>
                            <button @click="deleteDebt(debt.id)"
                                    class="text-red-600 hover:text-red-800">Удалить</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <Pagination :links="debts.links" class="flex justify-end items-end"/>
    </MainLayout>
</template>

