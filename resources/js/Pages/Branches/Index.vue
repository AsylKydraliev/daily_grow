<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link, router} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import SearchInput from "@/Components/SearchInput.vue";
import PlusIcon from "@/Components/Icons/PlusIcon.vue";
import EmptyState from "@/Components/EmptyState.vue";

const props = defineProps({
    branches: Object,
    branchesCount: Number
});

const deleteBranch = (id) => {
    if (confirm('Вы уверены, что хотите удалить этот филиал?')) {
        router.delete(`/branches/${id}`);
    }
};
</script>

<template>
    <MainLayout>
        <SearchInput
            route-name="branches.index"
            placeholder="Поиск по названию"
        />

        <div class="flex justify-between items-center mb-2">
            <h3 class="text-xl font-bold">Филиалы</h3>

            <div class="flex items-center gap-2 justify-center">
                <Link :href="route('branches.create')"
                      class="flex items-center gap-2 py-2 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                    <PlusIcon class="w-4 h-4" />
                    Добавить филиал
                </Link>
            </div>
        </div>

        <div class="font-medium text-gray-600 mb-6">Всего филиалов: {{ branchesCount }}</div>

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
                        Дата создания
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Действия
                    </th>
                </tr>
                </thead>
                <tbody>
                <EmptyState v-if="branches.data.length === 0" :colspan="4" />
                <tr v-for="branch in branches.data" :key="branch.id" class="border-b">
                    <th scope="row"
                        class="px-6 py-4 font-medium whitespace-nowrap">
                        {{ branch.name }}
                    </th>
                    <td class="px-6 py-4">
                        {{ branch.address }}
                    </td>
                    <td class="px-6 py-4">
                        {{ new Date(branch.created_at).toLocaleDateString('ru-RU') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <Link :href="route('branches.show', branch.id)"
                                  class="text-blue-600 hover:text-blue-800">Просмотр</Link>
                            <Link :href="route('branches.edit', branch.id)"
                                  class="text-green-600 hover:text-green-800">Редактировать</Link>
                            <button @click="deleteBranch(branch.id)"
                                    class="text-red-600 hover:text-red-800">Удалить</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <Pagination :links="branches.links" class="flex justify-end items-end"/>
    </MainLayout>
</template>

