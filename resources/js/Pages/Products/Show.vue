<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link} from "@inertiajs/vue3";
import PencilIcon from "@/Components/Icons/PencilIcon.vue";
import ArrowLeftIcon from "@/Components/Icons/ArrowLeftIcon.vue";

const props = defineProps({
    product: Object
});
</script>

<template>
    <MainLayout>
        <div class="flex flex-col gap-3 mb-5">
            <h3 class="text-xl font-bold mb-6">Информация о товаре</h3>
            <div class="flex gap-2">
                <Link :href="route('products.index')"
                      class="flex items-center gap-2 text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <ArrowLeftIcon class="w-4 h-4" />
                    Назад
                </Link>
                <Link :href="route('products.edit', product.id)"
                      class="flex items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <PencilIcon class="w-4 h-4" />
                    Редактировать
                </Link>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 sm:max-w-lg">
            <dl class="grid grid-cols-1 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Название</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ product.name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Филиал</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ product.branch?.name || 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Цена закупочная</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <div v-if="product.purchase_price_usd || product.purchase_price_kzt" class="space-y-1">
                            <div v-if="product.purchase_price_usd">
                                USD: {{ parseFloat(product.purchase_price_usd).toLocaleString('ru-RU', { style: 'currency', currency: 'USD' }) }}
                            </div>
                            <div v-if="product.purchase_price_kzt">
                                KZT: {{ parseFloat(product.purchase_price_kzt).toLocaleString('ru-RU', { style: 'currency', currency: 'KZT' }) }}
                            </div>
                        </div>
                        <span v-else class="text-gray-400">—</span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Цена оптовая</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <div v-if="product.wholesale_price_usd || product.wholesale_price_kzt" class="space-y-1">
                            <div v-if="product.wholesale_price_usd">
                                USD: {{ parseFloat(product.wholesale_price_usd).toLocaleString('ru-RU', { style: 'currency', currency: 'USD' }) }}
                            </div>
                            <div v-if="product.wholesale_price_kzt">
                                KZT: {{ parseFloat(product.wholesale_price_kzt).toLocaleString('ru-RU', { style: 'currency', currency: 'KZT' }) }}
                            </div>
                        </div>
                        <span v-else class="text-gray-400">—</span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Текущее количество</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ product.current_quantity || 0 }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Дата создания</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ new Date(product.created_at).toLocaleDateString('ru-RU') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Дата обновления</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ new Date(product.updated_at).toLocaleDateString('ru-RU') }}</dd>
                </div>
            </dl>
        </div>
    </MainLayout>
</template>

