<script setup>
import AuthenticatedLayout from '../layouts/AuthenticatedLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    products: Object, // Paginated data
})

const stockBadge = (product) => {
    if (product.stock_quantity <= 0)
        return { label: 'Out of Stock', class: 'bg-red-100 text-red-700' }
    if (product.stock_quantity <= product.low_stock_threshold)
        return { label: 'Low Stock', class: 'bg-yellow-100 text-yellow-700' }
    return { label: 'In Stock', class: 'bg-green-100 text-green-700' }
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>Products</template>

        <!-- Header Row -->
        <div class="flex items-center justify-between mb-6">
            <p class="text-gray-500 text-sm">{{ products.total }} products total</p>
            <Link
                :href="route('products.create')"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700"
            >
                + Add Product
            </Link>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">Product</th>
                        <th class="px-4 py-3 text-left">SKU</th>
                        <th class="px-4 py-3 text-left">Category</th>
                        <th class="px-4 py-3 text-left">Price</th>
                        <th class="px-4 py-3 text-left">Stock</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ product.name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ product.sku }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ product.category?.name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-800">₱{{ product.unit_price }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ product.stock_quantity }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-medium"
                                :class="stockBadge(product).class"
                            >
                                {{ stockBadge(product).label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 flex gap-2">
                            <Link
                                :href="route('products.edit', product.id)"
                                class="text-blue-500 hover:underline"
                            >
                                Edit
                            </Link>
                            <Link
                                :href="route('products.destroy', product.id)"
                                method="delete"
                                as="button"
                                class="text-red-500 hover:underline"
                                onclick="return confirm('Are you sure?')"
                            >
                                Delete
                            </Link>
                        </td>
                    </tr>

                    <!-- Empty state -->
                    <tr v-if="products.data.length === 0">
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                            No products found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex gap-2 mt-4">
            <Link
                v-for="link in products.links"
                :key="link.label"
                :href="link.url ?? '#'"
                v-html="link.label"
                class="px-3 py-1 rounded text-sm border"
                :class="link.active
                    ? 'bg-blue-600 text-white border-blue-600'
                    : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'"
            />
        </div>

    </AuthenticatedLayout>
</template>