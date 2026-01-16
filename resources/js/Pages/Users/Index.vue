<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link, router} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import SearchInput from "@/Components/SearchInput.vue";
import PlusIcon from "@/Components/Icons/PlusIcon.vue";

const props = defineProps({
    users: Object,
    usersCount: Number,
    canManageUsers: Boolean,
});

const deleteUser = (id) => {
    if (confirm('Вы уверены, что хотите удалить этого пользователя?')) {
        router.delete(`/users/${id}`);
    }
};
</script>

<template>
    <MainLayout>
        <SearchInput
            route-name="users.index"
            placeholder="Поиск по имени или логину"
        />

        <div class="flex justify-between items-center mb-2">
            <h3 class="text-xl font-bold">Пользователи</h3>

            <div class="flex items-center gap-2 justify-center" v-if="canManageUsers">
                <Link :href="route('users.create')"
                      class="flex items-center gap-2 py-2 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                    <PlusIcon class="w-4 h-4" />
                    Добавить пользователя
                </Link>
            </div>
        </div>

        <div class="font-medium text-gray-600 mb-6">Всего пользователей: {{ usersCount }}</div>

        <div class="relative overflow-x-auto mb-6">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Имя
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Логин
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Роль
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Дата создания
                    </th>
                    <th scope="col" class="px-6 py-3" v-if="canManageUsers">
                        Действия
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="users.data.length === 0" class="border-b">
                    <td :colspan="canManageUsers ? 5 : 4" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-lg font-medium">Результаты не найдены</p>
                            <p class="text-sm mt-1">Попробуйте изменить параметры поиска</p>
                        </div>
                    </td>
                </tr>
                <tr v-for="user in users.data" :key="user.id" class="border-b">
                    <th scope="row"
                        class="px-6 py-4 font-medium whitespace-nowrap">
                        {{ user.name }}
                    </th>
                    <td class="px-6 py-4">
                        {{ user.login }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full"
                              :class="{
                                  'bg-blue-100 text-blue-800': user.role === 'продавец',
                                  'bg-green-100 text-green-800': user.role === 'админ',
                                  'bg-purple-100 text-purple-800': user.role === 'супер-админ'
                              }">
                            {{ user.role }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        {{ new Date(user.created_at).toLocaleDateString('ru-RU') }}
                    </td>
                    <td class="px-6 py-4" v-if="canManageUsers">
                        <div class="flex gap-2">
                            <Link :href="route('users.show', user.id)"
                                  class="text-blue-600 hover:text-blue-800">Просмотр</Link>
                            <Link :href="route('users.edit', user.id)"
                                  class="text-green-600 hover:text-green-800">Редактировать</Link>
                            <button @click="deleteUser(user.id)"
                                    class="text-red-600 hover:text-red-800">Удалить</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <Pagination :links="users.links" class="flex justify-end items-end"/>
    </MainLayout>
</template>

