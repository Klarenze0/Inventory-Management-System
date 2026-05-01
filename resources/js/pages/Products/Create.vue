<script setup>
import AuthenticatedLayout from '../layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

defineProps({
    categories: Array,
})

const form = useForm({
    name:                '',
    sku:                 '',
    barcode:             '',
    description:         '',
    category_id:         '',
    unit_price:          '',
    stock_quantity:      0,
    low_stock_threshold: 10,
    image:               null,
})

const submit = () => {
    form.post(route('products.store'), {
        forceFormData: true, // Kailangan para sa file upload
    })
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>Add Product</template>

        <div class="max-w-2xl bg-white rounded-xl shadow p-6">

            <form @submit.prevent="submit" class="space-y-4">

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Samsung TV"
                    />
                    <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                </div>

                <!-- SKU + Barcode -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                        <input
                            v-model="form.sku"
                            type="text"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="TV-001"
                        />
                        <p v-if="form.errors.sku" class="text-red-500 text-xs mt-1">{{ form.errors.sku }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Barcode</label>
                        <input
                            v-model="form.barcode"
                            type="text"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="1234567890"
                        />
                        <p v-if="form.errors.barcode" class="text-red-500 text-xs mt-1">{{ form.errors.barcode }}</p>
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select
                        v-model="form.category_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">-- Select Category --</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                            {{ cat.name }}
                        </option>
                    </select>
                </div>

                <!-- Price + Stock + Threshold -->
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price *</label>
                        <input
                            v-model="form.unit_price"
                            type="number"
                            step="0.01"
                            min="0"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0.00"
                        />
                        <p v-if="form.errors.unit_price" class="text-red-500 text-xs mt-1">{{ form.errors.unit_price }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity *</label>
                        <input
                            v-model="form.stock_quantity"
                            type="number"
                            min="0"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        <p v-if="form.errors.stock_quantity" class="text-red-500 text-xs mt-1">{{ form.errors.stock_quantity }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Low Stock Alert *</label>
                        <input
                            v-model="form.low_stock_threshold"
                            type="number"
                            min="0"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea
                        v-model="form.description"
                        rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Optional product description..."
                    />
                </div>

                <!-- Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                    <input
                        type="file"
                        accept="image/*"
                        class="w-full text-sm text-gray-500"
                        @change="form.image = $event.target.files[0]"
                    />
                    <p v-if="form.errors.image" class="text-red-500 text-xs mt-1">{{ form.errors.image }}</p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-2">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Product' }}
                    </button>
                    <Link
                        :href="route('products.index')"
                        class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-200"
                    >
                        Cancel
                    </Link>
                </div>

            </form>
        </div>

    </AuthenticatedLayout>
</template>