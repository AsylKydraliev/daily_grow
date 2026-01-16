<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link} from "@inertiajs/vue3";
import PencilIcon from "@/Components/Icons/PencilIcon.vue";
import ArrowLeftIcon from "@/Components/Icons/ArrowLeftIcon.vue";

const props = defineProps({
    sale: Object
});
</script>

<template>
    <MainLayout>
        <div class="flex flex-col gap-3 mb-5">
            <h3 class="text-xl font-bold mb-6">Информация о продаже</h3>
            <div class="flex gap-2">
                <Link :href="route('sales.index')"
                      class="flex items-center gap-2 text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <ArrowLeftIcon class="w-4 h-4" />
                    Назад
                </Link>
                <Link :href="route('sales.edit', sale.id)"
                      class="flex items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <PencilIcon class="w-4 h-4" />
                    Редактировать
                </Link>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 sm:max-w-lg">
            <dl class="grid grid-cols-1 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Товар</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ sale.product?.name || 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Контрагент</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ sale.counterparty?.name || 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Филиал</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ sale.branch?.name || 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Количество</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ sale.quantity }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Цена</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ parseFloat(sale.price).toLocaleString('ru-RU', { style: 'currency', currency: 'RUB' }) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Дата продажи</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ new Date(sale.sale_date).toLocaleDateString('ru-RU') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Пользователь</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ sale.user?.name || 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Дата создания</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ new Date(sale.created_at).toLocaleDateString('ru-RU') }}</dd>
                </div>
            </dl>
        </div>
    </MainLayout>
</template>

