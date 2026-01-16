<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link} from "@inertiajs/vue3";
import PencilIcon from "@/Components/Icons/PencilIcon.vue";
import ArrowLeftIcon from "@/Components/Icons/ArrowLeftIcon.vue";

const props = defineProps({
    user: Object,
    canManageUsers: Boolean,
});
</script>

<template>
    <MainLayout>
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-xl font-bold">Информация о пользователе</h3>
            <div class="flex gap-2" v-if="canManageUsers">
                <Link :href="route('users.edit', user.id)"
                      class="flex items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <PencilIcon class="w-4 h-4" />
                    Редактировать
                </Link>
                <Link :href="route('users.index')"
                      class="flex items-center gap-2 text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <ArrowLeftIcon class="w-4 h-4" />
                    Назад
                </Link>
            </div>
            <div v-else>
                <Link :href="route('users.index')"
                      class="flex items-center gap-2 text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <ArrowLeftIcon class="w-4 h-4" />
                    Назад
                </Link>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 sm:max-w-lg">
            <dl class="grid grid-cols-1 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Имя</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ user.name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Логин</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ user.login }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Роль</dt>
                    <dd class="mt-1">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full"
                              :class="{
                                  'bg-blue-100 text-blue-800': user.role === 'продавец',
                                  'bg-green-100 text-green-800': user.role === 'админ',
                                  'bg-purple-100 text-purple-800': user.role === 'супер-админ'
                              }">
                            {{ user.role }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Дата создания</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ new Date(user.created_at).toLocaleDateString('ru-RU') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Дата обновления</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ new Date(user.updated_at).toLocaleDateString('ru-RU') }}</dd>
                </div>
            </dl>
        </div>
    </MainLayout>
</template>

