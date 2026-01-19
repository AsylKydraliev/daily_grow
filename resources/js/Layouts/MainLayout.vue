<script setup>
import { Link, router } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import ToastContainer from "@/Components/ToastContainer.vue";
import { ref } from "vue";
import UsersIcon from "@/Components/Icons/UsersIcon.vue";
import CounterpartiesIcon from "@/Components/Icons/CounterpartiesIcon.vue";
import StatisticsIcon from "@/Components/Icons/StatisticsIcon.vue";
import BranchesIcon from "@/Components/Icons/BranchesIcon.vue";
import SalesIcon from "@/Components/Icons/SalesIcon.vue";
import ProductReceiptsIcon from "@/Components/Icons/ProductReceiptsIcon.vue";
import ProductsIcon from "@/Components/Icons/ProductsIcon.vue";
import WarehouseIcon from "@/Components/Icons/WarehouseIcon.vue";

const currentUrl = ref(router.page.url);
</script>

<template>
    <div class="flex">
        <div class="flex flex-col p-3 bg-white border shadow w-60 h-screen sticky top-0">
            <div class="space-y-3">
                <div class="flex items-center">
                    <img src="../../../public/assets/logo-tech.png" alt="Daily Grow"/>
                </div>

                <div class="flex-1">
                    <ul class="pt-2 pb-4 space-y-1 text-sm">
                        <li class="rounded-sm">
                            <Link
                                :href="route('dashboard')"
                                class="flex items-center p-2 space-x-3 rounded-md"
                                :class="{ 'active': currentUrl === '/dashboard' }"
                            >
                                <StatisticsIcon />
                                <span>Статистика</span>
                            </Link>
                        </li>
                        <li class="rounded-sm" v-if="$page.props.auth.user?.canManageUsers">
                            <Link
                                :href="route('users.index')"
                                class="flex items-center p-2 space-x-3 rounded-md"
                                :class="{ 'active': currentUrl.startsWith('/users') }"
                            >
                                <UsersIcon/>
                                <span>Пользователи</span>
                            </Link>
                        </li>
                        <li class="rounded-sm">
                            <Link
                                :href="route('branches.index')"
                                class="flex items-center p-2 space-x-3 rounded-md"
                                :class="{ 'active': currentUrl.startsWith('/branches') }"
                            >
                                <BranchesIcon />
                                <span>Филиалы</span>
                            </Link>
                        </li>
                        <li class="rounded-sm">
                            <Link
                                :href="route('counterparties.index')"
                                class="flex items-center p-2 space-x-3 rounded-md"
                                :class="{ 'active': currentUrl.startsWith('/counterparties') }"
                            >
                                <CounterpartiesIcon/>
                                <span>Контрагенты</span>
                            </Link>
                        </li>
                        <li class="rounded-sm">
                            <Link
                                :href="route('products.index')"
                                class="flex items-center p-2 space-x-3 rounded-md"
                                :class="{ 'active': currentUrl.startsWith('/products') }"
                            >
                                <ProductsIcon />
                                <span>Товары</span>
                            </Link>
                        </li>
                        <li class="rounded-sm">
                            <Link
                                :href="route('product-receipts.index')"
                                class="flex items-center p-2 space-x-3 rounded-md"
                                :class="{ 'active': currentUrl.startsWith('/product-receipts') }"
                            >
                                <ProductReceiptsIcon />
                                <span>Приход товара</span>
                            </Link>
                        </li>
                        <li class="rounded-sm">
                            <Link
                                :href="route('sales.index')"
                                class="flex items-center p-2 space-x-3 rounded-md"
                                :class="{ 'active': currentUrl.startsWith('/sales') }"
                            >
                                <SalesIcon />
                                <span>Продажи</span>
                            </Link>
                        </li>
                        <li class="rounded-sm">
                            <Link
                                :href="route('warehouse.index')"
                                class="flex items-center p-2 space-x-3 rounded-md"
                                :class="{ 'active': currentUrl.startsWith('/warehouse') }"
                            >
                                <WarehouseIcon />
                                <span>Склад</span>
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container">
            <Dropdown align="right" class="text-right my-4">
                <template #trigger>
                    <span class="inline-flex rounded-md">
                        <button
                            type="button"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-xl leading-4 font-medium rounded-md text-orange-500 bg-white hover:text-orange-700 focus:outline-none transition ease-in-out duration-150"
                        >
                            {{ $page.props.auth.user.name }}

                            <svg
                                class="ms-2 -me-0.5 h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </span>
                </template>

                <template #content>
                    <DropdownLink :href="route('logout')" method="post" as="button">
                        <div class="flex items-center gap-2">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-6 h-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"
                                />
                            </svg>
                            Выйти
                        </div>
                    </DropdownLink>
                </template>
            </Dropdown>

            <div class="container mt-4 mx-6">
                <slot/>
            </div>
        </div>
    </div>
    <ToastContainer />
</template>

<style scoped>
.active {
    background: whitesmoke;
    box-shadow: rgba(99, 99, 99, 0.2) 0 2px 8px 0;
    color: rgb(249, 115, 22);
    font-weight: 600;
}
</style>
