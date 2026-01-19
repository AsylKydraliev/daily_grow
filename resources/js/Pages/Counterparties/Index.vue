<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link, router} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import SearchInput from "@/Components/SearchInput.vue";
import PlusIcon from "@/Components/Icons/PlusIcon.vue";
import EmptyState from "@/Components/EmptyState.vue";

const props = defineProps({
    counterparties: Object,
    counterpartiesCount: Number
});

const deleteCounterparty = (id) => {
    if (confirm('Вы уверены, что хотите удалить этого контрагента?')) {
        router.delete(`/counterparties/${id}`);
    }
};
</script>

<template>
    <MainLayout>
        <SearchInput 
            route-name="counterparties.index" 
            placeholder="Поиск по названию или адресу"
        />

        <div class="flex justify-between items-center mb-2">
            <h3 class="text-xl font-bold">Контрагенты</h3>

            <div class="flex items-center gap-2 justify-center">
                <Link :href="route('counterparties.create')"
                      class="flex items-center gap-2 py-2 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                    <PlusIcon class="w-4 h-4" />
                    Добавить контрагента
                </Link>
            </div>
        </div>

        <div class="font-medium text-gray-600 mb-6">Всего контрагентов: {{ counterpartiesCount }}</div>

        <div class="relative overflow-x-auto mb-6">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Название
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Адрес
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Филиал
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Дата создания
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Действия
                    </th>
                </tr>
                </thead>
                <tbody>
                <EmptyState v-if="counterparties.data.length === 0" :colspan="5" />
                <tr v-for="counterparty in counterparties.data" :key="counterparty.id" class="border-b">
                    <th scope="row"
                        class="px-6 py-4 font-medium whitespace-nowrap">
                        {{ counterparty.name }}
                    </th>
                    <td class="px-6 py-4">
                        {{ counterparty.address }}
                    </td>
                    <td class="px-6 py-4">
                        {{ counterparty.branch ? counterparty.branch.name : 'Не указан' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ new Date(counterparty.created_at).toLocaleDateString('ru-RU') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <Link :href="route('counterparties.show', counterparty.id)"
                                  class="text-blue-600 hover:text-blue-800">Просмотр</Link>
                            <Link :href="route('counterparties.edit', counterparty.id)"
                                  class="text-green-600 hover:text-green-800">Редактировать</Link>
                            <button @click="deleteCounterparty(counterparty.id)"
                                    class="text-red-600 hover:text-red-800">Удалить</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <Pagination :links="counterparties.links" class="flex justify-end items-end"/>
    </MainLayout>
</template>

