<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head, Link, useForm } from '@inertiajs/vue3'

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Products',  href: '/products'  },
    { title: 'Add Product', href: '/products/create' },
]

defineProps<{
    categories: Array<{ id: number, name: string }>
}>()

const form = useForm({
    name:                '',
    sku:                 '',
    barcode:             '',
    description:         '',
    category_id:         '',
    unit_price:          '',
    stock_quantity:      0,
    low_stock_threshold: 10,
    image:               null as File | null,
})

const submit = () => {
    form.post('/products', {
        forceFormData: true,
    })
}
</script>

<template>
    <Head title="Add Product" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 max-w-2xl">
            <div class="bg-white dark:bg-sidebar rounded-xl border border-sidebar-border/70 p-6">

                <h2 class="text-lg font-semibold mb-6">Add New Product</h2>

                <form @submit.prevent="submit" class="space-y-4">

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Product Name *</label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Samsung TV"
                        />
                        <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- SKU + Barcode -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">SKU *</label>
                            <input
                                v-model="form.sku"
                                type="text"
                                class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="TV-001"
                            />
                            <p v-if="form.errors.sku" class="text-red-500 text-xs mt-1">
                                {{ form.errors.sku }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Barcode</label>
                            <input
                                v-model="form.barcode"
                                type="text"
                                class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="1234567890"
                            />
                            <p v-if="form.errors.barcode" class="text-red-500 text-xs mt-1">
                                {{ form.errors.barcode }}
                            </p>
                        </div>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Category</label>
                        <select
                            v-model="form.category_id"
                            class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:bg-background focus:outline-none focus:ring-2 focus:ring-primary"
                        >
                            <option value="">-- Select Category --</option>
                            <option
                                v-for="cat in categories"
                                :key="cat.id"
                                :value="cat.id"
                            >
                                {{ cat.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Price + Stock + Threshold -->
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Unit Price *</label>
                            <input
                                v-model="form.unit_price"
                                type="number"
                                step="0.01"
                                min="0"
                                class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="0.00"
                            />
                            <p v-if="form.errors.unit_price" class="text-red-500 text-xs mt-1">
                                {{ form.errors.unit_price }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Stock Quantity *</label>
                            <input
                                v-model="form.stock_quantity"
                                type="number"
                                min="0"
                                class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-primary"
                            />
                            <p v-if="form.errors.stock_quantity" class="text-red-500 text-xs mt-1">
                                {{ form.errors.stock_quantity }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Low Stock Alert *</label>
                            <input
                                v-model="form.low_stock_threshold"
                                type="number"
                                min="0"
                                class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-primary"
                            />
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Optional product description..."
                        />
                    </div>

                    <!-- Image -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Product Image</label>
                        <input
                            type="file"
                            accept="image/*"
                            class="w-full text-sm text-muted-foreground"
                            @change="(e: any) => form.image = e.target.files[0]"
                        />
                        <p v-if="form.errors.image" class="text-red-500 text-xs mt-1">
                            {{ form.errors.image }}
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-primary text-primary-foreground px-6 py-2 rounded-lg text-sm hover:opacity-90 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Saving...' : 'Save Product' }}
                        </button>
                        <Link
                            href="/products"
                            class="bg-muted text-muted-foreground px-6 py-2 rounded-lg text-sm hover:opacity-90"
                        >
                            Cancel
                        </Link>
                    </div>

                </form>
            </div>
        </div>
    </AppLayout>
</template>