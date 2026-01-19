<script setup>
defineProps({
    colspan: {
        type: Number,
        default: 1,
    },
    message: {
        type: String,
        default: 'Результаты не найдены',
    },
    description: {
        type: String,
        default: 'Попробуйте изменить параметры поиска',
    },
});
</script>

<template>
    <tr class="border-b">
        <td :colspan="colspan" class="px-6 py-8 text-center text-gray-500">
            <div class="flex flex-col items-center justify-center">
                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-lg font-medium">{{ message }}</p>
                <p class="text-sm mt-1">{{ description }}</p>
            </div>
        </td>
    </tr>
</template>

