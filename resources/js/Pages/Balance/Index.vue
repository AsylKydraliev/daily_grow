<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import EmptyState from "@/Components/EmptyState.vue";
import { ref, computed } from "vue";
import axios from "axios";

const props = defineProps({
    branches: Array,
});

/** Модалка со списком товаров филиала */
const showProductsModal = ref(false);
const modalBranch = ref(null);
const modalProducts = ref([]);
const modalLoading = ref(false);

/** Открыть модалку и загрузить товары филиала (остаток > 0) */
const openBranchProducts = async (branch) => {
    modalBranch.value = { id: branch.id, name: branch.name };
    modalProducts.value = [];
    showProductsModal.value = true;
    modalLoading.value = true;
    try {
        const { data } = await axios.get(route("balance.branch-products", branch.id));
        modalBranch.value = data.branch;
        modalProducts.value = data.products;
    } catch {
        modalProducts.value = [];
    } finally {
        modalLoading.value = false;
    }
};

const closeProductsModal = () => {
    showProductsModal.value = false;
    modalBranch.value = null;
    modalProducts.value = [];
};

/** Итоговая сумма по всем товарам в модалке (USD и KZT) */
const modalTotalUsd = computed(() =>
    modalProducts.value.reduce((sum, p) => sum + (p.total_usd ?? 0), 0)
);
const modalTotalKzt = computed(() =>
    modalProducts.value.reduce((sum, p) => sum + (p.total_kzt ?? 0), 0)
);

/** Форматирование цены для отображения */
const formatPrice = (value, currency = "USD") => {
    if (value === null || value === undefined) return "—";
    return Number(value).toLocaleString("ru-RU", {
        style: "currency",
        currency: currency === "KZT" ? "KZT" : "USD",
        minimumFractionDigits: 2,
    });
};
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
                        class="bg-white border-b hover:bg-gray-50 cursor-pointer transition-colors"
                        @click="openBranchProducts(branch)"
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

        <!-- Модалка: товары филиала с остатком > 0 -->
        <Teleport to="body">
            <div
                v-if="showProductsModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
                @click.self="closeProductsModal"
            >
                <div
                    class="bg-white rounded-lg shadow-xl max-w-[95vw] w-full max-h-[90vh] flex flex-col"
                    @click.stop
                >
                    <div class="flex items-center justify-between p-4 border-b">
                        <h4 class="text-lg font-semibold text-gray-900">
                            Остатки товаров — {{ modalBranch?.name ?? '' }}
                        </h4>
                        <button
                            type="button"
                            class="text-gray-400 hover:text-gray-600 p-1"
                            aria-label="Закрыть"
                            @click="closeProductsModal"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 overflow-auto flex-1">
                        <div v-if="modalLoading" class="text-center py-8 text-gray-500">
                            Загрузка...
                        </div>
                        <div v-else-if="modalProducts.length === 0" class="text-center py-8 text-gray-500">
                            Нет товаров с остатком
                        </div>
                        <div v-else class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 min-w-[180px]">Название</th>
                                        <th scope="col" class="px-4 py-3 text-center">Количество</th>
                                        <th scope="col" class="px-4 py-3 text-right whitespace-nowrap">Закупочная (USD)</th>
                                        <th scope="col" class="px-4 py-3 text-right whitespace-nowrap">Закупочная (KZT)</th>
                                        <th scope="col" class="px-4 py-3 text-right whitespace-nowrap">Оптовая (USD)</th>
                                        <th scope="col" class="px-4 py-3 text-right whitespace-nowrap">Оптовая (KZT)</th>
                                        <th scope="col" class="px-4 py-3 text-right whitespace-nowrap font-semibold">Итоговая сумма (USD)</th>
                                        <th scope="col" class="px-4 py-3 text-right whitespace-nowrap font-semibold">Итоговая сумма (KZT)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="product in modalProducts"
                                        :key="product.id"
                                        class="border-b hover:bg-gray-50"
                                    >
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ product.name }}</td>
                                        <td class="px-4 py-3 text-center font-semibold">{{ product.quantity }}</td>
                                        <td class="px-4 py-3 text-right">{{ formatPrice(product.purchase_price_usd, 'USD') }}</td>
                                        <td class="px-4 py-3 text-right">{{ formatPrice(product.purchase_price_kzt, 'KZT') }}</td>
                                        <td class="px-4 py-3 text-right">{{ formatPrice(product.wholesale_price_usd, 'USD') }}</td>
                                        <td class="px-4 py-3 text-right">{{ formatPrice(product.wholesale_price_kzt, 'KZT') }}</td>
                                        <td class="px-4 py-3 text-right font-semibold text-blue-600">{{ formatPrice(product.total_usd, 'USD') }}</td>
                                        <td class="px-4 py-3 text-right font-semibold text-blue-600">{{ formatPrice(product.total_kzt, 'KZT') }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-100 border-t-2 border-gray-300 font-bold">
                                        <td colspan="6" class="px-4 py-3 text-right text-gray-900">
                                            Итого:
                                        </td>
                                        <td class="px-4 py-3 text-right text-blue-600">{{ formatPrice(modalTotalUsd, 'USD') }}</td>
                                        <td class="px-4 py-3 text-right text-blue-600">{{ formatPrice(modalTotalKzt, 'KZT') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </MainLayout>
</template>

