<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
</script>

<template>
    <MainLayout>
        <h3 class="text-xl font-bold mt-2 mb-5">Статистика</h3>
        <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
            <div class="w-full p-10 bg-white rounded-lg shadow">
                <div class="text-xl font-medium text-gray-500 truncate">
                    Количество контрагентов
                </div>
                <div class="mt-1 text-3xl font-semibold text-gray-900">
                    50
                </div>
            </div>
            <div class="w-full p-10 bg-white rounded-lg shadow">
                <div class="text-xl font-medium text-gray-500 truncate">
                    Количество заказов
                </div>
                <div class="mt-1 text-3xl font-semibold text-gray-900">
                    583
                </div>
            </div>
            <div class="w-full p-10 bg-white rounded-lg shadow">
                <div class="text-xl font-medium text-gray-500 truncate">
                    Доставленных товаров
                </div>
                <div class="mt-1 text-3xl font-semibold text-gray-900">
                    650
                </div>
            </div>
        </div>
    </MainLayout>
</template>
