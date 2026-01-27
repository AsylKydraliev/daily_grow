<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link} from "@inertiajs/vue3";
import PencilIcon from "@/Components/Icons/PencilIcon.vue";
import ArrowLeftIcon from "@/Components/Icons/ArrowLeftIcon.vue";

const props = defineProps({
    debt: Object
});
</script>

<template>
    <MainLayout>
        <div class="flex flex-col gap-3 mb-5">
            <h3 class="text-xl font-bold mb-6">Информация о долге</h3>
            <div class="flex gap-2">
                <Link :href="route('debts.index')"
                      class="flex items-center gap-2 text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <ArrowLeftIcon class="w-4 h-4" />
                    Назад
                </Link>
                <Link :href="route('debts.edit', debt.id)"
                      class="flex items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <PencilIcon class="w-4 h-4" />
                    Редактировать
                </Link>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 sm:max-w-lg">
            <dl class="grid grid-cols-1 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Филиал</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ debt.branch?.name || 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Название долга</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ debt.name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Сумма</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-semibold">
                        {{ parseFloat(debt.amount).toLocaleString('ru-RU', { style: 'currency', currency: 'USD' }) }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Дата долга</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ new Date(debt.debt_date).toLocaleDateString('ru-RU') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Дата создания</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ new Date(debt.created_at).toLocaleDateString('ru-RU') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Дата обновления</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ new Date(debt.updated_at).toLocaleDateString('ru-RU') }}</dd>
                </div>
            </dl>
        </div>
    </MainLayout>
</template>

