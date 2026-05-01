<script setup>
import AuthenticatedLayout from '../layouts/AuthenticatedLayout.vue'

defineProps({
    totalProducts:   Number,
    totalCategories: Number,
    totalSuppliers:  Number,
    lowStockCount:   Number,
    outOfStockCount: Number,
})
</script>

<template>
    <AuthenticatedLayout>
        <template #header>Dashboard</template>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-sm text-gray-500">Total Products</p>
                <p class="text-3xl font-bold text-gray-800">{{ totalProducts }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-sm text-gray-500">Categories</p>
                <p class="text-3xl font-bold text-gray-800">{{ totalCategories }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-sm text-gray-500">Suppliers</p>
                <p class="text-3xl font-bold text-gray-800">{{ totalSuppliers }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 border-l-4 border-yellow-400">
                <p class="text-sm text-gray-500">Low Stock</p>
                <p class="text-3xl font-bold text-yellow-600">{{ lowStockCount }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-400">
                <p class="text-sm text-gray-500">Out of Stock</p>
                <p class="text-3xl font-bold text-red-600">{{ outOfStockCount }}</p>
            </div>
        </div>

    </AuthenticatedLayout>
</template>