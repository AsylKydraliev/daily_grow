<script setup>
import { computed, ref, watch } from 'vue';

const props = defineProps({
    branchId: {
        type: Number,
        default: null
    },
    products: {
        type: Array,
        default: () => []
    }
});

// TODO Вывести количкество прихода в остатках товаров на странице Балансе и внизу итоги
// TODO Добавить погашение долга и добавить историю долгов
// TODO Добавить на страницк Скалада количество прихода
// TODO Добавить остаток кассы, денежных средств, расчестный счет Каспи ТОО
// TODO Добавить Вывести в ОПиУ сумму всех долгов
// TODO Добавить Расходы
// TODO Добавить Активы (Сумма всех - ТМЗ, долги, остаток кассы, денежных средств)
// TODO Добавить долги партнера (приход товара + сколько вернули долги потсавщику)

const emit = defineEmits(['update:sales']);

const saleQuantities = ref({});
const salePrices = ref({});

/** Фильтруем товары по выбранному филиалу */
const branchProducts = computed(() => {
    if (!props.branchId) {
        return [];
    }
    return props.products.filter(product => product.branch_id === props.branchId);
});

/** Остаток товара (как на складе — текущее количество в БД) */
const getRemainingQuantity = (product) => {
    return Math.max(0, product.current_quantity || 0);
};

/** Остаток после введённой продажи (текущее количество − количество в форме) */
const getRemainingAfterSale = (product) => {
    const currentQty = product.current_quantity || 0;
    const currentSaleQty = saleQuantities.value[product.id] ? parseInt(saleQuantities.value[product.id]) : 0;
    return currentQty - currentSaleQty;
};

/** Обновляем продажи при изменении */
watch([saleQuantities, salePrices], () => {
    const sales = {};
    Object.keys(saleQuantities.value).forEach(productId => {
        const qty = saleQuantities.value[productId];
        const price = salePrices.value[productId];
        if (qty && parseInt(qty) > 0) {
            // Если цена не указана, используем цену товара
            const product = branchProducts.value.find(p => p.id === parseInt(productId));
            const finalPrice = price ? parseFloat(price) : (product?.wholesale_price_usd ? parseFloat(product.wholesale_price_usd) : 0);

            sales[productId] = {
                quantity: parseInt(qty),
                price: finalPrice,
            };
        }
    });
    emit('update:sales', sales);
}, { deep: true });

/** Инициализируем количества и цены при смене филиала */
watch(() => props.branchId, () => {
    saleQuantities.value = {};
    salePrices.value = {};
}, { immediate: true });

/** Инициализируем цены по умолчанию из цены товара при изменении списка товаров */
watch(() => branchProducts.value, (products) => {
    if (products && products.length > 0) {
        products.forEach(product => {
            if (product.wholesale_price_usd && !salePrices.value[product.id]) {
                salePrices.value[product.id] = parseFloat(product.wholesale_price_usd).toFixed(2);
            }
        });
    }
}, { immediate: true });
</script>

<template>
    <div v-if="branchId && branchProducts.length > 0" class="mt-6 mb-6">
        <h4 class="text-lg font-semibold mb-4">Товары филиала</h4>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg products-list">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Название товара
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Цена закупочная (USD)
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Текущее количество
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Количество продажи
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Цена продажи
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Остаток
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="product in branchProducts"
                        :key="product.id"
                        class="bg-white border-b hover:bg-gray-50"
                    >
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ product.name }}
                        </th>
                        <td class="px-6 py-4 text-center">
                            <span v-if="product.purchase_price_usd">
                                {{ parseFloat(product.purchase_price_usd).toFixed(2) }} $
                            </span>
                            <span v-else class="text-gray-400">—</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-semibold text-gray-900">{{ product.current_quantity || 0 }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <input
                                type="number"
                                :id="`sale-quantity-${product.id}`"
                                v-model="saleQuantities[product.id]"
                                :max="getRemainingQuantity(product)"
                                min="0"
                                step="1"
                                class="w-32 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2"
                                placeholder="0"
                            />
                        </td>
                        <td class="px-6 py-4">
                            <input
                                type="number"
                                step="0.01"
                                :id="`sale-price-${product.id}`"
                                v-model="salePrices[product.id]"
                                :placeholder="product.wholesale_price_usd ? parseFloat(product.wholesale_price_usd).toFixed(2) : '0.00'"
                                min="0"
                                class="w-32 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2"
                            />
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="font-bold text-lg"
                                :class="{
                                    'text-red-600': getRemainingAfterSale(product) < 0,
                                    'text-green-600': getRemainingAfterSale(product) >= 0 && getRemainingAfterSale(product) > 0,
                                    'text-gray-600': getRemainingAfterSale(product) === 0
                                }"
                            >
                                {{ getRemainingAfterSale(product) }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div v-else-if="branchId && branchProducts.length === 0" class="mt-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-sm text-yellow-800">У выбранного филиала нет товаров</p>
        </div>
    </div>
</template>

<style scoped>
.products-list {
    max-height: 600px;
}
</style>

